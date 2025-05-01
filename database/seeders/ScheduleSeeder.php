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
        $schedules = [
            ['day_of_week' => 1],
            ['day_of_week' => 2],
            ['day_of_week' => 3],
            ['day_of_week' => 4],
            ['day_of_week' => 5],
        ];

        foreach ($schedules as $schedule) {
            Schedule::factory()->create([
                'name' => 'default',
                'day_of_week' => $schedule['day_of_week'],
                'check_in_time' => '06:45:00',
                'check_out_time' => '15:00:00',
                'active' => true,
            ]);
        }
    }
}
