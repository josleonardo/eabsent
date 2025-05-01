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
            ['name' => 'Superadmin'],
            ['name' => 'Administrator'],
            ['name' => 'Headmaster'],
            ['name' => 'Teacher'],
        ];

        foreach ($roles as $role) {
            Role::factory()->create([
                'name' => $role['name'],
                'active' => true,
            ]);
        }
    }
}
