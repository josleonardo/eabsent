<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreRoleMenuRequest;
use App\Http\Requests\Admins\UpdateRoleMenuRequest;
use App\Models\Menu;
use App\Models\Role;
use App\Services\Admins\RoleMenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, RoleMenuService $roleMenuService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('viewAny', Role::class)) {
            abort(403);
        }
        
        $roleMenus = $roleMenuService->getRolesMenus($currentUser);

        $platforms = config('constants.platforms');
        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.role-menu.index', ['pageName' => 'Role Menu'] + compact('roleMenus', 'platforms', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'name')
            ->where('active', 1)
            ->get();

        $menus = Menu::select('id', 'name')
            ->where('active', 1)
            ->get();

        $platforms = collect(config('constants.platforms'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();

        $activeKey = config('constants.actives');

        return view('administrators.role-menu.create', ['pageName' => 'Add Role Menu'] + compact('roles', 'menus', 'platforms', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleMenuRequest $request, RoleMenuService $roleMenuService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('create', Role::class)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $role = Role::findOrFail($validatedData['role']);
            $currentUserId = request()->user()->id;

            $result = $roleMenuService->createRoleMenu($role, $validatedData, $currentUserId);

            if (isset($result['error'])) {
                return back()->withErrors(['menu' => $result['error']]);
            }

            return redirect()->route('role-menu.index')->with('success', 'Role menu created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating role menu: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the role menu.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $roleId, string $menuId)
    {
        $role = Role::select('id', 'name')->findOrFail($roleId);
        $currMenu = Menu::select('id', 'name', 'platform')->findOrFail($menuId);
        $menus = Menu::select('id', 'name', 'platform')
            ->where('platform', $currMenu->platform)
            ->where('active', 1)
            ->get();
        $pivotData = $role->menus->where('id', $currMenu->id)->first()->pivot;

        $platforms = config('constants.platforms');
        $activeKey = config('constants.actives');

        return view('administrators.role-menu.edit', ['pageName' => 'Edit Role Menu'] + compact('role', 'currMenu', 'menus', 'pivotData', 'platforms', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleMenuRequest $request, int $roleId, int $menuId, RoleMenuService $roleMenuService)
    {
        $currentUser = $request->user();
        $role = Role::findOrFail($roleId);
        
        if ($currentUser->cannot('update', $role)) {
            abort(403);
        }
        
        $validatedData = $request->validated();

        try {
            $result = $roleMenuService->updateRoleMenu($role, $menuId, $validatedData);

            if (isset($result['error'])) {
                return back()->withErrors(['menu' => $result['error']]);
            }

            return redirect()->route('role-menu.index')->with('success', 'Role menu updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating role menu: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the role menu.');
        }
    }
}
