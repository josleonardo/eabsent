<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userSchedules = DB::table('user_schedule')
            ->join('users', 'user_schedule.user_id', '=', 'users.id')
            ->join('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->join('schedules', 'user_schedule.schedule_id', '=', 'schedules.id')
            ->select(
                'users.id as user_id',
                'user_profiles.first_name',
                'user_profiles.last_name',
                'schedules.id as schedule_id',
                'schedules.day_of_week',
                'schedules.check_in_time',
                'schedules.check_out_time',
                'user_schedule.active',
                'user_schedule.created_at',
                'user_schedule.created_by',
                'user_schedule.updated_at',
                'user_schedule.updated_by'
            )
            ->orderBy('users.id')
            ->orderBy('schedules.day_of_week')
            ->paginate(10);

        $days= config('constants.days');
        $options = config('constants.options');

        return view('administrators.user-schedule.index', ['pageName' => 'User Schedule'] + compact('userSchedules', 'days', 'options'));
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
                    'display' => $schedule->day_name . ' (' . $schedule->formatted_check_in . ' - ' . $schedule->formatted_check_out . ')',
                ];
            });

        $active = config('constants.active');

        return view('administrators.user-schedule.create', ['pageName' => 'Add User Schedule'] + compact('users', 'schedules', 'active'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user' => 'required|exists:users,id',
            'schedule' => 'required|exists:schedules,id',
            'active' => 'required|boolean',
        ]);

        $user = User::findOrFail($validatedData['user']);
        $currentUserId = Auth::id();

        if ($user->schedules()->where('schedule_id', $validatedData['schedule'])->exists()) {
            return back()->withErrors(['schedule' => 'This user already has the selected schedule.']);
        } else {
            $user->schedules()->attach($validatedData['schedule'], [
                'active' => $validatedData['active'],
                'created_by' => $currentUserId,
                'updated_by' => $currentUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('user-schedule.index')->with('success', 'User schedule created successfully.');
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
                    'display' => $schedule->formatted_check_in . ' - ' . $schedule->formatted_check_out,
                ];
            });

        $active = config('constants.active');

        return view('administrators.user-schedule.edit', ['pageName' => 'Edit User Schedule'] + compact('user', 'currSchedule', 'pivotData', 'schedules', 'active'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $userId, string $scheduleId)
    {
        $validatedData = $request->validate([
            'schedule' => 'nullable|exists:schedules,id',
            'active' => 'required|boolean',
        ]);

        $user = User::findOrFail($userId);
        $currentUserId = Auth::id();

        if (!empty($validatedData['schedule'])) {
            DB::table('user_schedule')
                ->where('user_id', $userId)
                ->where('schedule_id', $scheduleId)
                ->update([
                    'schedule_id' => $validatedData['schedule'],
                    'active' => $validatedData['active'],
                    'updated_by' => $currentUserId,
                    'updated_at' => now(),
                ]);
        } else {
            $user->schedules()->detach($scheduleId);
        }

        return redirect()->route('user-schedule.index')->with('success', 'User schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
