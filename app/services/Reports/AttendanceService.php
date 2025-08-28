<?php

namespace App\Services\Reports;

use App\Models\Attendance;
use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    private function selectQuery(): Builder
    {
        $select = ['id', 'user_id', 'date', 'sched_in', 'sched_out', 'actual_in', 'actual_out', 'status', 'updated_at', 'updated_by'];

        return Attendance::select($select)
            ->with([
                'users.profile:user_id,first_name,last_name',
                'users.levels:id,name',
                'users.roles:id,name'
            ]);
    }

    private function roleLevelFilters(Builder $query, string $role, string $level): Builder
    {
        if (in_array($role, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN])) {
            if ($level == Level::LEVEL_ADMIN) {
                return $query;
            } else {
                return $query->whereHas('users.levels', function (Builder $q) use ($level) {
                    $q->where('name', $level);
                });
            }
        }

        if ($role == Role::ROLE_HEADMASTER) {
            return $query->whereHas('users.roles', function (Builder $q) {
                $q->where('name', Role::ROLE_TEACHER);
            })->whereHas('users.levels', function (Builder $q) use ($level) {
                $q->where('name', $level);
            });
        }

        return $query->whereRaw('1 = 0');
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
     * Get attendances based on the user's role and level.
     */
    public function getAttendances(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;
        $perPage = $perPage ?? config('constants.default_per_page');

        $query = $this->selectQuery();

        $query = $this->roleLevelFilters($query, $role, $level);

        return $query->latest('date')->paginate($perPage);
    }

    /**
     * Update attendance record and save history.
     */
    public function updateAttendance(Attendance $attendance, array $validatedData, int $currentUserId): Attendance
    {
        return DB::transaction(function () use ($attendance, $validatedData, $currentUserId) {
            $oldData = $attendance->replicate();
            $oldData->id = $attendance->id;

            $updated = $attendance->update([
                'actual_in' => $validatedData['actual_in'],
                'actual_out' => $validatedData['actual_out'],
                'status' => $validatedData['status'],
                'updated_by' => $currentUserId,
            ]);

            if ($updated) {
                $this->saveAttendanceHistory($oldData, 'manual', null, null, $currentUserId);
            }

            return $attendance->refresh();
        });
    }

    public function markOnLeave(int $userId, string $startDate, string $endDate, int $sourceId, int $currentUserId): void
    {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        foreach ($attendances as $attendance) {
            $oldData = $attendance->replicate();
            $oldData->id = $attendance->id;

            $updated = $attendance->update([
                'status' => 3,
                'updated_by' => $currentUserId,
            ]);

            if ($updated) {
                $this->saveAttendanceHistory($oldData, 'leave', $sourceId, 'approved', $currentUserId);
            }
        }
    }

    public function revokeOnLeave(int $userId, string $startDate, string $endDate, int $sourceId, int $currentUserId): void
    {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 3)
            ->with('histories')
            ->get();

        foreach ($attendances as $attendance) {
            $oldData = $attendance->replicate();
            $oldData->id = $attendance->id;

            $previousHistories = $attendance->histories()
                ->where('source', 'leave')
                ->where('source_id', $sourceId)
                ->orderByDesc('changed_at')
                ->first();

            $previousStatus = $previousHistories->status ?? 1;

            $updated = $attendance->update([
                'status' => $previousStatus,
                'updated_by' => $currentUserId,
            ]);

            if ($updated) {
                $this->saveAttendanceHistory($oldData, 'leave', $sourceId, 'revoked', $currentUserId);
            }
        }
    }

    public function exportAttendances(User $user)
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $query = $this->selectQuery();

        $query = $this->roleLevelFilters($query, $role, $level);

        return $query->latest('date')->get();
    }
}
