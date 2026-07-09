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

class UserExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        $userService = new \App\Services\Admins\UserService;

        return $userService->exportUsers($user);
    }

    public function headings(): array
    {
        return [
            'Email',
            'Username',
            'School Location',
            'Schedule Group',
            'Active',
            'Created At',
            'Created By',
            'Updated At',
            'Updated By',
        ];
    }

    public function map($user): array
    {
        $yesNoKey = config('constants.yes_no');
        $user->active = $yesNoKey[$user->active] ? __($yesNoKey[$user->active]) : __('Unknown');
        $user->school_location_name = $user->schoolLocation->name ?? __('Unknown');
        $user->schedule_group_name = $user->scheduleGroup->name ?? __('Unknown');
        $user->created_by = $user->full_name ?? __('Unknown');
        $user->updated_by = $user->full_name ?? __('Unknown');

        return [
            $user->email,
            $user->username,
            $user->school_location_name,
            $user->schedule_group_name,
            $user->active,
            $user->created_at,
            $user->created_by,
            $user->updated_at,
            $user->updated_by,
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
