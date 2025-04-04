<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 7),
            'schedule_id' => fake()->numberBetween(1, 5),
            'day_of_week' => fake()->numberBetween(0, 4),
            'date' => fake()->date(),
            'sched_check_in' => fake()->time(),
            'sched_check_out' => fake()->time(),
            'real_check_in' => fake()->time(),
            'real_check_out' => fake()->time(),
            'status' => fake()->randomElement(['present', 'absent', 'late']),
            'active' => fake()->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
