<?php

namespace App\Services\Admins;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MenuService
{
    public function getMenus(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return Menu::paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createMenu(array $validatedData, int $currentUserId): Menu
    {
        $menu = Menu::create([
            'menu_group' => $validatedData['menu_group'],
            'name' => $validatedData['menu_name'],
            'url' => $validatedData['url'],
            'platform' => $validatedData['platform'],
            'order' => $validatedData['order'],
            'icon' => $validatedData['icon'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $menu;
    }

    public function updateMenu(Menu $menu, array $validatedData, int $currentUserId): Menu
    {
        $menu->update([
            'menu_group' => $validatedData['menu_group'],
            'name' => $validatedData['menu_name'],
            'url' => $validatedData['url'],
            'platform' => $validatedData['platform'],
            'order' => $validatedData['order'],
            'icon' => $validatedData['icon'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $menu;
    }
}
