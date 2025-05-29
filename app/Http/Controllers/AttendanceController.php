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
        $statusKey = config('constants.attendance_status');

        return view('reports.attendances.index', ['pageName' => 'Attendance report'] + compact('attendances', 'statusKey'));
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
        $statuses = collect(config('constants.attendance_status'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value['label'])];
            })
            ->toArray();
        return view('reports.attendances.edit', ['pageName' => 'Edit Attendance'] + compact('attendance', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $validatedData = $request->validate([
            'actual_in' => 'nullable|date_format:H:i',
            'actual_out' => 'nullable|date_format:H:i',
            'status' => 'required|integer|max:20',
        ]);

        $currentUserId = Auth::id();

        $attendance->update([
            'actual_in' => $validatedData['actual_in'],
            'actual_out' => $validatedData['actual_out'],
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
