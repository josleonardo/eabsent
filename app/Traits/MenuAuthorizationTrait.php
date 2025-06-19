<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait MenuAuthorizationTrait
{
    /**
     * Checking if the current user have authorized menu access
     */
    public function checkMenuAuthorization(string $menuName): bool
    {
        $user = Auth::user();
        if (! $user || ! $user->active) {
            return false;
        }

        $role = $user->roles->first();
        if (! $role || ! $role->active || ! optional($role->pivot)->active) {
            return false;
        }

        $menu = $role->menus->where('platform', 0)->where('name', $menuName)->first();
        if (! $menu || ! $menu->active || ! optional($menu->pivot)->active) {
            return false;
        }

        return true;
    }
}
