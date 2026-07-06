<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()
            ->whereNotNull('schedule_group_id')
            ->get();

        $dates = collect(range(0, 9))->map(function ($i) {
            return now()->subDays($i)->toDateString();
        });

        foreach ($users as $user) {
            foreach ($dates as $date) {
                Attendance::factory()->create([
                    'user_id' => $user->id,
                    'date' => $date,
                ]);
            }
        }
    }
}
