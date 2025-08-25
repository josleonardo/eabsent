<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        $attendanceService = new \App\Services\Reports\AttendanceService;

        return $attendanceService->exportAttendances($user);
    }

    public function headings(): array
    {
        return [
            'Full Name',
            'Day',
            'Date',
            'Scheduled In',
            'Scheduled Out',
            'Actual In',
            'Actual Out',
            'Status',
            'Updated At',
            'Updated By',
        ];
    }

    public function map($attendance): array
    {
        $statusKey = config('constants.attendance_status');
        $attendance->status = $statusKey[$attendance->status] ? __($statusKey[$attendance->status]['status']) : __('Unknown');

        return [
            $attendance->users->full_name,
            $attendance->day_name,
            $attendance->date,
            $attendance->sched_in,
            $attendance->sched_out,
            $attendance->actual_in,
            $attendance->actual_out,
            $attendance->status,
            $attendance->updated_at,
            $attendance->updated_by,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
