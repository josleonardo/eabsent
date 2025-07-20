<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreUserScheduleRequest;
use App\Http\Requests\Admins\UpdateUserScheduleRequest;
use App\Models\Schedule;
use App\Models\User;
use App\Services\Admins\UserScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserScheduleService $userScheduleService)
    {
        $userRole = Auth::user()->roles->first()->name ?? '';
        $userSchedules = $userScheduleService->getUsersSchedules($userRole);

        $days = config('constants.days');
        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.user-schedule.index', ['pageName' => 'User Schedule'] + compact('userSchedules', 'days', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::select('id')
            ->with(['profile:user_id,first_name,last_name'])
            ->orderBy('id')
            ->get();

        $schedules = Schedule::select('id', 'group', 'day_of_week', 'check_in_time', 'check_out_time')
            ->where('active', 1)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'display' => $schedule->day_name.' ('.$schedule->formatted_check_in.' - '.$schedule->formatted_check_out.')',
                ];
            });

        $activeKey = config('constants.actives');

        return view('administrators.user-schedule.create', ['pageName' => 'Add User Schedule'] + compact('users', 'schedules', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserScheduleRequest $request, UserScheduleService $userScheduleService)
    {
        $validatedData = $request->validated();

        try {
            $user = User::findOrFail($validatedData['user']);
            $currentUserId = $request->user()->id;

            $userScheduleService->createUserSchedules($validatedData, $user, $currentUserId);

            return redirect()->route('user-schedule.index')->with('success', 'User schedule created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating user schedule: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the user schedule.');
        }
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
    public function edit(string $userId, string $scheduleId)
    {
        $user = User::select('id')->findOrFail($userId);
        $currSchedule = Schedule::select('id', 'group', 'day_of_week')->findOrFail($scheduleId);
        $pivotData = $user->schedules->where('id', $currSchedule->id)->first()->pivot;

        $schedules = Schedule::select('id', 'group', 'check_in_time', 'check_out_time')
            ->where('active', 1)
            ->where('day_of_week', $currSchedule->day_of_week)
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'display' => $schedule->formatted_check_in.' - '.$schedule->formatted_check_out,
                ];
            });

        $activeKey = config('constants.actives');

        return view('administrators.user-schedule.edit', ['pageName' => 'Edit User Schedule'] + compact('user', 'currSchedule', 'pivotData', 'schedules', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserScheduleRequest $request, string $userId, string $scheduleId, UserScheduleService $userScheduleService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $userScheduleService->updateUserSchedules($validatedData, $userId, $scheduleId, $currentUserId);

            return redirect()->route('user-schedule.index')->with('success', 'User schedule updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating user schedule: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the user schedule.');
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
