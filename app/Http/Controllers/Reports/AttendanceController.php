<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\UpdateAttendanceRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $statusKey = collect(config('constants.attendance_status'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value['status'])];
            })
            ->toArray();

        return view('reports.attendances.edit', ['pageName' => 'Edit Attendance'] + compact('attendance', 'statusKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $attendance->update([
                'actual_in' => $validatedData['actual_in'],
                'actual_out' => $validatedData['actual_out'],
                'status' => $validatedData['status'],
                'updated_by' => $currentUserId,
            ]);

            return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

            return back()->with('error', 'An error occurred while updating the attendance.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }
}
