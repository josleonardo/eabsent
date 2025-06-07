<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->first();
        $allowedRoles = in_array($role->id, [1, 2, 3]);

        // If user not exist, inactive, or role not exist, not allowed, or role/role_user inactive
        if (! $user || ! $user->active || ! $role || ! $allowedRoles || ! $role->active || ! $role->pivot->active) {
            return redirect()->route('home.index')->with('error', 'Unauthorized access');
        }

        $menus = Menu::select('id', 'name', 'url')
            ->where([
                ['platform', 0],
                ['menu_group', 2],
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
            ->map(function ($menu, $index) {
                $colors = [
                    'bg-indigo-400 dark:bg-indigo-600',
                    'bg-orange-400 dark:bg-orange-600',
                ];
                $hovers = [
                    'hover:bg-indigo-500',
                    'hover:bg-orange-500',
                ];
                $icons = [
                    'icon-logs',
                    'icon-calendar',
                ];

                // Assign color based on index, cycling if more items than available
                $menu->color = $colors[$index % count($colors)];
                $menu->hover = $hovers[$index % count($hovers)];
                $menu->icon = $icons[$index % count($icons)];

                return $menu;
            });

        return view('reports.index', ['pageName' => 'Report'] + compact('menus'));
    }
}
