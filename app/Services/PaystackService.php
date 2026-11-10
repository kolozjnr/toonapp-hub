<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Thin client for the Paystack REST API, configured from config/payment.php.
 */
class PaystackService
{
    private const BASE_URL = 'https://api.paystack.co';

    private const CONNECT_TIMEOUT = 5;

    private const TIMEOUT = 15;

    /**
     * Start a checkout session and get the page to send the shopper to.
     *
     * @param  array<string, mixed>  $metadata
     * @return array{authorization_url: string, access_code: string, reference: string}
     */
    public function initialize(int $amount, string $currency, string $email, string $reference, ?string $callbackUrl, array $metadata = []): array
    {
        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'email' => $email,
            'reference' => $reference,
            'callback_url' => $callbackUrl,
        ];

        if ($metadata !== []) {
            $payload['metadata'] = $metadata;
        }

        $response = $this->client()->post(self::BASE_URL.'/transaction/initialize', $payload);

        return $this->data($response, 'Unable to start the payment with Paystack.');
    }

    /**
     * Ask Paystack whether this reference was actually charged.
     *
     * @return array<string, mixed>
     */
    public function verify(string $reference): array
    {
        $response = $this->client()->get(self::BASE_URL.'/transaction/verify/'.rawurlencode($reference));

        return $this->data($response, 'Paystack could not confirm this payment.');
    }

    /**
     * A configured client with tight timeouts and the secret key attached.
     */
    private function client(): PendingRequest
    {
        $secretKey = config('payment.paystack.secret_key');

        if (! $secretKey) {
            throw new RuntimeException('Paystack is not configured. Set PAYSTACK_SECRET_KEY in your .env file.');
        }

        return Http::withToken($secretKey)
            ->connectTimeout(self::CONNECT_TIMEOUT)
            ->timeout(self::TIMEOUT)
            ->acceptJson();
    }

    /**
     * Unwrap a Paystack response, turning gateway problems into a message the
     * shopper can be shown instead of a stack trace.
     *
     * @return array<string, mixed>
     */
    private function data(Response $response, string $fallback): array
    {
        if ($response->failed()) {
            throw new RuntimeException($response->json('message') ?: $fallback);
        }

        $data = $response->json('data');

        if (! is_array($data)) {
            throw new RuntimeException($response->json('message') ?: $fallback);
        }

        return $data;
    }
}
