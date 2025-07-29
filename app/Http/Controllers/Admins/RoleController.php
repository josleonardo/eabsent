<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreRoleRequest;
use App\Http\Requests\Admins\UpdateRoleRequest;
use App\Models\Role;
use App\Services\Admins\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, RoleService $roleService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('viewAny', Role::class)) {
            abort(403);
        }

        $roles = $roleService->getRoles($currentUser);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.roles.index', ['pageName' => 'Roles'] + compact('roles', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeKey = config('constants.actives');

        return view('administrators.roles.create', ['pageName' => 'Add role'] + compact('activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request, RoleService $roleService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('create', Role::class)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $roleService->createRole($validatedData, $currentUserId);

            return redirect()->route('role.index')->with('success', 'Role created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating role: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the role.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $activeKey = config('constants.actives');

        return view('administrators.roles.edit', ['pageName' => 'Edit role'] + compact('role', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role, RoleService $roleService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('update', $role)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $roleService->updateRole($role, $validatedData, $currentUserId);

            return redirect()->route('role.index')->with('success', 'Role updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating role: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the role.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
