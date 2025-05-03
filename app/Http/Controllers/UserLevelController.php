<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'email', 'username')->with('levels:id,name')->paginate(10);

        return view('administrators.user-level.index', ['pageName' => 'User Level'] + compact('users'));
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
        $user = User::select('id', 'email', 'username')->with('levels:id,name')->findOrFail($id);
        $levels = Level::select('id', 'name')->where('active', 1)->get();

        return view('administrators.user-level.edit', ['pageName' => 'Edit User Level'] + compact('user', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'level' => 'nullable|exists:levels,id',
            'active' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $currentUserId = Auth::id();
        $defaultSync = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        if (!empty($validatedData['level'])) {
            $user->levels()->syncWithPivotValues([$validatedData['level']], $defaultSync);
        } else {
            $user->levels()->detach();
        }

        return redirect()->route('user-level.index')->with('success', 'User level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
