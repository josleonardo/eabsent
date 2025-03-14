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
            ['role_name' => 'Superadmin'],
            ['role_name' => 'Administrator'],
            ['role_name' => 'Headmaster'],
            ['role_name' => 'Teacher'],
        ];

        foreach ($roles as $role) {
            Role::factory()->create([
                'role_name' => $role['role_name'],
                'active' => true,
            ]);
        }
    }
}
