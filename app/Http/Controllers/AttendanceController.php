<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $attendances = Attendance::getAttendances($user);
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('reports.attendances.index', ['pageName' => 'Attendance report'] + compact('attendances', 'days'));
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
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attendance $attendance)
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $attendance->real_check_in = Carbon::parse($attendance->real_check_in)->format('H:i');
        $attendance->real_check_out = Carbon::parse($attendance->real_check_out)->format('H:i');

        return view('reports.attendances.edit', ['pageName' => 'Edit Attendance'] + compact('attendance', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validatedData = $request->validate([
            'real_check_in' => 'required|date_format:H:i',
            'real_check_out' => 'required|date_format:H:i',
            'status' => 'required|string',
        ]);

        $currentUserId = Auth::id();

        $attendance->update([
            'real_check_in' => $validatedData['real_check_in'],
            'real_check_out' => $validatedData['real_check_out'],
            'status' => $validatedData['status'],
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
