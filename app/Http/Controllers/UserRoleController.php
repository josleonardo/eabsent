<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'email', 'username')->with('roles:id,name')->paginate(10);

        return view('administrators.user-role.index', ['pageName' => 'User Role'] + compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::select('id', 'email', 'username')->with('roles:id,name')->findOrFail($id);
        $roles = Role::select('id', 'name')->where('active', 1)->get();

        return view('administrators.user-role.edit', ['pageName' => 'Edit User Role'] + compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'role' => 'nullable|exists:roles,id',
            'active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $currentUserId = Auth::id();
        $defaultSync = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        if (!empty($validatedData['role'])) {
            $user->roles()->syncWithPivotValues([$validatedData['role']], $defaultSync);
        } else {
            $user->roles()->detach();
        }

        return redirect()->route('user-role.index')->with('success', 'User role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
