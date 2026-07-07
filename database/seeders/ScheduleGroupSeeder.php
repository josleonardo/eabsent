<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\ScheduleGroup;
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

        $schedules = Schedule::query()->where('active', true)->orderBy('id')->get();
        $chunks = $schedules->chunk(5);

        foreach ($scheduleGroups as $index => $groupData) {
            $group = ScheduleGroup::factory()->create([
                'name' => $groupData['name'],
                'active' => true,
            ]);

            $groupSchedules = $chunks[$index];

            $group->schedules()->syncWithoutDetaching(
                $groupSchedules->pluck('id')->mapWithKeys(fn ($id) => [
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
