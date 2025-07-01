<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\UpdateUserRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'email', 'username')->with('roles:id,name')->paginate(10);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.user-role.index', ['pageName' => 'User Role'] + compact('users', 'activeKey', 'yesNoKey'));
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

        $activeKey = config('constants.actives');

        return view('administrators.user-role.edit', ['pageName' => 'Edit User Role'] + compact('user', 'roles', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRoleRequest $request, string $id)
    {
        $validatedData = $request->validated();

        try {
            $user = User::findOrFail($id);
            $currentUserId = $request->user()->id;
            $defaultSync = [
                'active' => $validatedData['active'],
                'created_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ];

            if (! empty($validatedData['role'])) {
                $user->roles()->syncWithPivotValues([$validatedData['role']], $defaultSync);
            } else {
                $user->roles()->detach();
            }

            return redirect()->route('user-role.index')->with('success', 'User role updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating the user role.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
