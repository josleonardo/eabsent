<?php

namespace App\Exports\Approvals\Leaves;

use App\Services\Approvals\LeaveService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeavePendingExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        $leaveService = new LeaveService;

        return $leaveService->exportPending($user);
    }

    public function title(): string
    {
        return 'Pending Leaves';
    }

    public function headings(): array
    {
        return [
            'Requested By',
            'Level',
            'Start Date',
            'End Date',
            'Reason',
            'File',
            'Requested At',
        ];
    }

    public function map($pending): array
    {
        return [
            $pending->requester->full_name,
            $pending->requester->levels->first()->name ?? '',
            $pending->start_date,
            $pending->end_date,
            $pending->reason,
            $pending->file_path,
            $pending->created_at,
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
