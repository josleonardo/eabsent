<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userCreds = [
            [
                'username' => 'admin',
                'email' => 'admin@school.com',
                'password' => Hash::make('Asdf.123'),
                'nik' => null,
                'first_name' => 'Admin',
                'last_name' => null,
                'school_location_id' => 2,
                'schedule_group_id' => 2,
                'position' => 'Admin',
                'role_id' => 2,
                'level_id' => 1,
            ],
            [
                'username' => 'kepsek1',
                'email' => 'kepsek1@school.com',
                'password' => Hash::make('password'),
                'first_name' => 'Kepsek',
                'last_name' => 'SD',
                'school_location_id' => 1,
                'schedule_group_id' => 1,
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 3,
            ],
            [
                'username' => 'kepsek2',
                'email' => 'kepsek2@school.com',
                'password' => Hash::make('password'),
                'first_name' => 'Kepsek',
                'last_name' => 'SMP',
                'school_location_id' => 1,
                'schedule_group_id' => 1,
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 4,
            ],
            [
                'username' => 'kepsek3',
                'email' => 'kepsek3@school.com',
                'password' => Hash::make('password'),
                'first_name' => 'Kepsek',
                'last_name' => 'SMA',
                'school_location_id' => 2,
                'schedule_group_id' => 2,
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 5,
            ],
            [
                'username' => 'guru1',
                'email' => 'guru1@school.com',
                'password' => Hash::make('password'),
                'first_name' => 'Guru',
                'last_name' => 'SD',
                'school_location_id' => 1,
                'schedule_group_id' => 1,
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 3,
            ],
            [
                'username' => 'guru2',
                'email' => 'guru2@school.com',
                'password' => Hash::make('password'),
                'first_name' => 'Guru',
                'last_name' => 'SMP',
                'school_location_id' => 1,
                'schedule_group_id' => 1,
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 4,
            ],
            [
                'username' => 'guru3',
                'email' => 'guru3@school.com',
                'password' => Hash::make('password'),
                'first_name' => 'Guru 3',
                'last_name' => 'SMA',
                'school_location_id' => 2,
                'schedule_group_id' => 2,
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 5,
            ],
        ];

        DB::beginTransaction();

        try {
            foreach ($userCreds as $userCred) {
                $user = User::factory()->make([
                    'username' => $userCred['username'],
                    'email' => $userCred['email'],
                    'password' => $userCred['password'],
                    'school_location_id' => $userCred['school_location_id'],
                    'schedule_group_id' => $userCred['schedule_group_id'],
                    'active' => true,
                ]);
                $user->save();

                $profile = [
                    'user_id' => $user->id,
                    'nuptk' => null,
                    'first_name' => $userCred['first_name'],
                    'last_name' => $userCred['last_name'],
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
                ];

                if (isset($userCred['nik'])) {
                    $profile['nik'] = $userCred['nik'];
                }

                UserProfile::factory()->create($profile);

                $role = Role::where('id', $userCred['role_id'])->first();
                if ($role) {
                    $user->roles()->attach($role->id, [
                        'active' => true,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                }

                $level = Level::where('id', $userCred['level_id'])->first();
                if ($level) {
                    $user->levels()->attach($level->id, [
                        'active' => true,
                        'created_by' => 1,
                        'updated_by' => 1,
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Error seeding users: '.$th->getMessage());
        }

        // User::factory()->count(50)->create();
    }
}
