<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::paginate(10);
        return view('administrators.levels.index', ['pageName' => 'Levels'] + compact('levels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.levels.create', ['pageName' => 'Add level']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'level_name' => 'required|string|max:255|unique:levels,name,',
            'active' => 'required|boolean',
        ]);

        $currentUserId = Auth::id();

        Level::create([
            'name' => $validatedData['level_name'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('level.index')->with('success', 'Level added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        return view('administrators.levels.edit', ['pageName' => 'Edit level'] + compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $validatedData = $request->validate([
            'level_name' => 'required|string|max:255|unique:levels,name,' . $level->id,
            'active' => 'required|boolean',
        ]);

        $currentUserId = Auth::id();

        $level->update([
            'name' => $validatedData['level_name'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('level.index')->with('success', 'Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        //
    }
}
