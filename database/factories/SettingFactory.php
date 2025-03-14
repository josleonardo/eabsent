<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'setting_name' => fake()->unique()->word(),
            'key' => fake()->word(),
            'value_1' => fake()->randomElement([fake()->word(), fake()->numberBetween(1, 100)]),
            'value_2' => fake()->randomElement([fake()->word(), fake()->numberBetween(1, 100)]),
            'active' => fake()->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
