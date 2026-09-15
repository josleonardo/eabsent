<?php

namespace App\Http\Controllers\Reports;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Services\Reports\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        Request $request,
        AttendanceService $attendanceService
    ) {
        $user = $request->user();

        $attendances = $attendanceService->getAttendances($user, 25);

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
    public function update(
        UpdateAttendanceRequest $request,
        Attendance $attendance,
        AttendanceService $attendanceService
    ) {
        try {
            $attendanceService->updateAttendance(
                $attendance,
                $request->validated(),
                $request->user()->id
            );

            return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            Log::error(
                'Error updating attendance data: ' . $th->getMessage(),
                ['exception' => $th]
            );

            return back()->with(
                'error',
                'An unexpected error occurred while updating the attendance.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function exportExcel()
    {
        return (new AttendanceExport)->download('attendance.xlsx');
    }

    public function exportCsv()
    {
        return (new AttendanceExport)->download('attendance.csv');
    }
}
