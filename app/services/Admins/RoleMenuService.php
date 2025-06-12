<?php

namespace App\Services\Admins;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleMenuService
{
    public function createRoleMenu(Role $role, array $validatedData, int $userId)
    {
        if ($role->menus()->where('menu_id', $validatedData['menu'])->exists()) {
            return ['error' => 'This role already has the selected menu.'];
        } else {
            $role->menus()->attach($validatedData['menu'], [
                'active' => $validatedData['active'],
                'created_by' => $userId,
                'updated_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return ['success' => true];
    }

    /**
     * Update a role menu association with the provided validated data and default data.
     */
    public function updateRoleMenu(Role $role, int $menuId, array $validatedData, array $defaultData)
    {
        if (! empty($validatedData['menu']) && $validatedData['menu'] != $menuId) {
            if ($role->menus()->where('menu_id', $validatedData['menu'])->exists()) {
                return ['error' => 'This role already has the selected menu.'];
            }
            DB::table('role_menu')
                ->where('role_id', $role->id)
                ->where('menu_id', $menuId)
                ->update(array_merge([
                    'menu_id' => $validatedData['menu'],
                ], $defaultData));
        } elseif (! empty($validatedData['menu']) && $validatedData['menu'] == $menuId) {
            DB::table('role_menu')
                ->where('role_id', $role->id)
                ->where('menu_id', $menuId)
                ->update($defaultData);
        } else {
            $role->menus()->detach($menuId);
        }

        return ['success' => true];
    }
}
