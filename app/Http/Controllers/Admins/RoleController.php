<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreRoleRequest;
use App\Http\Requests\Admins\UpdateRoleRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(10);

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
    public function store(StoreRoleRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            Role::create([
                'name' => $validatedData['role_name'],
                'active' => $validatedData['active'],
                'created_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ]);

            return redirect()->route('role.index')->with('success', 'Role created successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

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
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $role->update([
                'name' => $validatedData['role_name'],
                'active' => $validatedData['active'],
                'updated_by' => $currentUserId,
            ]);

            return redirect()->route('role.index')->with('success', 'Role updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

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
