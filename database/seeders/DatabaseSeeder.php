<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            RoleSeeder::class,
            LevelSeeder::class,
            RoleMenuSeeder::class,
            SchoolLocationSeeder::class,
            ScheduleSeeder::class,
            ScheduleGroupSeeder::class,
            UserSeeder::class,
            AttendanceSeeder::class,
            LeaveSeeder::class,
            CorrectionSeeder::class,
        ]);
    }
}
