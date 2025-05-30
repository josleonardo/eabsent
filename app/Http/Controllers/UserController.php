<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'regex:/[0-9]/', // Must contain a number
                'regex:/[!-\/:-@[-`{-~]/', // Must contain a special character
            ],
            'nik' => 'required|integer|numeric|digits:16|unique:user_profiles,nik',
            'nuptk' => 'nullable|integer|numeric|digits:16|unique:user_profiles,nuptk',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|integer|exists:roles,id',
            'level' => 'nullable|integer|exists:levels,id',
            'schedule' => [
                'nullable',
                'regex:/^(\d+(,\d+)*)?$/', // Comma-separated list of integers
            ],
            'employment_start' => 'required|date',
            'employment_end' => 'nullable|date',
            'active' => 'required|boolean',
        ], [
            'password.regex' => 'The password must contain at least one number and one special character.',
        ]);

        $currentUserId = Auth::id();
        $defaultData = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        DB::beginTransaction();

        try {
            // Create user credentials
            $user = User::create(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
            ], $defaultData));

            // Create user profile
            $user->profile()->create(array_merge([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'nik' => $validatedData['nik'],
                'nuptk' => $validatedData['nuptk'],
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'employment_start' => $validatedData['employment_start'],
                'employment_end' => $validatedData['employment_end'],
            ], $defaultData));

            // Attach the role to the user
            if (! empty($validatedData['role'])) {
                $user->roles()->attach($validatedData['role'], $defaultData);
            }

            // Attach the level to the user
            if (! empty($validatedData['level'])) {
                $user->levels()->attach($validatedData['level'], $defaultData);
            }

            // Attach the schedules to the user
            $scheduleIds = [];
            if (! empty($validatedData['schedule'])) {
                $scheduleIds = explode(',', $validatedData['schedule']);

                // Validate all IDs exist in DB
                $validIds = Schedule::whereIn('id', $scheduleIds)->pluck('id')->toArray();
                if (count($validIds) !== count($scheduleIds)) {
                    return back()->withErrors(['schedule' => 'One or more selected schedules are invalid.']);
                }

                $user->schedules()->attach($scheduleIds, $defaultData);
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('user.index')->with('success', 'User added successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'An error occurred while creating the user.']);
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
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email,'.$id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$id,
            'nik' => 'required|integer|numeric|digits:16|unique:user_profiles,nik,'.$id.',user_id',
            'nuptk' => 'nullable|integer|numeric|digits:16|unique:user_profiles,nuptk,'.$id.',user_id',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'role' => 'nullable|integer|exists:roles,id',
            'level' => 'nullable|integer|exists:levels,id',
            'schedule' => [
                'nullable',
                'regex:/^(\d+(,\d+)*)?$/', // Comma-separated list of integers
            ],
            'employment_start' => 'required|date',
            'employment_end' => 'nullable|date',
            'active' => 'required|boolean',
        ]);

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

        DB::beginTransaction();

        try {
            // Update user credentials
            $user->update(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
            ], $defaultData));

            // Update user profile
            $user->profile()->update(array_merge([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'nik' => $validatedData['nik'],
                'nuptk' => $validatedData['nuptk'],
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'employment_start' => $validatedData['employment_start'],
                'employment_end' => $validatedData['employment_end'],
            ], $defaultData));

            // Sync the role to the user
            if (! empty($validatedData['role'])) {
                $user->roles()->syncWithPivotValues([$validatedData['role']], $defaultSync);
            } else {
                $user->roles()->detach();
            }

            // Sync the level to the user
            if (! empty($validatedData['level'])) {
                $user->levels()->syncWithPivotValues([$validatedData['level']], $defaultSync);
            } else {
                $user->levels()->detach();
            }

            // Sync the schedules to the user
            $scheduleIds = [];
            if (! empty($validatedData['schedule'])) {
                $scheduleIds = explode(',', $validatedData['schedule']);

                // Validate all IDs exist in DB
                $validIds = Schedule::whereIn('id', $scheduleIds)->pluck('id')->toArray();
                if (count($validIds) !== count($scheduleIds)) {
                    return back()->withErrors(['schedule' => 'One or more selected schedules are invalid.']);
                }

                $user->schedules()->syncWithPivotValues($scheduleIds, $defaultSync);
            } else {
                $user->schedules()->detach();
            }

            // Commit the transaction if everything is successful
            DB::commit();

            return redirect()->route('user.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();

            return back()->withInput()->withErrors(['error' => 'An error occurred while updating the user.']);
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
