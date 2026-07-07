<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $user = User::where('username', 'super_admin')->first();
            $user->update([
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            $role = Role::where('name', 'superadmin')->first();
            if ($user && $role) {
                $user->roles()->attach($role->id, [
                    'active' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }

            $level = Level::where('name', 'admin')->first();
            if ($user && $level) {
                $user->levels()->attach($level->id, [
                    'active' => true,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            Log::error('Error updating super admin: ' . $th->getMessage());
        }
    }
}
