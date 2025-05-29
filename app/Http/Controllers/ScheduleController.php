<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = Schedule::paginate(10);

        $days = config('constants.days');
        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.schedules.index', ['pageName' => 'Schedule'] + compact('schedules', 'days', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = collect(config('constants.days'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();
        $activeKey = config('constants.actives');

        return view('administrators.schedules.create', ['pageName' => 'Add schedule'] + compact('days', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'group' => 'required|integer|max:20',
            'day_of_week' => 'required|integer|max:7',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'active' => 'required|boolean',
        ]);
        
        $currentUserId = Auth::id();

        Schedule::create([
            'group' => $validatedData['group'],
            'day_of_week' => $validatedData['day_of_week'],
            'check_in_time' => $validatedData['check_in_time'],
            'check_out_time' => $validatedData['check_out_time'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('schedule.index')->with('success', 'Schedule added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $days = collect(config('constants.days'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();
        $activeKey = config('constants.actives');

        return view('administrators.schedules.edit', ['pageName' => 'Edit schedule'] + compact('schedule', 'days', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validatedData = $request->validate([
            'group' => 'required|integer|max:20',
            'day_of_week' => 'required|integer|max:7',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'active' => 'required|boolean',
        ]);

        $currentUserId = Auth::id();

        $schedule->update([
            'group' => $validatedData['group'],
            'day_of_week' => $validatedData['day_of_week'],
            'check_in_time' => $validatedData['check_in_time'],
            'check_out_time' => $validatedData['check_out_time'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('schedule.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
