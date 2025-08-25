<?php

namespace App\Services\Admins;

use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LevelService
{
    public function getLevels(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $userRole = $user->roles->first()->name;
        $perPage = $perPage ?? config('constants.default_per_page');

        if (in_array($userRole, [$superAdmin, $admin])) {
            return Level::paginate($perPage);
        }

        abort(403, 'Unauthorized');
    }

    public function createLevel(array $validatedData, int $currentUserId): Level
    {
        $level = Level::create([
            'name' => $validatedData['level_name'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $level;
    }

    public function updateLevel(Level $level, array $validatedData, int $currentUserId): Level
    {
        $level->update([
            'name' => $validatedData['level_name'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $level;
    }
}
