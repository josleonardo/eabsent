<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'setting_name' => 'AbsenTitik1',
                'key' =>  'koordinat',
                'value_1' => '-6.456011',
                'value_2' => '107.041969',
            ],
            [
                'setting_name' => 'RadiusTitikAbse',
                'key' => 'radius',
                'value_1' => '100',
                'value_2' => 'meter',
            ]
        ];

        foreach ($settings as $setting) {
            Setting::factory()->create([
                'setting_name' => $setting['setting_name'],
                'key' =>  $setting['key'],
                'value_1' => $setting['value_1'],
                'value_2' => $setting['value_2'],
                'active' => true,
            ]);
        }
    }
}
