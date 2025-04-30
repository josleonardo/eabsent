<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role->first();
        $allowedRoles = in_array($role->id, [1, 2]);

        // If user not exist, inactive, or role not exist, not allowed, or role/role_user inactive
        if (!$user || !$user->active || !$role || !$allowedRoles || !$role->active || !$role->pivot->active) {
            return redirect()->route('home.index')->with('error', 'Unauthorized access');
        }

        $menus = Menu::select('id', 'menu_name', 'menu_url')
            ->where([
                ['type', 'web'],
                ['main_menu_id', 10],
                ['menus.active', 1], 
            ])
            ->whereHas('roles', function ($query) use ($role) {
                $query->where([
                    ['role_id', $role->id],
                    ['role_menu.active', 1],
                ]);
            })
            ->orderBy('order')
            ->get();

        return view('administrators.admin', ['pageName' => 'Admin'] + compact('menus'));
        
    }
}
