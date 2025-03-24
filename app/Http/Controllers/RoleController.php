<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(10);
        return view('administrators.roles.role', ['pageName'=> 'Roles', 'singleName' => 'role'], compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.roles.create', ['pageName' => 'Add role']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'role_name' => 'required|string|max:255|unique:roles',
            'active' => 'required|boolean',
        ]);

        Role::create([
            'role_name' => $validatedData['role_name'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('role.index')->with('success', 'Role added successfully.');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
