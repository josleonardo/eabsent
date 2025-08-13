<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }

    public function map($user): array
    {
        $yesNoKey = config('constants.yes_no');
        $user->active = $yesNoKey[$user->active] ? __($yesNoKey[$user->active]) : __('Unknown');
        $user->created_at->format('Y-m-d H:i:s');
        $user->updated_at->format('Y-m-d H:i:s');

        return [
            $user->email,
            $user->username,
            $user->active,
            $user->created_at,
            $user->created_by,
            $user->updated_at,
            $user->updated_by,
        ];
    }

    public function headings(): array
    {
        return [
            'Email',
            'Username',
            'Active',
            'Created At',
            'Created By',
            'Updated At',
            'Updated By',
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
