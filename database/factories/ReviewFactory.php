<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Plugin has no factory in this project, so callers pass plugin_id.
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->boolean(70) ? fake()->sentence() : null,
        ];
    }

    /**
     * A review that only carries the star rating.
     */
    public function ratingOnly(): static
    {
        return $this->state(fn (): array => ['comment' => null]);
    }
}
