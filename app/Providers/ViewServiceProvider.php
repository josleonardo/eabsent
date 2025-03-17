<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            $allowedRoles = [1, 2, 3];
            $userRoleActive = $user->roleActive($allowedRoles);

            // If user not exist or user or role_user or role not active
            if (!$user || !$user->active || !$userRoleActive) {
                $view->with('menuItems', collect());
                return;
            }

            $menuItems = Menu::whereHas('roles', function ($query) use ($userRoleActive) {
                $query->where([
                    ['type', 'web'],
                    ['main_menu_id', 0],
                    ['role_id', $userRoleActive->id],
                    ['role_menu.active', 1],
                    ['menus.active', 1],
                ]);
            })->orderBy('order')->get();

            $view->with('menuItems', $menuItems);
        });
    }
}
