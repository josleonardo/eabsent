<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('components.sidebar', function ($view) {
            $user = Auth::user();
            $role = $user->roles->first();
            $allowedRoles = in_array($role->id, [1, 2, 3]);

            // If user not exist, inactive, or role not exist, not allowed, or role/role_user inactive
            if (! $user || ! $user->active || ! $role || ! $allowedRoles || ! $role->active || ! $role->pivot->active) {
                $view->with('sideMenus', collect());

                return;
            }

            $sideMenus = Menu::select('id', 'name', 'url')
                ->where([
                    ['platform', 0],
                    ['menu_id', 0],
                    ['menus.active', 1],
                ])
                ->whereHas('roles', function ($query) use ($role) {
                    $query->where([
                        ['role_id', $role->id],
                        ['role_menu.active', 1],
                    ]);
                })
                ->orderBy('order')
                ->get()
                ->map(function ($sideMenu, $index) {
                    $icons = [
                        'icon-home',
                        'icon-file-text',
                        'icon-clipboard-check',
                        'icon-category',
                    ];

                    // Assign color based on index, cycling if more items than available
                    $sideMenu->icon = $icons[$index % count($icons)];

                    return $sideMenu;
                });

            $view->with('sideMenus', $sideMenus);
        });
    }
}
