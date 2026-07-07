<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $androidMenus = [
            [
                'name' => 'attendance',
                'url' => 'attendance',
                'order' => 1,
            ],
            [
                'name' => 'history',
                'url' => 'history',
                'order' => 2,
            ],
            [
                'name' => 'leave',
                'url' => 'leave',
                'order' => 3,
            ],
            [
                'name' => 'correction',
                'url' => 'correction',
                'order' => 4,
            ],
            [
                'name' => 'manage approval',
                'url' => 'manage-approval',
                'order' => 5,
            ],
            [
                'name' => 'change password',
                'url' => 'change-password',
                'order' => 6,
            ],
        ];

        $webMenus = [
            [
                'menu_group' => 0,
                'name' => 'home',
                'url' => '/home',
                'order' => 1,
            ],
            [
                'menu_group' => 0,
                'name' => 'report',
                'url' => '/report',
                'order' => 2,
            ],
            [
                'menu_group' => 0,
                'name' => 'approval',
                'url' => '/approval',
                'order' => 3,
            ],
            [
                'menu_group' => 0,
                'name' => 'administration',
                'url' => '/admin',
                'order' => 4,
            ],
            [
                'menu_group' => 2,
                'name' => 'activity logs',
                'url' => '/report/activity-logs',
                'order' => 1,
            ],
            [
                'menu_group' => 2,
                'name' => 'attendance',
                'url' => '/report/attendance',
                'order' => 2,
            ],
            [
                'menu_group' => 3,
                'name' => 'leave',
                'url' => '/approval/leave',
                'order' => 1,
            ],
            [
                'menu_group' => 3,
                'name' => 'correction',
                'url' => '/approval/correction',
                'order' => 2,
            ],
            [
                'menu_group' => 6,
                'name' => 'change password',
                'url' => '/settings/account/password',
                'order' => 1,
            ],
            [
                'menu_group' => 10,
                'name' => 'user',
                'url' => '/admin/user',
                'order' => 1,
            ],
            [
                'menu_group' => 10,
                'name' => 'role',
                'url' => '/admin/role',
                'order' => 2,
            ],
            [
                'menu_group' => 10,
                'name' => 'menu',
                'url' => '/admin/menu',
                'order' => 3,
            ],
            [
                'menu_group' => 10,
                'name' => 'level',
                'url' => '/admin/level',
                'order' => 4,
            ],
            [
                'menu_group' => 10,
                'name' => 'schedule',
                'url' => '/admin/schedule',
                'order' => 5,
            ],
            [
                'menu_group' => 10,
                'name' => 'school location',
                'url' => '/admin/school-location',
                'order' => 6,
            ],
            [
                'menu_group' => 10,
                'name' => 'user role',
                'url' => '/admin/user-role',
                'order' => 7,
            ],
            [
                'menu_group' => 10,
                'name' => 'user level',
                'url' => '/admin/user-level',
                'order' => 8,
            ],
            [
                'menu_group' => 10,
                'name' => 'schedule group',
                'url' => '/admin/schedule-group',
                'order' => 9,
            ],
            [
                'menu_group' => 10,
                'name' => 'role menu',
                'url' => '/admin/role-menu',
                'order' => 10,
            ],
        ];

        foreach ($androidMenus as $androidMenu) {
            Menu::factory()->create([
                'menu_group' => 0,
                'name' => $androidMenu['name'],
                'url' => $androidMenu['url'],
                'platform' => 1,
                'order' => $androidMenu['order'],
                'icon' => null,
                'active' => true,
            ]);
        }

        foreach ($webMenus as $webMenu) {
            Menu::factory()->create([
                'menu_group' => $webMenu['menu_group'],
                'name' => $webMenu['name'],
                'url' => $webMenu['url'],
                'platform' => 0,
                'order' => $webMenu['order'],
                'icon' => null,
                'active' => true,
            ]);
        }
    }
}
