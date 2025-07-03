<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
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
            $currentRoute = Route::currentRouteName();

            if (str_starts_with($currentRoute, 'settings.')) {
                $menus = [
                    ['name' => 'Profile', 'url' => route('settings.profile'), 'icon' => 'icon-user'],
                    ['name' => 'Account', 'url' => route('settings.account'), 'icon' => 'icon-settings-2'],
                ];
            } else {
                $user = Auth::user();
                $role = $user?->roles->first();

                $isValid =
                    $user &&
                    $user->active &&
                    $role &&
                    $role->active &&
                    optional($role->pivot)->active &&
                    in_array($role->name, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN, Role::ROLE_HEADMASTER]);

                if (! $isValid) {
                    $view->with('menus', collect());

                    return;
                }

                $menus = DB::table('menus as m')
                    ->join('role_menu as rm', 'm.id', '=', 'rm.menu_id')
                    ->where([
                        ['rm.role_id', $role->id],
                        ['rm.active', true],
                        ['m.active', true],
                        ['m.platform', 0],
                        ['m.menu_group', 0],
                    ])
                    ->select('m.id', 'm.name', 'm.url')
                    ->orderBy('m.order')
                    ->get()
                    ->map(function ($menu, $index) {
                        $icons = [
                            'icon-home',
                            'icon-file-text',
                            'icon-clipboard-check',
                            'icon-category',
                        ];

                        $menu->icon = $icons[$index % count($icons)];

                        return [
                            'name' => $menu->name,
                            'url' => $menu->url,
                            'icon' => $menu->icon,
                        ];
                    });
            }

            $view->with('menus', $menus);
        });
    }
}
