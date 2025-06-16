<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\Leave;
use App\Services\Reports\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AttendanceService $attendanceService)
    {
        $user = Auth::user();
        $attendances = $attendanceService->getAttendances($user);
        $leaves = Leave::select('id', 'start_date', 'end_date', 'reason', 'status', 'created_by')->where('status', 1)->get();

        $leavesByUser = $leaves->groupBy('created_by');

        // Update attendance status if user is on leave for that date
        foreach ($attendances as $attendance) {
            $userLeaves = $leavesByUser->get($attendance->user_id, collect());
            foreach ($userLeaves as $leave) {
                if (
                    $attendance->date >= $leave->start_date &&
                    $attendance->date <= $leave->end_date
                ) {
                    $attendance->status = 3;
                    break; // No need to check other leaves for this attendance
                }
            }
        }

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
    public function update(UpdateAttendanceRequest $request, Attendance $attendance, AttendanceService $attendanceService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $attendanceService->updateAttendance($attendance, $validatedData, $currentUserId);

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
