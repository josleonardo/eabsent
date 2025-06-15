<?php

namespace Database\Seeders;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $leaveDates = [];
            for ($i = 0; $i < 3; $i++) {
                do {
                    $start = fake()->dateTimeBetween('-10 days', 'now');
                    $end = fake()->dateTimeBetween($start, '+5 days');
                    $overlap = false;
                    foreach ($leaveDates as [$s, $e]) {
                        if ($start <= $e && $end >= $s) {
                            $overlap = true;
                            break;
                        }
                    }
                } while ($overlap);

                $leaveDates[] = [$start, $end];

                Leave::factory()->create([
                    'start_date' => $start->format('Y-m-d'),
                    'end_date' => $end->format('Y-m-d'),
                    'created_at' => $start,
                    'created_by' => $user->id,
                ]);
            }
        }
    }
}
