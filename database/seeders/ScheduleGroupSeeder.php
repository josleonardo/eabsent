<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Database\Factories\ScheduleGroupFactory;
use Illuminate\Database\Seeder;

class ScheduleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scheduleGroups = [
            ['name' => 'Standard 15:00',],
            ['name' => 'Standard 16:00',],
        ];

        $schedules = Schedule::query()->where('active', true)->get();

        foreach ($scheduleGroups as $groupData) {
            $group = ScheduleGroupFactory::factory()->create([
                'name' => $groupData['name'],
                'active' => true,
            ]);

            $group->schedules()->syncWithoutDetaching(
                $schedules->pluck('id')->mapWithKeys(fn ($id) => [
                    $id => [
                        'active' => true,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ],
                ])->all()
            );
        }
    }
}
