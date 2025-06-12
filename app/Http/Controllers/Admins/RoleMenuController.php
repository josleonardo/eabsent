<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreRoleMenuRequest;
use App\Http\Requests\Admins\UpdateRoleMenuRequest;
use App\Models\Menu;
use App\Models\Role;
use App\Services\Admins\RoleMenuService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roleMenus = DB::table('role_menu as rm')
            ->join('roles as r', 'rm.role_id', '=', 'r.id')
            ->join('menus as m', 'rm.menu_id', '=', 'm.id')
            ->select(
                'r.id as role_id',
                'r.name as role_name',
                'm.id as menu_id',
                'm.name as menu_name',
                'm.platform',
                'rm.active',
                'rm.created_at',
                'rm.created_by',
                'rm.updated_at',
                'rm.updated_by'
            )
            ->orderBy('r.id')
            ->paginate(20);

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
            Log::error($th);

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
        $validatedData = $request->validated();

        try {
            $role = Role::findOrFail($roleId);
            $defaultData = [
                'active' => $validatedData['active'],
                'updated_by' => request()->user()->id,
                'updated_at' => now(),
            ];

            $result = $roleMenuService->updateRoleMenu($role, $menuId, $validatedData, $defaultData);

            if (isset($result['error'])) {
                return back()->withErrors(['menu' => $result['error']]);
            }

            return redirect()->route('role-menu.index')->with('success', 'Role menu updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating the role menu.');
        }
    }
}
