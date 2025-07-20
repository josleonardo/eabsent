<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRoleService
{
    public function getUsersRoles(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin) {
            return User::select('id', 'email', 'username')->with('roles:id,name')->paginate($perPage);
        }

        if ($userRole == $admin) {
            return User::select('id', 'email', 'username')
                ->whereHas('roles', function ($query) use ($superAdmin) {
                    $query->whereNot('name', $superAdmin);
                })
                ->with('roles:id,name')
                ->paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function updateUserRole(array $validatedData, User $user, int $currentUserId)
    {
        if (! empty($validatedData['role'])) {
            $user->roles()->syncWithPivotValues(
                [$validatedData['role']],
                [
                    'active' => $validatedData['active'],
                    'created_by' => $currentUserId,
                    'updated_by' => $currentUserId,
                ]
            );
        } else {
            $user->roles()->detach();
        }
    }
}
