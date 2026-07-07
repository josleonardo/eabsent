<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'school_location_id' => fake()->numberBetween(1, 2),
            'schedule_group_id' => fake()->numberBetween(1, 2),
            'active' => fake()->boolean(90),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            UserProfile::factory()->create([
                'user_id' => $user->id,
                'school_location_id' => $user->school_location_id,
                'schedule_group_id' => $user->schedule_group_id,
                'active' => $user->active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'created_by' => $user->created_by,
                'updated_by' => $user->updated_by,
            ]);
        });
    }
}
