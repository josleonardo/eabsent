<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => fake()->numberBetween(1, 20),
            'name' => fake()->word(),
            'url' => fake()->url(),
            'platform' => fake()->numberBetween(0, 1),
            'order' => fake()->numberBetween(1, 20),
            'icon' => fake()->words(rand(1, 3), true),
            'active' => fake()->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
