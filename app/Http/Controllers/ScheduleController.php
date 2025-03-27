<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
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
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('administrators.schedules.schedule', ['pageName' => 'Schedule', 'singleName' => 'schedule'], compact('schedules', 'days'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('administrators.schedules.create', ['pageName' => 'Add schedule'], compact('days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'schedule_name' => 'required|string|max:255',
            'day_of_week' => 'required|integer|max:7',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'active' => 'required|boolean',
        ]);

        Schedule::create([
            'schedule_name' => $validatedData['schedule_name'],
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
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('administrators.schedules.edit', ['pageName' => 'Edit schedule'], compact('schedule', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $currentUserId = Auth::id();

        // Format the time fields to match H:i
        $request->merge([
            'check_in_time' => Carbon::parse($request->check_in_time)->format('H:i'),
            'check_out_time' => Carbon::parse($request->check_out_time)->format('H:i'),
        ]);

        $validatedData = $request->validate([
            'schedule_name' => 'required|string|max:255',
            'day_of_week' => 'required|integer|max:7',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'active' => 'required|boolean',
        ]);

        $schedule->update([
            'schedule_name' => $validatedData['schedule_name'],
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
