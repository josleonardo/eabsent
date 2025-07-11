<?php

namespace App\Http\Controllers\Approvals;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->roles->first();

        $menus = Menu::select('id', 'name', 'url')
            ->where([
                ['platform', 0],
                ['menu_group', 3],
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
                    'bg-green-400 dark:bg-green-600',
                    'bg-blue-400 dark:bg-blue-600',
                ];
                $hovers = [
                    'hover:bg-green-500',
                    'hover:bg-blue-500',
                ];
                $icons = [
                    'icon-plane',
                    'icon-calendar-cog',
                ];

                // Assign color based on index, cycling if more items than available
                $menu->color = $colors[$index % count($colors)];
                $menu->hover = $hovers[$index % count($hovers)];
                $menu->icon = $icons[$index % count($icons)];

                return $menu;
            });

        return view('approvals.index', ['pageName' => 'Approval'] + compact('menus'));
    }
}
