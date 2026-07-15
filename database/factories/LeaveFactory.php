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
            'user_id' => fake()->numberBetween(3, 7),
            'leave_type_id' => fake()->numberBetween(1, 4),
            'start_date' => fake()->dateTimeBetween('-10 days', 'now')->format('Y-m-d'),
            'end_date' => fake()->dateTimeBetween('now', '+2 days')->format('Y-m-d'),
            'description' => fake()->boolean() ? fake()->sentence(3) : null,
            'status' => fake()->numberBetween(0, 5),
            'processed_at' => null,
            'processed_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => fake()->numberBetween(2, 7),
            'updated_by' => fake()->numberBetween(1, 3),
        ];
    }
}
