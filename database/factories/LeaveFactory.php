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
            'start_date' => fake()->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('now', '+2 days')->format('Y-m-d'),
            'reason' => fake()->sentence(3),
            'status' => null,
            'approved_at' => fake()->dateTimeBetween('-10 days', 'now'),
            'approved_by' => fake()->numberBetween(1, 4),
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => fake()->numberBetween(2, 7),
            'updated_by' => fake()->numberBetween(1, 3),
        ];
    }
}
