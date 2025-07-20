<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\UpdateUserLevelRequest;
use App\Models\Level;
use App\Models\User;
use App\Services\Admins\UserLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserLevelService $userLevelService)
    {
        $userLevel = $request->user()->levels->first()->name ?? '';
        $users = $userLevelService->getUsersLevels($userLevel);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.user-level.index', ['pageName' => 'User Level'] + compact('users', 'activeKey', 'yesNoKey'));
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

        $activeKey = config('constants.actives');

        return view('administrators.user-level.edit', ['pageName' => 'Edit User Level'] + compact('user', 'levels', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserLevelRequest $request, string $id, UserLevelService $userLevelService)
    {
        $validatedData = $request->validated();

        try {
            $user = User::findOrFail($id);
            $currentUserId = $request->user()->id;

            $userLevelService->updateUserLevel($validatedData, $user, $currentUserId);

            return redirect()->route('user-level.index')->with('success', 'User level updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating user level: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the user level.');
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
