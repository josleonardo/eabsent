<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RoleService
{
    public function getRoles(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $userRole = $user->roles->first()->name;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin) {
            return Role::paginate($perPage);
        }

        if ($userRole == $admin) {
            return Role::whereNot('name', $superAdmin)
                ->orderBy('id')
                ->paginate($perPage);
        }

        abort(403, 'Unauthorized');
    }

    public function createRole(array $validatedData, int $currentUserId): Role
    {
        $role = Role::create([
            'name' => $validatedData['role_name'],
            'priority' => $validatedData['priority'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $role;
    }

    public function updateRole(Role $role, array $validatedData, int $currentUserId): Role
    {
        $role->update([
            'name' => $validatedData['role_name'],
            'priority' => $validatedData['priority'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $role;
    }
}
