<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Correction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Correction>
 */
class CorrectionFactory extends Factory
{
    protected $model = Correction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attendance = Attendance::query()->inRandomOrder()->first();
        $correctIn = fake()->optional(0.7)->time();
        $correctOut = fake()->optional(0.7)->time();
        $correctStatus = fake()->optional(0.4)->numberBetween(0, 5);

        if (!$correctIn && !$correctOut && is_null($correctStatus)) {
            $correctIn = fake()->time();
        }

        return [
            'attendance_id' => $attendance?->id ?? Attendance::factory(),

            'correct_in' => $correctIn,
            'correct_out' => $correctOut,
            'correct_status' => $correctStatus,

            'description' => fake()->sentence(),

            'status' => fake()->numberBetween(0, 2),

            'processed_at' => null,
            'processed_by' => null,

            'created_by' => User::query()->inRandomOrder()->value('id')
                ?? User::factory(),

            'updated_by' => User::query()->inRandomOrder()->value('id')
                ?? User::factory(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => [
            'status' => Correction::STATUS_PENDING,
            'processed_at' => null,
            'processed_by' => null,
            'attendance_history_id' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(function () {
            return [
                'status' => Correction::STATUS_APPROVED,
                'processed_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'processed_by' => User::query()->inRandomOrder()->value('id')
                    ?? User::factory(),
            ];
        });
    }

    public function rejected(): static
    {
        return $this->state(function () {
            return [
                'status' => Correction::STATUS_REJECTED,
                'processed_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'processed_by' => User::query()->inRandomOrder()->value('id')
                    ?? User::factory(),
            ];
        });
    }

    public function revoked(): static
    {
        return $this->state(function () {
            return [
                'status' => Correction::STATUS_REVOKED,
                'processed_at' => fake()->dateTimeBetween('-30 days', 'now'),
                'processed_by' => User::query()->inRandomOrder()->value('id')
                    ?? User::factory(),
            ];
        });
    }
}
