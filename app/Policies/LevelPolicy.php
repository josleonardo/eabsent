<?php

namespace App\Policies;

use App\Models\Level;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LevelPolicy
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
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->roles->first()->priority >= 90;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Level $level): bool
    {
        return false;
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
    public function update(User $user, Level $level): bool
    {
        return $user->roles->first()->priority >= 90;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Level $level): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Level $level): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Level $level): bool
    {
        return false;
    }
}
