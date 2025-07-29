<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreLevelRequest;
use App\Http\Requests\Admins\UpdateLevelRequest;
use App\Models\Level;
use App\Services\Admins\LevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LevelService $levelService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('viewAny', Level::class)) {
            abort(403);
        }

        $levels = $levelService->getLevels($currentUser);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.levels.index', ['pageName' => 'Levels'] + compact('levels', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeKey = config('constants.actives');

        return view('administrators.levels.create', ['pageName' => 'Add level'] + compact('activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLevelRequest $request, LevelService $levelService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('create', Level::class)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $levelService->createLevel($validatedData, $currentUserId);

            return redirect()->route('level.index')->with('success', 'Level created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating level: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating a level.');
        }

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
        $activeKey = config('constants.actives');

        return view('administrators.levels.edit', ['pageName' => 'Edit level'] + compact('level', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLevelRequest $request, Level $level, LevelService $levelService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('update', $level)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $levelService->updateLevel($level, $validatedData, $currentUserId);

            return redirect()->route('level.index')->with('success', 'Level updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating level: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the level.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        //
    }
}
