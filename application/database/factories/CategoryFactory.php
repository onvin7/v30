<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\RaceEdition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'race_edition_id' => RaceEdition::factory(),
            'code' => fake()->unique()->slug(2),
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'distance_km' => fake()->randomElement([3, 7, 30]),
            'technique' => fake()->randomElement(['classic', 'free']),
            'entry_fee_cents' => null,
            'sort_order' => 0,
        ];
    }
}
