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

class LeaveHistoryExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        $leaveService = new LeaveService;

        return $leaveService->exportHistory($user);
    }

    public function title(): string
    {
        return 'Leave History';
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
            'Status',
            'Processed At',
            'Processed By',
        ];
    }

    public function map($history): array
    {
        $statusKey = config('constants.approve_status');
        $history->status = $statusKey[$history->status] ? __($statusKey[$history->status]['status']) : __('Unknown');

        return [
            $history->requester->full_name,
            $history->requester->levels->first()->name ?? '',
            $history->start_date,
            $history->end_date,
            $history->reason,
            $history->file_path,
            $history->created_at,
            $history->status,
            $history->approved_at,
            $history->approver->full_name,
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
