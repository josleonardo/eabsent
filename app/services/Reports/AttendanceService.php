<?php

namespace App\Services\Reports;

use App\Models\Attendance;
use App\Models\AttendanceHistory;
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
                'users.roles:id,name',
            ]);
    }

    private function roleLevelFilters(
        Builder $query,
        string $role,
        string $level
    ): Builder {
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

    /**
     * Get attendances based on the user's role and level.
     */
    public function getAttendances(
        User $user,
        ?int $perPage = null
    ): LengthAwarePaginator {
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
    public function updateAttendance(
        Attendance $attendance,
        array $validatedData,
        int $currentUserId
    ): Attendance {
        return DB::transaction(function () use ($attendance, $validatedData, $currentUserId) {
            $oldData = $attendance->replicate();

            $updated = $attendance->update([
                'actual_in' => $validatedData['actual_in'],
                'actual_out' => $validatedData['actual_out'],
                'status' => $validatedData['status'],
                'updated_by' => $currentUserId,
            ]);

            if ($updated) {
                $this->saveAttendanceHistory(
                    $attendance->id,
                    $oldData,
                    AttendanceHistory::SOURCE_MANUAL,
                    null,
                    null,
                    $currentUserId
                );
            }

            return $attendance->refresh();
        });
    }

    public function markOnLeave(
        int $userId,
        string $startDate,
        string $endDate,
        int $referenceId,
        int $currentUserId
    ): void {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        foreach ($attendances as $attendance) {
            $oldData = $attendance->replicate();

            $updated = $attendance->update([
                'status' => 3,
                'updated_by' => $currentUserId,
            ]);

            if ($updated) {
                $this->saveAttendanceHistory(
                    $attendance->id,
                    $oldData,
                    AttendanceHistory::SOURCE_LEAVE,
                    $referenceId,
                    AttendanceHistory::REASON_APPROVED,
                    $currentUserId
                );
            }
        }
    }

    public function revokeOnLeave(
        int $userId,
        string $startDate,
        string $endDate,
        int $referenceId,
        int $currentUserId
    ): void {
        $attendances = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 3)
            ->with('histories')
            ->get();

        foreach ($attendances as $attendance) {
            $oldData = $attendance->replicate();

            $previousHistories = $attendance->histories()
                ->where('change_source', AttendanceHistory::SOURCE_LEAVE)
                ->where('reference_id', $referenceId)
                ->where('change_reason', AttendanceHistory::REASON_APPROVED)
                ->orderByDesc('changed_at')
                ->firstOrFail();

            $previousStatus = $previousHistories->status ?? 1;

            $updated = $attendance->update([
                'status' => $previousStatus,
                'updated_by' => $currentUserId,
            ]);

            if ($updated) {
                $this->saveAttendanceHistory(
                    $attendance->id,
                    $oldData,
                    AttendanceHistory::SOURCE_LEAVE,
                    $referenceId,
                    AttendanceHistory::REASON_REVOKED,
                    $currentUserId
                );
            }
        }
    }

    public function correctAttendance(
        int $attendanceId,
        ?string $correctIn,
        ?string $correctOut,
        ?int $correctStatus,
        int $referenceId,
        int $currentUserId
    ): void {
        $attendance = Attendance::findOrFail($attendanceId);

        $this->saveAttendanceHistory(
            $attendanceId,
            $attendance,
            AttendanceHistory::SOURCE_CORRECTION,
            $referenceId,
            AttendanceHistory::REASON_APPROVED,
            $currentUserId
        );

        $attendance->update([
            'actual_in' => $correctIn ?? $attendance->actual_in,
            'actual_out' => $correctOut ?? $attendance->actual_out,
            'status' => $correctStatus ?? $attendance->status,
            'updated_by' => $currentUserId,
        ]);
    }

    public function revokeCorrectedAttendance(
        int $attendanceId,
        int $referenceId,
        int $currentUserId
    ): void {
        $attendance = Attendance::findOrFail($attendanceId);
        $previousHistory = $attendance->histories()
            ->where('change_source', AttendanceHistory::SOURCE_CORRECTION)
            ->where('reference_id', $referenceId)
            ->where('change_reason', AttendanceHistory::REASON_APPROVED)
            ->orderByDesc('changed_at')
            ->firstOrFail();

        $this->saveAttendanceHistory(
            $attendanceId,
            $attendance,
            AttendanceHistory::SOURCE_CORRECTION,
            $referenceId,
            AttendanceHistory::REASON_REVOKED,
            $currentUserId
        );

        $attendance->update([
            'actual_in' => $previousHistory->actual_in ?? $attendance->actual_in,
            'actual_out' => $previousHistory->actual_out ?? $attendance->actual_out,
            'status' => $previousHistory->status ?? $attendance->status,
            'updated_by' => $currentUserId,
        ]);
    }

    protected function saveAttendanceHistory(
        int $attendanceId,
        Attendance $attendance,
        string $changeSource,
        ?int $referenceId,
        ?string $changeReason,
        int $currentUserId
    ): AttendanceHistory {
        return $attendance->histories()->create([
            'attendance_id' => $attendanceId,
            'actual_in' => $attendance->actual_in,
            'actual_out' => $attendance->actual_out,
            'status' => $attendance->status,
            'change_source' => $changeSource,
            'reference_id' => $referenceId,
            'change_reason' => $changeReason,
            'changed_at' => now(),
            'changed_by' => $currentUserId,
        ]);
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
