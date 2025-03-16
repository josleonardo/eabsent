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
            if (!$user) {
                $view->with('menuItems', collect());
                return;
            }

            $userRole = $user->role()->wherePivot('active', 1)->first();
            if (!$userRole) {
                $view->with('menuItems', collect());
                return;
            }

            $menuItems = Menu::whereHas('roles', function ($query) use ($userRole) {
                $query->where([
                    ['type', 'web'],
                    ['main_menu_id', 0],
                    ['role_id', $userRole->id],
                    ['role_menu.active', 1],
                    ['menus.active', 1],
                ]);
            })->orderBy('order')->get();

            $view->with('menuItems', $menuItems);
        });
    }
}
