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
                'menu_name' => 'Absensi',
                'menu_url' => 'attendance',
                'order' => 1,
                'icon' => 'fingerprint_rounded',
            ],
            [
                'menu_name' => 'Riwayat',
                'menu_url' => 'history',
                'order' => 2,
                'icon' => 'list_rounded',
            ],
            [
                'menu_name' => 'Izin',
                'menu_url' => 'leave',
                'order' => 3,
                'icon' => 'flight_takeoff_rounded',
            ],
            [
                'menu_name' => 'Koreksi',
                'menu_url' => 'correction',
                'order' => 4,
                'icon' => 'edit_note_rounded',
            ],
            [
                'menu_name' => 'Kelola Persetujuan',
                'menu_url' => 'manage-approval',
                'order' => 5,
                'icon' => 'task_rounded',
            ],
            [
                'menu_name' => 'Ganti Kata Sandi',
                'menu_url' => 'change-password',
                'order' => 6,
                'icon' => 'lock_reset_rounded',
            ],
        ];

        foreach ($androidMenus as $androidMenu) {
            Menu::factory()->create([
                'menu_name' => $androidMenu['menu_name'],
                'menu_url' => $androidMenu['menu_url'],
                'type' => 1,
                'main_menu_id' => 0,
                'order' => $androidMenu['order'],
                'icon' => $androidMenu['icon'],
                'active' => true
            ]);
        }

        $webMenus = [
            [
                'menu_name' => 'Beranda',
                'menu_url' => '/home',
                'main_menu_id' => 0,
                'order' => 1,
            ],
            [
                'menu_name' => 'Laporan',
                'menu_url' => '/reports',
                'main_menu_id' => 0,
                'order' => 2,
            ],
            [
                'menu_name' => 'Persetujuan',
                'menu_url' => '/approvals',
                'main_menu_id' => 0,
                'order' => 3,
            ],
            [
                'menu_name' => 'Administrasi',
                'menu_url' => '/admin',
                'main_menu_id' => 0,
                'order' => 4,
            ],
            [
                'menu_name' => 'Riwayat',
                'menu_url' => '/reports/history',
                'main_menu_id' => 2,
                'order' => 1,
            ],
            [
                'menu_name' => 'Absensi',
                'menu_url' => '/reports/attendance',
                'main_menu_id' => 2,
                'order' => 2,
            ],
            [
                'menu_name' => 'Izin',
                'menu_url' => '/approvals/leaves',
                'main_menu_id' => 8,
                'order' => 1,
            ],
            [
                'menu_name' => 'Koreksi',
                'menu_url' => '/approvals/corrections',
                'main_menu_id' => 8,
                'order' => 2,
            ],
            [
                'menu_name' => 'User',
                'menu_url' => '/admin/user',
                'main_menu_id' => 10,
                'order' => 1,
            ],
            [
                'menu_name' => 'Role',
                'menu_url' => '/admin/role',
                'main_menu_id' => 10,
                'order' => 2,
            ],
            [
                'menu_name' => 'Menu',
                'menu_url' => '/admin/menu',
                'main_menu_id' => 10,
                'order' => 3,
            ],
            [
                'menu_name' => 'Level',
                'menu_url' => '/admin/level',
                'main_menu_id' => 10,
                'order' => 4,
            ],
            [
                'menu_name' => 'Schedule',
                'menu_url' => '/admin/schedule',
                'main_menu_id' => 10,
                'order' => 5,
            ],
            [
                'menu_name' => 'Settings',
                'menu_url' => '/admin/setting',
                'main_menu_id' => 10,
                'order' => 6,
            ],
            [
                'menu_name' => 'Role User',
                'menu_url' => '/admin/role-user',
                'main_menu_id' => 10,
                'order' => 7,
            ],
            [
                'menu_name' => 'Role Menu',
                'menu_url' => '/admin/role-menu',
                'main_menu_id' => 10,
                'order' => 8,
            ],
            [
                'menu_name' => 'Level User',
                'menu_url' => '/admin/level-user',
                'main_menu_id' => 10,
                'order' => 9,
            ],
            [
                'menu_name' => 'Schedule User',
                'menu_url' => '/admin/schedule-user',
                'main_menu_id' => 10,
                'order' => 10,
            ],
            [
                'menu_name' => 'Change Password',
                'menu_url' => '/admin/change-password',
                'main_menu_id' => 10,
                'order' => 11,
            ],
        ];

        foreach ($webMenus as $webMenu) {
            Menu::factory()->create([
                'menu_name' => $webMenu['menu_name'],
                'menu_url' => $webMenu['menu_url'],
                'type' => 0,
                'main_menu_id' => $webMenu['main_menu_id'],
                'order' => $webMenu['order'],
                'icon' => null,
                'active' => true
            ]);
        }
    }
}
