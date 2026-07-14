<?php

namespace App\Services\Admins;

use App\Models\LeaveType;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LeaveTypeService
{
    public function getLeaveTypes(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return LeaveType::paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createLeaveType(array $validatedData, int $currentUserId): LeaveType
    {
        return LeaveType::create([
            ...$validatedData,
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);
    }

    public function updateLeaveType(LeaveType $leaveType, array $validatedData, int $currentUserId): LeaveType
    {
        $leaveType->update([
            ...$validatedData,
            'updated_by' => $currentUserId,
        ]);

        return $leaveType;
    }
}
