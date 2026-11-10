<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\PaystackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(
        protected CartService $cart,
        protected PaystackService $paystack,
    ) {}

    /**
     * The checkout page: the order summary and the Pay now button.
     */
    public function create(): View
    {
        $payload = $this->cart->payload();

        return view('checkout', [
            'items' => $payload['items'],
            'total' => $payload['total'],
            'idempotencyKey' => (string) Str::uuid(),
        ]);
    }

    /**
     * "Pay now": store the payment as pending, then send the shopper to Paystack.
     *
     * The amount always comes from the cart on the server, never from the form,
     * and one checkout submission can only ever open one payment.
     */
    public function pay(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'idempotency_key' => ['required', 'string', 'max:64'],
        ], [
            'idempotency_key.required' => 'Your checkout session expired. Refresh the page and try again.',
        ]);

        $user = $request->user();
        $payload = $this->cart->payload();
        $items = collect($payload['items']);

        if ($items->isEmpty()) {
            return redirect()->route('checkout.create')->with('error', 'Your cart is empty.');
        }

        $amount = (int) round(((float) $payload['total']) * 100);
        $currency = $this->currency();
        $cartHash = $this->cartHash((int) $user->id, $currency, $amount, $items);
        $idempotencyKey = $this->idempotencyKey((string) $validated['idempotency_key'], $cartHash);

        $payment = $this->existingPayment($idempotencyKey, (int) $user->id, $cartHash)
            ?? $this->savePendingPayment((int) $user->id, $idempotencyKey, $cartHash, $amount, $currency, $items);

        if ((int) $payment->user_id !== (int) $user->id) {
            throw ValidationException::withMessages([
                'idempotency_key' => 'This checkout session is no longer valid. Refresh the page and try again.',
            ]);
        }

        if ($payment->isSuccessful()) {
            return $this->toResult($payment);
        }

        // A charge is already known to Paystack for this payment: never start a
        // second one, send the shopper to the result instead.
        if ($payment->hasChargeInFlight()) {
            return redirect()->route('checkout.success', ['reference' => $payment->reference])
                ->with('error', $payment->failure_reason ?: 'We are confirming this payment with Paystack. It only takes a moment.');
        }

        // Nothing to charge: settle straight away rather than send the shopper
        // to a gateway that cannot process a zero amount.
        if ($amount <= 0) {
            $this->settleFreePayment($payment);

            return $this->toResult($payment);
        }

        // Already opened with Paystack (a second tab, or a re-submitted form):
        // go back to the same checkout page instead of starting another one.
        if ($payment->authorization_url) {
            return redirect()->away($payment->authorization_url);
        }

        try {
            $session = $this->paystack->initialize(
                amount: $payment->amount,
                currency: $payment->currency,
                email: $user->email,
                reference: $payment->reference,
                callbackUrl: config('payment.paystack.callback_url'),
                metadata: ['payment_id' => $payment->id],
            );
        } catch (RuntimeException $exception) {
            $payment->update([
                'status' => Payment::STATUS_FAILED,
                'failure_reason' => $exception->getMessage(),
                'verified_at' => now(),
            ]);

            return redirect()->route('checkout.create')->with('error', $exception->getMessage());
        }

        $payment->update(['authorization_url' => $session['authorization_url']]);

        return redirect()->away($session['authorization_url']);
    }

    /**
     * Where Paystack sends the shopper back to: confirm the charge here.
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = (string) ($request->query('reference') ?: $request->query('trxref'));
        $payment = $reference === '' ? null : Payment::query()->where('reference', $reference)->first();

        if ($payment === null) {
            return redirect()->route('checkout.create')->with('error', 'We could not find that payment. If you were charged, contact support with your reference.');
        }

        try {
            $this->confirmPayment($payment);
        } catch (RuntimeException $exception) {
            return $this->toResult($payment, $exception->getMessage());
        }

        return $this->toResult($payment);
    }

    /**
     * "Check again" on the result page, for callbacks that never arrive.
     */
    public function recheck(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reference' => ['required', 'string', 'max:64'],
        ]);

        $payment = Payment::query()
            ->where('reference', $validated['reference'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            $this->confirmPayment($payment);
        } catch (RuntimeException $exception) {
            return $this->toResult($payment, $exception->getMessage());
        }

        return $this->toResult($payment);
    }

    /**
     * The payment result page.
     */
    public function success(Request $request): View
    {
        $payment = Payment::query()
            ->where('reference', (string) $request->query('reference'))
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return view('checkout-success', ['payment' => $payment]);
    }

    /**
     * Ask Paystack what happened to this payment and record the answer.
     */
    private function confirmPayment(Payment $payment): Payment
    {
        $verification = $this->paystack->verify($payment->reference);

        return $this->settleFromGateway($payment, $verification);
    }

    /**
     * Work out which payment this submission refers to, reusing an existing one
     * instead of opening a second charge.
     *
     * Two guards, in order:
     *  - the idempotency key sent with the form stops a double click or a
     *    re-posted form from creating a second record;
     *  - a pending payment for the same cart stops a second browser tab from
     *    charging the same plugins twice.
     */
    private function existingPayment(string $idempotencyKey, int $userId, string $cartHash): ?Payment
    {
        $byKey = Payment::query()->where('idempotency_key', $idempotencyKey)->first();

        if ($byKey === null) {
            return Payment::query()
                ->where('user_id', $userId)
                ->where('cart_hash', $cartHash)
                ->pending()
                ->latest('id')
                ->first();
        }

        if ((int) $byKey->user_id !== $userId) {
            throw ValidationException::withMessages([
                'idempotency_key' => 'This checkout session is no longer valid. Refresh the page and try again.',
            ]);
        }

        if ($byKey->isSuccessful() || $byKey->hasChargeInFlight()) {
            return $byKey;
        }

        // A failed attempt is retried on the same row, so one checkout session
        // still maps to one payment record.
        return $byKey->hasFailed() ? $this->restartFailedPayment($byKey) : $byKey;
    }

    /**
     * A failed attempt gets a fresh reference and goes back to pending, keeping
     * what went wrong the first time on the record.
     */
    private function restartFailedPayment(Payment $payment): Payment
    {
        $previousReference = $payment->reference;
        $previousFailure = $payment->failure_reason;

        $payment->update([
            'reference' => $this->newReference(),
            'status' => Payment::STATUS_PENDING,
            'authorization_url' => null,
            'failure_reason' => null,
            'verified_at' => null,
            'paid_at' => null,
            'gateway_payload' => array_merge($payment->gateway_payload ?? [], [
                'previous_reference' => $previousReference,
                'previous_failure' => $previousFailure,
            ]),
        ]);

        return $payment;
    }

    /**
     * Persist the payment before anything is charged, together with the plugin
     * total it will be checked against once Paystack reports back.
     *
     * @param  Collection<int, array<string, mixed>>  $items
     */
    private function savePendingPayment(int $userId, string $idempotencyKey, string $cartHash, int $amount, string $currency, Collection $items): Payment
    {
        return Payment::query()->createOrFirst(['idempotency_key' => $idempotencyKey], [
            'user_id' => $userId,
            'reference' => $this->newReference(),
            'cart_hash' => $cartHash,
            'amount' => $amount,
            'currency' => $currency,
            'status' => Payment::STATUS_PENDING,
            'items' => $items->map(fn (array $item): array => [
                'plugin_id' => $item['plugin_id'],
                'name' => $item['name'],
                'price' => (float) $item['price'],
            ])->values()->all(),
        ]);
    }

    /**
     * Compare what Paystack says was charged against the total of the plugins
     * on this payment, then record the outcome exactly once.
     *
     * @param  array<string, mixed>  $verification
     */
    private function settleFromGateway(Payment $payment, array $verification): Payment
    {
        if ($payment->isSuccessful()) {
            return $payment;
        }

        $gatewayStatus = strtolower((string) ($verification['status'] ?? ''));
        $gatewayMessage = (string) ($verification['gateway_response'] ?? '');
        $chargedAmount = (int) ($verification['amount'] ?? -1);
        $chargedCurrency = strtoupper((string) ($verification['currency'] ?? ''));
        $payload = array_merge($payment->gateway_payload ?? [], ['verification' => $verification]);

        // Still being processed by the gateway: leave the payment open rather
        // than calling it failed while money may still be moving.
        if ($gatewayStatus !== 'success' && ! $this->isFinalFailure($gatewayStatus)) {
            $payment->update(['gateway_payload' => $payload]);

            return $payment;
        }

        if ($gatewayStatus !== 'success') {
            $payment->update([
                'status' => Payment::STATUS_FAILED,
                'failure_reason' => $gatewayMessage ?: 'Paystack reported this payment as '.$gatewayStatus,
                'gateway_payload' => $payload,
                'verified_at' => now(),
            ]);

            return $payment;
        }

        $expected = $payment->expectedAmount();

        if ($chargedAmount !== $payment->amount || $chargedAmount !== $expected) {
            $payment->update([
                'status' => Payment::STATUS_FAILED,
                'failure_reason' => sprintf(
                    'Amount mismatch: Paystack charged %d, this payment is for %d (%d from the plugin total).',
                    $chargedAmount,
                    $payment->amount,
                    $expected,
                ),
                'gateway_payload' => $payload,
                'verified_at' => now(),
            ]);

            return $payment;
        }

        if ($chargedCurrency !== strtoupper($payment->currency)) {
            $payment->update([
                'status' => Payment::STATUS_FAILED,
                'failure_reason' => sprintf('Currency mismatch: Paystack charged %s, expected %s.', $chargedCurrency, $payment->currency),
                'gateway_payload' => $payload,
                'verified_at' => now(),
            ]);

            return $payment;
        }

        $payment->update([
            'status' => Payment::STATUS_SUCCESS,
            'failure_reason' => null,
            'gateway_payload' => $payload,
            'verified_at' => now(),
            'paid_at' => $payment->paid_at ?? now(),
        ]);

        $this->clearPurchasedItems($payment);

        return $payment;
    }

    /**
     * A zero-value cart never reaches the gateway.
     */
    private function settleFreePayment(Payment $payment): Payment
    {
        if ($payment->isSuccessful()) {
            return $payment;
        }

        $payment->update([
            'status' => Payment::STATUS_SUCCESS,
            'verified_at' => now(),
            'paid_at' => $payment->paid_at ?? now(),
            'gateway_payload' => array_merge($payment->gateway_payload ?? [], ['free' => true]),
        ]);

        $this->clearPurchasedItems($payment);

        return $payment;
    }

    /**
     * The plugins have been bought, so they leave the cart. Done by user id so
     * it still works when the callback lands without a live session.
     */
    private function clearPurchasedItems(Payment $payment): void
    {
        CartItem::query()
            ->where('user_id', $payment->user_id)
            ->whereIn('plugin_id', collect($payment->items)->pluck('plugin_id')->all())
            ->delete();
    }

    private function toResult(Payment $payment, ?string $message = null): RedirectResponse
    {
        $redirect = redirect()->route('checkout.success', ['reference' => $payment->reference]);

        return $message ? $redirect->with('error', $message) : $redirect;
    }

    /**
     * Whether Paystack has reached a verdict on this payment that must not be
     * attempted a second time without a human looking at it.
     */
    private function isFinalFailure(string $gatewayStatus): bool
    {
        return in_array($gatewayStatus, ['failed', 'abandoned', 'reverted'], true);
    }

    /**
     * A fingerprint of who is buying what, for reusing an open payment.
     *
     * @param  Collection<int, array<string, mixed>>  $items
     */
    private function cartHash(int $userId, string $currency, int $amount, Collection $items): string
    {
        $pluginIds = $items->pluck('plugin_id')->map(fn ($id): int => (int) $id)->sort()->values()->implode(',');

        return hash('sha256', implode('|', [$userId, $currency, $amount, $pluginIds]));
    }

    /**
     * The idempotency key stored for this submission: the same form sent twice
     * for the same cart collides, while a changed cart is a new attempt.
     */
    private function idempotencyKey(string $clientKey, string $cartHash): string
    {
        return hash('sha256', $clientKey.'|'.$cartHash);
    }

    private function currency(): string
    {
        return strtoupper((string) config('payment.paystack.currency', 'NGN'));
    }

    private function newReference(): string
    {
        return 'TOON-'.Str::upper(Str::random(24));
    }
}
