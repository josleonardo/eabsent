<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Correction>
 */
class CorrectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => fake()->date(),
            'actual_in' => fake()->time(),
            'actual_out' => fake()->time(),
            'reason' => fake()->sentence(3),
            'status' => null,
            'approved_at' => null,
            'approved_by' => null,
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => fake()->numberBetween(2, 7),
            'updated_by' => 1,
        ];
    }
}
