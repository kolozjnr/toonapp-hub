<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * The model to create for the factory.
     *
     * @var class-string<Payment>
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(500, 50000);

        return [
            'user_id' => User::factory(),
            'reference' => 'TOON-'.Str::upper(fake()->unique()->regexify('[A-Z0-9]{24}')),
            'idempotency_key' => (string) Str::uuid(),
            'cart_hash' => hash('sha256', fake()->unique()->uuid()),
            'amount' => $amount,
            'currency' => 'NGN',
            'gateway' => Payment::GATEWAY_PAYSTACK,
            'status' => Payment::STATUS_PENDING,
            'items' => [
                ['plugin_id' => fake()->randomNumber(4), 'name' => fake()->words(2, true), 'price' => $amount / 100],
            ],
        ];
    }

    /**
     * Indicate that the shopper has been charged and the payment settled.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => Payment::STATUS_SUCCESS,
            'verified_at' => now(),
            'paid_at' => now(),
        ]);
    }
}
