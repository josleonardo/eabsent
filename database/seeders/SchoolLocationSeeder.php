<?php

namespace Database\Seeders;

use App\Models\SchoolLocation;
use Illuminate\Database\Seeder;

class SchoolLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolLocations = [
            [
                'name' => 'main location',
                'key' => 'ml',
                'latitude' => '-6.456011',
                'longitude' => '107.041969',
                'radius' => 50,
            ],
            [
                'name' => 'branch location 1',
                'key' => 'bl1',
                'latitude' => '-6.456015',
                'longitude' => '107.041972',
                'radius' => 100,
            ],
        ];

        foreach ($schoolLocations as $location) {
            SchoolLocation::factory()->create([
                'name' => $location['name'],
                'key' => $location['key'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude'],
                'radius' => $location['radius'],
                'active' => true,
            ]);
        }
    }
}
