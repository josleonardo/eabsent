<?php

namespace App\Exports\Approvals\Corrections;

use App\Services\Approvals\CorrectionService;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CorrectionPendingExport implements FromCollection, ShouldAutoSize, WithTitle, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        $correctionService = new CorrectionService();

        return $correctionService->exportPending($user);
    }

    public function title(): string
    {
        return 'Pending Corrections';
    }

    public function headings(): array
    {
        return [
            'Requested By',
            'Level',
            'Date',
            'Start Time',
            'End Time',
            'Reason',
            'Requested At',
        ];
    }

    public function map($pending): array
    {
        return [
            $pending->requester->full_name,
            $pending->requester->levels->first()->name ?? '',
            $pending->date,
            $pending->actual_in,
            $pending->actual_out,
            $pending->reason,
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
