<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Builds fake Product rows for seeding and testing.
 *
 * @extends Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * The default values of one fake product.
     */
    public function definition(): array
    {
        return [
            // Three random words as the product name
            'product'     => fake()->words(3, true),
            // A random paragraph as the description
            'description' => fake()->paragraph(),
            // One of the four allowed status codes
            'status'      => fake()->randomElement(['A', 'C', 'H', 'X']),
            // Creates a user for the product unless one is supplied (see recycle() below)
            'user_id'     => User::factory(),
        ];
    }
}