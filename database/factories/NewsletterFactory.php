<?php

namespace Database\Factories;

use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Newsletter>
 */
class NewsletterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => fake()->sentence(6),
            'message' => fake()->paragraph(),
            'audience' => Newsletter::AUDIENCE_SUBSCRIBERS,
            'recipients_count' => fake()->numberBetween(1, 50),
            'sent_by' => User::factory(),
        ];
    }
}
