<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SchoolLocation>
 */
class SchoolLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lat = -90 + (mt_rand() / mt_getrandmax()) * 180;
        $lng = -180 + (mt_rand() / mt_getrandmax()) * 360;

        return [
            'name' => fake()->unique()->word(),
            'key' => fake()->unique()->word(),
            'latitude' => round($lat, 6),
            'longitude' => round($lng, 6),
            'radius' => fake()->numberBetween(1, 100),
            'active' => fake()->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
