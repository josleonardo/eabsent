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
            ['name' => 'Admin'],
            ['name' => 'K'],
            ['name' => 'Elem'],
            ['name' => 'JHS'],
            ['name' => 'SHS'],
        ];

        foreach ($levels as $level) {
            Level::factory()->create([
                'name' => $level['name'],
                'active' => true,
            ]);
        }
    }
}
