<?php

namespace App\Exports\Approvals\Corrections;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CorrectionExport implements WithMultipleSheets
{
    use Exportable;

    public function sheets(): array
    {
        $sheets = [
            new CorrectionPendingExport,
            new CorrectionHistoryExport,
        ];

        return $sheets;
    }
}
