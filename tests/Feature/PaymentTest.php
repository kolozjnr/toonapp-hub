<?php

use App\Models\CartItem;
use App\Models\Payment;
use App\Models\Plugin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

/**
 * Fake the Paystack call the checkout flow makes, and nothing else.
 */
function fakePaystackInitialize(string $authorizationUrl = 'https://checkout.paystack.com/abc123'): void
{
    Http::preventStrayRequests();
    Http::fake([
        'api.paystack.co/*' => Http::response([
            'status' => true,
            'message' => 'Authorization URL created',
            'data' => [
                'authorization_url' => $authorizationUrl,
                'access_code' => 'access-code-1',
                'reference' => 'ignored-by-our-own-record',
            ],
        ]),
    ]);
}

/**
 * @param  array<string, mixed>  $verification
 */
function fakePaystackVerify(array $verification): void
{
    Http::preventStrayRequests();
    Http::fake([
        'api.paystack.co/transaction/verify/*' => Http::response([
            'status' => true,
            'message' => 'Transaction fetched',
            'data' => $verification,
        ]),
    ]);
}

function livePlugin(string $name, float $price): Plugin
{
    return Plugin::create([
        'name' => $name,
        'price' => $price,
        'category' => 'Plugin',
        'is_live' => true,
    ]);
}

/**
 * A payment whose plugin snapshot adds up to the amount being verified.
 */
function paymentFor(User $user, Plugin $plugin, int $amount, bool $successful = false): Payment
{
    $factory = Payment::factory()->for($user);

    if ($successful) {
        $factory = $factory->successful();
    }

    return $factory->create([
        'amount' => $amount,
        'currency' => 'NGN',
        'items' => [
            ['plugin_id' => $plugin->id, 'name' => $plugin->name, 'price' => $amount / 100],
        ],
    ]);
}

test('pay now saves a pending payment for the cart total and sends the shopper to paystack', function () {
    fakePaystackInitialize();

    $plugin = livePlugin('Pay Me Plugin', 25);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/cart/add', ['plugin_id' => $plugin->id])
        ->assertOk();

    $this->actingAs($user)
        ->post('/checkout/pay', ['idempotency_key' => 'checkout-session-1'])
        ->assertRedirect('https://checkout.paystack.com/abc123');

    $payment = Payment::sole();

    expect($payment->status)->toBe(Payment::STATUS_PENDING)
        ->and($payment->user_id)->toBe($user->id)
        ->and($payment->amount)->toBe(2500)
        ->and($payment->currency)->toBe('NGN')
        ->and($payment->authorization_url)->toBe('https://checkout.paystack.com/abc123')
        ->and(collect($payment->items)->sum('price'))->toEqual(25.0);

    Http::assertSent(fn (ClientRequest $request): bool => $request->url() === 'https://api.paystack.co/transaction/initialize'
        && $request['amount'] === 2500
        && $request['currency'] === 'NGN'
        && $request['email'] === $user->email);
});

test('the checkout page lists the cart total and a checkout session key', function () {
    $plugin = livePlugin('Shown At Checkout', 19);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->postJson('/cart/add', ['plugin_id' => $plugin->id])
        ->assertOk();

    $this->actingAs($user)
        ->get('/checkout')
        ->assertOk()
        ->assertSee('Shown At Checkout')
        ->assertSee('Pay now')
        ->assertSee('idempotency_key', false);
});

test('posting pay now twice with the same checkout key cannot open a second payment', function () {
    fakePaystackInitialize();

    $plugin = livePlugin('Double Click Plugin', 30);
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/cart/add', ['plugin_id' => $plugin->id])->assertOk();

    $this->actingAs($user)
        ->post('/checkout/pay', ['idempotency_key' => 'checkout-session-1'])
        ->assertRedirect('https://checkout.paystack.com/abc123');

    $this->actingAs($user)
        ->post('/checkout/pay', ['idempotency_key' => 'checkout-session-1'])
        ->assertRedirect('https://checkout.paystack.com/abc123');

    expect(Payment::count())->toBe(1);
    Http::assertSentCount(1);
});

test('a second checkout tab for the same cart reuses the payment that is already open', function () {
    fakePaystackInitialize();

    $plugin = livePlugin('Two Tabs Plugin', 40);
    $user = User::factory()->create();

    $this->actingAs($user)->postJson('/cart/add', ['plugin_id' => $plugin->id])->assertOk();

    $this->actingAs($user)
        ->post('/checkout/pay', ['idempotency_key' => 'first-tab'])
        ->assertRedirect('https://checkout.paystack.com/abc123');

    $this->actingAs($user)
        ->post('/checkout/pay', ['idempotency_key' => 'second-tab'])
        ->assertRedirect('https://checkout.paystack.com/abc123');

    expect(Payment::count())->toBe(1);
    Http::assertSentCount(1);
});

test('the callback marks the payment successful when paystack confirms the cart total', function () {
    $plugin = livePlugin('Confirmed Plugin', 25);
    $user = User::factory()->create();
    CartItem::create(['user_id' => $user->id, 'plugin_id' => $plugin->id]);

    $payment = paymentFor($user, $plugin, 2500);

    fakePaystackVerify([
        'status' => 'success',
        'amount' => 2500,
        'currency' => 'NGN',
        'gateway_response' => 'Successful',
    ]);

    $this->actingAs($user)
        ->get('/payment/paystack/callback?reference='.$payment->reference)
        ->assertRedirect(route('checkout.success', ['reference' => $payment->reference]));

    $payment->refresh();

    expect($payment->status)->toBe(Payment::STATUS_SUCCESS)
        ->and($payment->paid_at)->not->toBeNull()
        ->and($payment->verified_at)->not->toBeNull()
        ->and($payment->failure_reason)->toBeNull();

    // The bought plugins are out of the cart.
    expect(CartItem::where('user_id', $user->id)->count())->toBe(0);

    Http::assertSent(fn (ClientRequest $request): bool => $request->url() === 'https://api.paystack.co/transaction/verify/'.$payment->reference);
});

test('a payment charged for a different amount is not marked successful', function () {
    $plugin = livePlugin('Mispriced Plugin', 25);
    $user = User::factory()->create();
    $payment = paymentFor($user, $plugin, 2500);

    fakePaystackVerify([
        'status' => 'success',
        'amount' => 1900,
        'currency' => 'NGN',
        'gateway_response' => 'Successful',
    ]);

    $this->actingAs($user)
        ->get('/payment/paystack/callback?reference='.$payment->reference)
        ->assertRedirect(route('checkout.success', ['reference' => $payment->reference]));

    $payment->refresh();

    expect($payment->status)->toBe(Payment::STATUS_FAILED)
        ->and($payment->paid_at)->toBeNull()
        ->and($payment->failure_reason)->toContain('Amount mismatch');
});

test('a charge paystack reports as failed marks the payment failed', function () {
    $plugin = livePlugin('Declined Plugin', 25);
    $user = User::factory()->create();
    $payment = paymentFor($user, $plugin, 2500);

    fakePaystackVerify([
        'status' => 'failed',
        'amount' => 2500,
        'currency' => 'NGN',
        'gateway_response' => 'Insufficient funds',
    ]);

    $this->actingAs($user)
        ->get('/payment/paystack/callback?reference='.$payment->reference)
        ->assertRedirect(route('checkout.success', ['reference' => $payment->reference]));

    $payment->refresh();

    expect($payment->status)->toBe(Payment::STATUS_FAILED)
        ->and($payment->failure_reason)->toBe('Insufficient funds');
});

test('reconfirming an already successful payment leaves the original charge in place', function () {
    $plugin = livePlugin('Already Paid Plugin', 25);
    $user = User::factory()->create();
    $payment = paymentFor($user, $plugin, 2500, successful: true);
    $paidAt = $payment->paid_at;

    fakePaystackVerify([
        'status' => 'success',
        'amount' => 2500,
        'currency' => 'NGN',
        'gateway_response' => 'Successful',
    ]);

    $this->actingAs($user)
        ->get('/payment/paystack/callback?reference='.$payment->reference)
        ->assertRedirect(route('checkout.success', ['reference' => $payment->reference]));

    $payment->refresh();

    expect($payment->status)->toBe(Payment::STATUS_SUCCESS)
        ->and($payment->paid_at->toDateTimeString())->toBe($paidAt->toDateTimeString());
});

test('a shopper cannot open another shopper payment result', function () {
    $plugin = livePlugin('Private Plugin', 25);
    $owner = User::factory()->create();
    $payment = paymentFor($owner, $plugin, 2500);
    $otherShopper = User::factory()->create();

    $this->actingAs($otherShopper)
        ->get('/checkout/success?reference='.$payment->reference)
        ->assertNotFound();
});

test('the payment result page shows the state of the payment', function (string $status, ?string $failureReason, string $expectedText) {
    $plugin = livePlugin('Result Plugin', 25);
    $user = User::factory()->create();
    $payment = paymentFor($user, $plugin, 2500);

    $payment->update([
        'status' => $status,
        'failure_reason' => $failureReason,
        'verified_at' => now(),
        'paid_at' => $status === Payment::STATUS_SUCCESS ? now() : null,
    ]);

    $this->actingAs($user)
        ->get('/checkout/success?reference='.$payment->reference)
        ->assertOk()
        ->assertSee($expectedText)
        ->assertSee($payment->reference);
})->with([
    'a payment still being confirmed' => [Payment::STATUS_PENDING, null, 'Confirming your payment'],
    'a payment that went through' => [Payment::STATUS_SUCCESS, null, 'Your order is confirmed'],
    'a payment that was declined' => [Payment::STATUS_FAILED, 'Insufficient funds', 'Insufficient funds'],
]);

test('checkout requires a signed in shopper', function () {
    $this->get('/checkout')->assertRedirect(route('login'));

    $this->post('/checkout/pay', ['idempotency_key' => 'checkout-session-1'])
        ->assertRedirect(route('login'));
});

test('pay now without a checkout session asks the shopper to refresh', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/checkout/pay', [])
        ->assertSessionHasErrors('idempotency_key');

    expect(Payment::count())->toBe(0);
});

test('pay now with an empty cart shows an error instead of charging', function () {
    Http::preventStrayRequests();

    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/checkout/pay', ['idempotency_key' => 'checkout-session-1'])
        ->assertRedirect(route('checkout.create'));

    expect(Payment::count())->toBe(0);
});
