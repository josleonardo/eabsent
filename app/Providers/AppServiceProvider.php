<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-menu', function (User $user, string $menuName) {
            if (! $user || ! $user->active) {
                return false;
            }

            $role = $user->roles->first();
            if (! $role || ! $role->active || ! optional($role->pivot)->active) {
                return false;
            }

            $menu = DB::table('menus as m')
                ->join('role_menu as rm', 'm.id', '=', 'rm.menu_id')
                ->where([
                    ['rm.role_id', $role->id],
                    ['rm.active', true],
                    ['m.active', true],
                    ['m.platform', 0],
                    ['m.name', $menuName],
                ])
                ->exists();

            return $menu;
        });
    }
}
