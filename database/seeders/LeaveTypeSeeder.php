<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'annual', 'requires_description' => false],
            ['name' => 'sick', 'requires_description' => true],
            ['name' => 'personal', 'requires_description' => true],
            ['name' => 'maternity', 'requires_description' => true],
            ['name' => 'bereavement', 'requires_description' => true],
        ];

        foreach ($types as $type) {
            LeaveType::factory()->create([
                'name' => $type['name'],
                'requires_description' => $type['requires_description'],
                'active' => true,
            ]);
        }
    }
}
