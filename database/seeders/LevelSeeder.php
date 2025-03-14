<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['level_name' => 'Super Admin'],
            ['level_name' => 'KB'],
            ['level_name' => 'TK'],
            ['level_name' => 'SD'],
            ['level_name' => 'SMP'],
            ['level_name' => 'SMA'],
        ];

        foreach ($levels as $level) {
            Level::factory()->create([
                'level_name' => $level['level_name'],
                'active' => true,
            ]);
        }
    }
}
