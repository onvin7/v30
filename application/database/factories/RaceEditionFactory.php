<?php

namespace Database\Factories;

use App\Enums\RaceEditionStatus;
use App\Models\Event;
use App\Models\RaceEdition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RaceEdition>
 */
class RaceEditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'year' => fake()->numberBetween(2015, 2035),
            'event_date' => fake()->dateTimeBetween('+1 week', '+2 years')->format('Y-m-d'),
            'date_confirmed' => true,
            'is_current' => false,
            'status' => RaceEditionStatus::Published,
            'venue' => fake()->city(),
            'course_summary' => fake()->sentence(),
            'fee_notes' => null,
        ];
    }
}
