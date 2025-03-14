<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'nik' => fake()->unique()->numerify('################'),
            'nuptk' => fake()->boolean(50) ? fake()->unique()->numerify('################') : null,
            'fullname' => fake()->name(),
            'position' => fake()->jobTitle(),
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'employment_start' => fake()->date(),
            'employment_end' => fake()->boolean(10) ? fake()->date() : null,
            'active' => null,
            'created_at' => null,
            'updated_at' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }
}
