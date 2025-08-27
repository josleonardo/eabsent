<?php

namespace App\Exports\Approvals\Leaves;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LeaveExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [
            new LeavePendingExport,
            new LeaveHistoryExport,
        ];

        return $sheets;
    }
}
