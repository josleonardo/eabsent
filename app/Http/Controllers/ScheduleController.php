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
        return view('administrators.schedules.schedule', ['pageName' => 'Schedule', 'singleName' => 'schedule'], compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.schedules.create', ['pageName' => 'Add schedule']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'schedule_name' => 'required|string|max:255|unique:schedules',
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
