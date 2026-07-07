<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $user = User::factory()->make([
                'username' => 'super_admin',
                'email' => 'superadmin@school.com',
                'password' => Hash::make('Asdf.123'),
                'school_location_id' => null,
                'schedule_group_id' => null,
                'active' => true,
                'created_by' => null,
                'updated_by' => null,
            ]);

            $user->save();

            UserProfile::factory()->create([
                'user_id' => $user->id,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'nik' => 1111111111111111,
                'nuptk' => null,
                'address' => null,
                'phone_number' => null,
                'position' => 'Super Admin',
                'employment_start' => null,
                'employment_end' => null,
                'active' => $user->active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Error seeding super admin: ' . $th->getMessage());
        }
    }
}
