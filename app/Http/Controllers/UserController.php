<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Level;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'email', 'username', 'active', 'created_at', 'updated_at', 'created_by', 'updated_by')->paginate(10);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.users.index', ['pageName' => 'Users'] + compact('users', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'name')->where('active', 1)->get();
        $levels = Level::select('id', 'name')->where('active', 1)->get();

        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')->where('active', 1)->get();
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
    public function store(StoreUserRequest $request, UserService $userService)
    {
        $validatedData = $request->validated();

        $currentUserId = Auth::id();
        $defaultData = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        if ($request->hasFile('avatar')) {
            $validatedData['avatar'] = $request->file('avatar');
        }

        try {
            $userService->createUser($validatedData, $defaultData);

            return redirect()->route('user.index')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage() ?: 'An error occurred while creating the user.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::select('id', 'name')->where('active', 1)->get();
        $levels = Level::select('id', 'name')->where('active', 1)->get();
        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')->where('active', 1)->get();

        return view('administrators.users.show', ['pageName' => 'User Profile'] + compact('user', 'roles', 'levels', 'schedules'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::select('id', 'name')->where('active', 1)->get();
        $levels = Level::select('id', 'name')->where('active', 1)->get();

        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')->where('active', 1)->get();
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
    public function update(UpdateUserRequest $request, string $id, UserService $userService)
    {
        $validatedData = $request->validated();

        $user = User::findOrFail($id);
        $currentUserId = Auth::id();

        $defaultData = [
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ];
        $defaultSync = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        if ($request->hasFile('avatar')) {
            $validatedData['avatar'] = $request->file('avatar');
        }

        try {
            $userService->updateUser($user, $validatedData, $defaultData, $defaultSync);

            return redirect()->route('user.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => $e->getMessage() ?: 'An error occurred while updating the user.']);
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
