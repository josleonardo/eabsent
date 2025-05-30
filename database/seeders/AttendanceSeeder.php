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
        $users = User::all();

        foreach ($users as $user) {
            $dates = collect(range(0, 9))->map(function ($i) {
                return now()->subDays($i)->toDateString();
            });

            foreach ($dates as $date) {
                Attendance::factory()->create([
                    'user_id' => $user->id,
                    'date' => $date,
                ]);
            }
        }
    }
}
