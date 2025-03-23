<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('administrators.users.user', ['pageName' => 'Users', 'singleName' => 'user'], compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('administrators.users.create', ['pageName' => 'Add user'], compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'fullname' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'regex:/[0-9]/', // Must contain a number
                'regex:/[!-\/:-@[-`{-~]/', // Must contain a special character
            ],
            'nik' => 'required|string|max:16|unique:user_profiles,nik',
            'nuptk' => 'nullable|string|max:16|unique:user_profiles,nuptk',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|integer|exists:roles,id',
            'employment_start' => 'required|date',
            'employment_end' => 'nullable|date',
            'active' => 'required|boolean',
        ], [
            'password.regex' => 'The password must contain at least one number and one special character.',
        ]);

        // Create the user
        $user = User::create([
            'email' => $validatedData['email'],
            'username' => $validatedData['username'],
            'password' => Hash::make($validatedData['password']),
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        // Create the user profile
        $user->profile()->create([
            'fullname' => $validatedData['fullname'],
            'nik' => $validatedData['nik'],
            'nuptk' => $validatedData['nuptk'],
            'position' => $validatedData['position'],
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
            'employment_start' => $validatedData['employment_start'],
            'employment_end' => $validatedData['employment_end'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        // Attach the role to the user
        $user->role()->attach($validatedData['role'], [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('user.index')->with('success', 'User added successfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
