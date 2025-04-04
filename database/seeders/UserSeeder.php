<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCreds = [
            [
                'username' => 'super_admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('Asdf.123'),
                'fullname' => 'Super Administrator',
                'position' => 'Super Admin',
                'role_id' => 1,
                'level_id' => 1,
                'schedule_name' => 'default',
            ],
            [
                'username' => 'kepsek1',
                'email' => 'kepsek1@sekolah.com',
                'password' => Hash::make('password'),
                'fullname' => 'Kepsek 1',
                'position' => 'Kepala Sekolah',
                'role_id' => 2,
                'level_id' => 4,
                'schedule_name' => 'default',
            ],
            [
                'username' => 'kepsek2',
                'email' => 'kepsek2@sekolah.com',
                'password' => Hash::make('password'),
                'fullname' => 'Kepsek 2',
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 5,
                'schedule_name' => 'default',
            ],
            [
                'username' => 'kepsek3',
                'email' => 'kepsek3@sekolah.com',
                'password' => Hash::make('password'),
                'fullname' => 'Kepsek 3',
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 6,
                'schedule_name' => 'default',
            ],
            [
                'username' => 'guru1',
                'email' => 'guru1@sekolah.com',
                'password' => Hash::make('password'),
                'fullname' => 'Guru 1',
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 5,
                'schedule_name' => 'default',
            ],
            [
                'username' => 'guru2',
                'email' => 'guru2@sekolah.com',
                'password' => Hash::make('password'),
                'fullname' => 'Guru 2',
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 5,
                'schedule_name' => 'default',
            ],
            [
                'username' => 'guru3',
                'email' => 'guru3@sekolah.com',
                'password' => Hash::make('password'),
                'fullname' => 'Guru 3',
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 6,
                'schedule_name' => 'default',
            ],
        ];

        foreach ($userCreds as $userCred) {
            // Create user
            $user = User::factory()->make([
                'username' => $userCred['username'],
                'email' => $userCred['email'],
                'password' => $userCred['password'],
                'active' => true,
            ]);
            $user->save();

            // Create user profile
            UserProfile::factory()->create([
                'user_id' => $user->id,
                'nuptk' => null,
                'fullname' => $userCred['fullname'],
                'position' => $userCred['position'],
                'address' => null,
                'phone_number' => null,
                'employment_start' => now(),
                'employment_end' => null,
                'active' => $user->active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            // Attach role
            $role = Role::where('id', $userCred['role_id'])->first();
            if ($role) {
                $user->role()->attach($role->id, [
                    'active' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }

            // Attach level
            $level = Level::where('id', $userCred['level_id'])->first();
            if ($level) {
                $user->levels()->attach($level->id, [
                    'active' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }

            // Attach schedules
            $schedules = Schedule::where('schedule_name', $userCred['schedule_name'])->get();
            if ($schedules) {
                foreach ($schedules as $schedule) {
                    $user->schedules()->attach($schedule->id, [
                        'active' => true,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                }
            }
        }

        // User::factory()->count(50)->create();
    }
}
