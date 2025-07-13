<?php

namespace App\Services\Reports;

use App\Models\Attendance;
use App\Models\Level;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AttendanceService
{
    /**
     * Get attendances based on the user's role and level.
     */
    public function getAttendances(string $role, string $level): LengthAwarePaginator
    {
        $query = Attendance::select(['id', 'user_id', 'date', 'sched_in', 'sched_out', 'actual_in', 'actual_out', 'status', 'updated_at', 'updated_by'])
            ->with(['users.profile:user_id,first_name,last_name', 'users.levels:id,name', 'users.roles:id,name']);

        if (in_array($role, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN])) {
            if ($level == Level::LEVEL_ADMIN) {
                return $query->latest('date')->paginate(25);
            } else {
                return $query->whereHas('users.levels', function (Builder $q) use ($level) {
                    $q->where('name', $level);
                })->latest('date')->paginate(25);
            }
        }

        if ($role == Role::ROLE_HEADMASTER) {
            return $query->whereHas('users.roles', function (Builder $q) {
                $q->where('name', Role::ROLE_TEACHER);
            })
                ->whereHas('users.levels', function (Builder $q) use ($level) {
                    $q->where('name', $level);
                })->latest('date')->paginate(25);
        }

        // Default case: return an empty result if no conditions are met
        return $query->collect([])->paginate(0);
    }

    protected function saveAttendanceHistory(Attendance $attendance, string $source, ?int $sourceId, ?string $changeReason, int $currentUserId): void
    {
        $attendance->histories()->create([
            'attendance_id' => $attendance->id,
            'user_id' => $attendance->user_id,
            'date' => $attendance->date,
            'actual_in' => $attendance->actual_in,
            'actual_out' => $attendance->actual_out,
            'status' => $attendance->status,
            'source' => $source,
            'source_id' => $sourceId,
            'change_reason' => $changeReason,
            'changed_at' => now(),
            'changed_by' => $currentUserId,
        ]);
    }

    /**
     * Update attendance record.
     */
    public function updateAttendance(Attendance $attendance, array $validatedData, int $currentUserId): Attendance
    {
        $this->saveAttendanceHistory($attendance, 'manual', null, null, $currentUserId);

        $attendance->update([
            'actual_in' => $validatedData['actual_in'],
            'actual_out' => $validatedData['actual_out'],
            'status' => $validatedData['status'],
            'updated_by' => $currentUserId,
        ]);

        return $attendance;
    }
}
