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
                'name' => 'Absensi',
                'url' => 'attendance',
                'order' => 1,
                'icon' => 'fingerprint_rounded',
            ],
            [
                'name' => 'Riwayat',
                'url' => 'history',
                'order' => 2,
                'icon' => 'list_rounded',
            ],
            [
                'name' => 'Izin',
                'url' => 'leave',
                'order' => 3,
                'icon' => 'flight_takeoff_rounded',
            ],
            [
                'name' => 'Koreksi',
                'url' => 'correction',
                'order' => 4,
                'icon' => 'edit_note_rounded',
            ],
            [
                'name' => 'Kelola Persetujuan',
                'url' => 'manage-approval',
                'order' => 5,
                'icon' => 'task_rounded',
            ],
            [
                'name' => 'Ganti Kata Sandi',
                'url' => 'change-password',
                'order' => 6,
                'icon' => 'lock_reset_rounded',
            ],
        ];

        $webMenus = [
            [
                'menu_group' => 0,
                'name' => 'Home',
                'url' => '/home',
                'order' => 1,
            ],
            [
                'menu_group' => 0,
                'name' => 'Report',
                'url' => '/report',
                'order' => 2,
            ],
            [
                'menu_group' => 0,
                'name' => 'Approval',
                'url' => '/approval',
                'order' => 3,
            ],
            [
                'menu_group' => 0,
                'name' => 'Administration',
                'url' => '/admin',
                'order' => 4,
            ],
            [
                'menu_group' => 2,
                'name' => 'History',
                'url' => '/report/history',
                'order' => 1,
            ],
            [
                'menu_group' => 2,
                'name' => 'Attendance',
                'url' => '/report/attendance',
                'order' => 2,
            ],
            [
                'menu_group' => 3,
                'name' => 'Leave',
                'url' => '/approval/leave',
                'order' => 1,
            ],
            [
                'menu_group' => 3,
                'name' => 'Correction',
                'url' => '/approval/correction',
                'order' => 2,
            ],
            [
                'menu_group' => 6,
                'name' => 'Change Password',
                'url' => '/change-password',
                'order' => 1,
            ],
            [
                'menu_group' => 10,
                'name' => 'User',
                'url' => '/admin/user',
                'order' => 1,
            ],
            [
                'menu_group' => 10,
                'name' => 'Role',
                'url' => '/admin/role',
                'order' => 2,
            ],
            [
                'menu_group' => 10,
                'name' => 'Menu',
                'url' => '/admin/menu',
                'order' => 3,
            ],
            [
                'menu_group' => 10,
                'name' => 'Level',
                'url' => '/admin/level',
                'order' => 4,
            ],
            [
                'menu_group' => 10,
                'name' => 'Schedule',
                'url' => '/admin/schedule',
                'order' => 5,
            ],
            [
                'menu_group' => 10,
                'name' => 'App Setting',
                'url' => '/admin/app-setting',
                'order' => 6,
            ],
            [
                'menu_group' => 10,
                'name' => 'User Role',
                'url' => '/admin/user-role',
                'order' => 7,
            ],
            [
                'menu_group' => 10,
                'name' => 'User Level',
                'url' => '/admin/user-level',
                'order' => 8,
            ],
            [
                'menu_group' => 10,
                'name' => 'User Schedule',
                'url' => '/admin/user-schedule',
                'order' => 9,
            ],
            [
                'menu_group' => 10,
                'name' => 'Role Menu',
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
                'icon' => $androidMenu['icon'],
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
