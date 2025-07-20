<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'priority' => 100],
            ['name' => 'admin', 'priority' => 90],
            ['name' => 'headmaster', 'priority' => 20],
            ['name' => 'teacher', 'priority' => 10],
        ];

        foreach ($roles as $role) {
            Role::factory()->create([
                'name' => $role['name'],
                'priority' => $role['priority'],
                'active' => true,
            ]);
        }
    }
}
