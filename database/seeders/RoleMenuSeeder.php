<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = Menu::all(); // Get all menus

        // Define menu access for each role
        $roleMenus = [
            1 => $menus->pluck('id')->toArray(), // Superadmin gets all menus
            2 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 19, 24, 25],
            3 => [1, 2, 5, 6, 7, 8, 9, 11, 12, 13, 14, 25],
            4 => [1, 2, 3, 4, 6],
        ];

        foreach ($roleMenus as $roleId => $menuIds) {
            $role = Role::where('id', $roleId)->first();
            if ($role) {
                foreach ($menuIds as $menuId) {
                    $role->menus()->attach($menuId, [
                        'active' => true,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                }
            }
        }
    }
}
