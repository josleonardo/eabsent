<?php

namespace Database\Factories;

use App\Models\ScheduleGroup;
use App\Models\User;
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
        $date = fake()->dateTimeBetween('-10 days', 'now')->format('Y-m-d');
        $dayOfWeek = (int) date('w', strtotime($date));

        $user = User::query()
            ->whereNotNull('schedule_group_id')
            ->inRandomOrder()
            ->first();

        if (! $user) {
            $scheduleGroup = ScheduleGroup::factory()->create();
            $user = User::factory()->create([
                'schedule_group_id' => $scheduleGroup->id,
            ]);
        }

        $scheduleGroup = $user->scheduleGroup;

        if (! $scheduleGroup) {
            return [
                'user_id' => $user->id,
                'date' => $date,
                'sched_in' => null,
                'sched_out' => null,
                'actual_in' => null,
                'actual_out' => null,
                'status' => 5,
                'active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        $schedule = $scheduleGroup->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->wherePivot('active', true)
            ->first();

        if (! $schedule) {
            return [
                'user_id' => $user->id,
                'date' => $date,
                'sched_in' => null,
                'sched_out' => null,
                'actual_in' => null,
                'actual_out' => null,
                'status' => 5,
                'active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ];
        }

        $schedIn = $schedule->check_in_time;
        $schedOut = $schedule->check_out_time;

        $actualIn = fake()->boolean(80)
            ? fake()->dateTimeBetween("$date $schedIn -10 minutes", "$date $schedIn +20 minutes")
            : null;

        $actualOut = $actualIn
            ? fake()->dateTimeBetween("$date $schedOut -1 hour", "$date $schedOut +1 hour")
            : null;

        $status = $actualIn
            ? ($actualIn->format('H:i:s') > $schedIn ? 2 : 1)
            : 0;

        return [
            'user_id' => $user->id,
            'date' => $date,
            'sched_in' => $schedIn,
            'sched_out' => $schedOut,
            'actual_in' => $actualIn,
            'actual_out' => $actualOut,
            'status' => $status,
            'active' => true,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
