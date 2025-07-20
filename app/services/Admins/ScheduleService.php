<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleService
{
    public function getSchedules(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return Schedule::paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createSchedule(array $validatedData, int $currentUserId): Schedule
    {
        $schedule = Schedule::create([
            'group' => $validatedData['group'],
            'day_of_week' => $validatedData['day_of_week'],
            'check_in_time' => $validatedData['check_in_time'],
            'check_out_time' => $validatedData['check_out_time'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $schedule;
    }

    public function updateSchedule(Schedule $schedule, array $validatedData, int $currentUserId): Schedule
    {
        $schedule->update([
            'group' => $validatedData['group'],
            'day_of_week' => $validatedData['day_of_week'],
            'check_in_time' => $validatedData['check_in_time'],
            'check_out_time' => $validatedData['check_out_time'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $schedule;
    }
}
