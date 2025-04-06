<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Leave>
 */
class LeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'reason' => fake()->sentence(3),
            'approve_status' => fake()->randomElement([null, 0, 1]),
            'approved_at' => fake()->dateTime(),
            'approved_by' => fake()->numberBetween(1, 4),
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => fake()->numberBetween(2, 7),
            'updated_by' => 1,
        ];
    }
}
