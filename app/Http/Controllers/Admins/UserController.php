<?php

namespace App\Http\Controllers\Admins;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreUserRequest;
use App\Http\Requests\Admins\UpdateUserRequest;
use App\Models\Level;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Services\Admins\UserService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserService $userService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('viewAny', User::class)) {
            abort(403);
        }

        $users = $userService->getUsers($currentUser);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.users.index', ['pageName' => 'Users'] + compact('users', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'name')->where('active', true)->get();
        $levels = Level::select('id', 'name')->where('active', true)->get();

        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')->where('active', true)->get();
        $schedules = $schedules->map(function ($schedule) {
            // Format the schedule data
            $schedule->check_in_time = Carbon::parse($schedule->check_in_time)->format('H:i');
            $schedule->check_out_time = Carbon::parse($schedule->check_out_time)->format('H:i');

            return $schedule;
        })->groupBy('group')->map(function ($group) {
            $first = $group->first();

            return [
                'ids' => $group->pluck('id')->toArray(),
                'display' => "{$first->check_in_time} - {$first->check_out_time}",
            ];
        })->values();

        $activeKey = config('constants.actives');

        return view('administrators.users.create', ['pageName' => 'Add user'] + compact('roles', 'levels', 'schedules', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, UserService $userService): RedirectResponse
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('create', User::class)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $currentUser->id;

            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = $request->file('avatar');
            }

            $userService->createUser($validatedData, $currentUserId);

            return redirect()->route('user.index')->with('success', 'User created successfully');
        } catch (\Throwable $th) {
            Log::error('Error creating user: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->cannot('view', $user)) {
            abort(403);
        }

        $roles = Role::select('id', 'name')->where('active', true)->get();
        $levels = Level::select('id', 'name')->where('active', true)->get();
        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')->where('active', true)->get();

        return view('administrators.users.show', ['pageName' => 'User Profile'] + compact('user', 'roles', 'levels', 'schedules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::select('id', 'name')->where('active', true)->get();
        $levels = Level::select('id', 'name')->where('active', true)->get();

        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')->where('active', true)->get();
        $schedules = $schedules->map(function ($schedule) {
            // Format the schedule data
            $schedule->check_in_time = Carbon::parse($schedule->check_in_time)->format('H:i');
            $schedule->check_out_time = Carbon::parse($schedule->check_out_time)->format('H:i');

            return $schedule;
        })->groupBy('group')->map(function ($group) {
            $first = $group->first();

            return [
                'ids' => $group->pluck('id')->toArray(),
                'display' => "{$first->check_in_time} - {$first->check_out_time}",
            ];
        })->values();

        $activeKey = config('constants.actives');

        return view('administrators.users.edit', ['pageName' => 'Edit user'] + compact('user', 'roles', 'levels', 'schedules', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id, UserService $userService): RedirectResponse
    {
        $user = User::findOrFail($id);
        $currentUser = $request->user();

        if ($currentUser->cannot('update', $user)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $currentUser->id;

            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = $request->file('avatar');
            }

            $userService->updateUser($user, $validatedData, $currentUserId);

            return redirect()->route('user.index')->with('success', 'User updated successfully');
        } catch (\Throwable $th) {
            Log::error('Error updating user: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the user.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportExcel() 
    {
        return (new UserExport)->download('users.xlsx');
    }
    
    public function exportCsv() 
    {
        return (new UserExport)->download('users.csv');
    }
}
