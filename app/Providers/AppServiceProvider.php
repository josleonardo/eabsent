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
        Gate::define('access-menu', function (User $user, $menuName) {
            if (! $user->active) {
                return false;
            }

            $role = $user->roles->first();
            if (! $role || ! $role->active || ! optional($role->pivot)->active) {
                return false;
            }

            return DB::table('menus as m')
                ->join('role_menu as rm', 'm.id', '=', 'rm.menu_id')
                ->where('rm.role_id', $role->id)
                ->where('rm.active', true)
                ->where('m.active', true)
                ->where('m.platform', 0)
                ->where('m.name', $menuName)
                ->exists();
        });
    }
}
