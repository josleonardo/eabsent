<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checkOuts = [
            '15:00:00',
            '16:00:00',
        ];

        $schedules = [
            ['day_of_week' => 1],
            ['day_of_week' => 2],
            ['day_of_week' => 3],
            ['day_of_week' => 4],
            ['day_of_week' => 5],
        ];

        foreach ($checkOuts as $checkOut) {
            foreach ($schedules as $schedule) {
                Schedule::factory()->create([
                    'day_of_week' => $schedule['day_of_week'],
                    'check_in_time' => '06:45:00',
                    'check_out_time' => $checkOut,
                    'active' => true,
                ]);
            }
        }
    }
}
