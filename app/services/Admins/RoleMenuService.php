<?php

namespace App\Services\Admins;

use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoleMenuService
{
    public function getRolesMenus(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        $query = DB::table('role_menu as rm')
            ->join('roles as r', 'rm.role_id', '=', 'r.id')
            ->join('menus as m', 'rm.menu_id', '=', 'm.id')
            ->select(
                'r.id as role_id',
                'r.name as role_name',
                'm.id as menu_id',
                'm.name as menu_name',
                'm.platform',
                'rm.active',
                'rm.created_at',
                'rm.created_by',
                'rm.updated_at',
                'rm.updated_by'
            )
            ->orderBy('role_id');

        if ($userRole == $superAdmin) {
            return $query->paginate($perPage);
        }

        if ($userRole == $admin) {
            return $query->whereNot('role_name', $superAdmin)->paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

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
    public function updateRoleMenu(Role $role, int $menuId, array $validatedData)
    {
        $defaultData = [
            'active' => $validatedData['active'],
            'updated_by' => request()->user()->id,
            'updated_at' => now(),
        ];

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
