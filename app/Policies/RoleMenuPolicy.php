<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RoleMenuPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user): ?bool
    {
        // Allow all actions for super admins
        if ($user->roles->first()->priority == 100) {
            return true;
        }

        return null;
    }

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->roles->first()->priority >= 90;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->roles->first()->priority >= 90;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->roles->first()->priority >= $role->priority;
    }
}
