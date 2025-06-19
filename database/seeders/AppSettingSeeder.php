<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $appSettings = [
            [
                'name' => 'absentpoint1',
                'key' => 'koordinat',
                'value_1' => '-6.456011',
                'value_2' => '107.041969',
            ],
            [
                'name' => 'absentradius1',
                'key' => 'radius',
                'value_1' => '100',
                'value_2' => 'meter',
            ],
        ];

        foreach ($appSettings as $setting) {
            AppSetting::factory()->create([
                'name' => $setting['name'],
                'key' => $setting['key'],
                'value_1' => $setting['value_1'],
                'value_2' => $setting['value_2'],
                'active' => true,
            ]);
        }
    }
}
