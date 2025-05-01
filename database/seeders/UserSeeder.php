<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'position' => 'Super Admin',
                'role_id' => 1,
                'level_id' => 1,
                'name' => 'default',
            ],
            [
                'username' => 'kepsek1',
                'email' => 'kepsek1@sekolah.com',
                'password' => Hash::make('password'),
                'first_name' => 'Kepsek',
                'last_name' => 'SD',
                'position' => 'Kepala Sekolah',
                'role_id' => 2,
                'level_id' => 3,
                'name' => 'default',
            ],
            [
                'username' => 'kepsek2',
                'email' => 'kepsek2@sekolah.com',
                'password' => Hash::make('password'),
                'first_name' => 'Kepsek',
                'last_name' => 'SMP',
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 4,
                'name' => 'default',
            ],
            [
                'username' => 'kepsek3',
                'email' => 'kepsek3@sekolah.com',
                'password' => Hash::make('password'),
                'first_name' => 'Kepsek',
                'last_name' => 'SMA',
                'position' => 'Kepala Sekolah',
                'role_id' => 3,
                'level_id' => 5,
                'name' => 'default',
            ],
            [
                'username' => 'guru1',
                'email' => 'guru1@sekolah.com',
                'password' => Hash::make('password'),
                'first_name' => 'Guru',
                'last_name' => 'SD',
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 3,
                'name' => 'default',
            ],
            [
                'username' => 'guru2',
                'email' => 'guru2@sekolah.com',
                'password' => Hash::make('password'),
                'first_name' => 'Guru',
                'last_name' => 'SMP',
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 4,
                'name' => 'default',
            ],
            [
                'username' => 'guru3',
                'email' => 'guru3@sekolah.com',
                'password' => Hash::make('password'),
                'first_name' => 'Guru 3',
                'last_name' => 'SMA',
                'position' => 'Guru',
                'role_id' => 4,
                'level_id' => 5,
                'name' => 'default',
            ],
        ];

        DB::beginTransaction();

        try {
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
                $schedules = Schedule::where('name', $userCred['name'])->get();
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

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            
            throw $th; 
        }
        

        // User::factory()->count(50)->create();
    }
}
