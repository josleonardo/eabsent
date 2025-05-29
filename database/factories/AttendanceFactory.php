<?php

namespace Database\Factories;

use App\Models\Attendance;
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
        $user = User::inRandomOrder()->first();
        $date = fake()->dateTimeBetween('-10 days', 'now')->format('Y-m-d');
        
        $dayOfWeek = date('w', strtotime($date));  // 0 = Sunday, 6 = Saturday

        // Find the user's schedule for this day
        $schedule = $user->schedules
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            // If no schedule, skip by returning null times and marking as holiday
            return [
                'user_id' => $user->id,
                'date' => $date,
                'sched_in' => null,
                'sched_out' => null,
                'actual_in' => null,
                'actual_out' => null,
                'status' => 5, // 5 = holiday
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
            : 0; // 0 = absent, 1 = present, 2 = late

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
