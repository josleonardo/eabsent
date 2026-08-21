<?php

namespace App\Services\Approvals;

use App\Models\Correction;
use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use App\Services\Reports\AttendanceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CorrectionService
{
    public function __construct(
        private AttendanceService $attendanceService
    ) {}

    private function roleLevelFilters(
        Builder $query,
        string $role,
        string $level
    ): Builder {
        if (in_array($role, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN])) {
            if ($level == Level::LEVEL_ADMIN) {
                return $query;
            } else {
                return $query->whereHas('requester.levels', function (Builder $q) use ($level) {
                    $q->where('name', $level);
                });
            }
        }

        if ($role == Role::ROLE_HEADMASTER) {
            return $query->whereHas('requester.roles', function (Builder $q) {
                $q->where('name', Role::ROLE_TEACHER);
            })->whereHas('requester.levels', function (Builder $q) use ($level) {
                $q->where('name', $level);
            });
        }

        return $query->whereRaw('1 = 0');
    }

    private function queryPending(User $user)
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $query = Correction::select('id', 'attendance_id', 'correct_in', 'correct_out', 'correct_status', 'description', 'created_at', 'created_by')
            ->with([
                'attendance:id,user_id,date,actual_in,actual_out,status',
                'attendance.users.profile:user_id,first_name,last_name',
                'attendance.users.levels:id,name',
                'requester.profile:user_id,first_name,last_name',
            ]);

        return $this->roleLevelFilters($query, $role, $level)->where('status', 0);
    }

    public function getPending(
        User $user,
        ?int $perPage = null
    ): LengthAwarePaginator {
        return $this->queryPending($user)
            ->latest()
            ->paginate($perPage ?? config('constants.default_per_page'));
    }

    public function exportPending(User $user)
    {
        return $this->queryPending($user)
            ->latest()
            ->get();
    }

    private function queryHistory(User $user)
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $query = Correction::with([
            'attendance:id,user_id,date',
            'attendance.users.profile:user_id,first_name,last_name',
            'attendance.users.levels:id,name',
            'processer.profile:user_id,first_name,last_name',
            'requester.profile:user_id,first_name,last_name',
            'updater.profile:user_id,first_name,last_name',
        ]);

        return $this->roleLevelFilters($query, $role, $level)->whereNot('status', 0);
    }

    public function getHistory(
        User $user,
        ?int $perPage = null
    ): LengthAwarePaginator {
        return $this->queryHistory($user)
            ->latest('processed_at')
            ->paginate($perPage ?? config('constants.default_per_page'));
    }

    public function exportHistory(User $user)
    {
        return $this->queryHistory($user)
            ->latest('processed_at')
            ->get();
    }

    /**
     * Update correction request status and correct attendance if approved.
     */
    public function updateCorrection(
        Correction $correction,
        string $action,
        int $currentUserId
    ): Correction {
        $status = Correction::ACTION_STATUS_MAP[$action]
            ?? throw new \InvalidArgumentException('Invalid action.');

        return DB::transaction(function () use ($correction, $status, $currentUserId) {
            if ($status == Correction::STATUS_APPROVED) {
                $this->attendanceService->correctAttendance(
                    $correction->attendance_id,
                    $correction->correct_in,
                    $correction->correct_out,
                    $correction->correct_status,
                    $correction->id,
                    $currentUserId
                );
            }

            $updated = Correction::whereKey($correction->id)
                ->where('status', Correction::STATUS_PENDING)
                ->update([
                    'status' => $status,
                    'processed_at' => now(),
                    'processed_by' => $currentUserId,
                    'updated_by' => $currentUserId,
                ]);

            if (! $updated) {
                throw new \RuntimeException('Correction request has already been processed.');
            }

            return $correction->refresh();
        });
    }

    /**
     * Revoke correction request and update attendance accordingly.
     */
    public function revokeCorrection(
        Correction $correction,
        int $currentUserId
    ): Correction {
        if ($correction->status !== Correction::STATUS_APPROVED) {
            throw new \RuntimeException('Only approved correction can be revoked.');
        }

        if (! $correction->canBeRevoked()) {
            throw new \RuntimeException('The revocation period has expired.');
        }

        return DB::transaction(function () use ($correction, $currentUserId) {
            $this->attendanceService->revokeCorrectedAttendance(
                $correction->attendance_id,
                $correction->id,
                $currentUserId
            );

            $revoked = Correction::whereKey($correction->id)
                ->where('status', Correction::STATUS_APPROVED)
                ->update([
                    'status' => Correction::STATUS_REVOKED,
                    'updated_by' => $currentUserId,
                ]);

            if (! $revoked) {
                throw new \RuntimeException('Correction request has already been processed.');
            }

            return $correction->refresh();
        });
    }
}
