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
            ['name' => 'superadmin'],
            ['name' => 'admin'],
            ['name' => 'headmaster'],
            ['name' => 'teacher'],
        ];

        foreach ($roles as $role) {
            Role::factory()->create([
                'name' => $role['name'],
                'active' => true,
            ]);
        }
    }
}
