<?php

namespace App\Services\Approvals;

use App\Models\Leave;
use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use App\Services\Reports\AttendanceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    private function roleLevelFilters(Builder $query, string $role, string $level): Builder
    {
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

        $query = Leave::select('id', 'user_id', 'leave_type_id', 'start_date', 'end_date', 'description', 'created_at', 'created_by')
            ->with([
                'user.profile:user_id,first_name,last_name',
                'user.levels:id,name',
                'user.roles:id,name',
                'files:user_id,fileable_id,fileable_type,path,filename,original_name,mime_type,size,category',
                'requester.profile:user_id,first_name,last_name',
            ]);

        return $this->roleLevelFilters($query, $role, $level)->where('status', 0);
    }

    public function getPending(User $user, ?int $perPage = null): LengthAwarePaginator
    {
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

        $query = Leave::with([
            'user.profile:user_id,first_name,last_name',
            'user.levels:id,name',
            'user.roles:id,name',
            'processer.profile:user_id,first_name,last_name',
            'files:user_id,fileable_id,fileable_type,path,filename,original_name,mime_type,size,category',
            'requester.profile:user_id,first_name,last_name',
            'updater.profile:user_id,first_name,last_name',
        ]);

        return $this->roleLevelFilters($query, $role, $level)->whereNot('status', 0);
    }

    public function getHistory(User $user, ?int $perPage = null): LengthAwarePaginator
    {
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
     * Update leave request status and mark attendance as on leave if approved.
     */
    public function updateLeave(Leave $leave, string $action, int $currentUserId): Leave
    {
        $status = Leave::ACTION_STATUS_MAP[$action]
            ?? throw new \InvalidArgumentException('Invalid action.');

        return DB::transaction(function () use ($leave, $status, $currentUserId) {
            $attendanceService = app(AttendanceService::class);

            $updated = Leave::whereKey($leave->id)
                ->where('status', Leave::STATUS_PENDING)
                ->update([
                    'status' => $status,
                    'processed_at' => now(),
                    'processed_by' => $currentUserId,
                    'updated_by' => $currentUserId,
                ]);

            if (! $updated) {
                throw new \RuntimeException('Leave request has already been processed.');
            }

            if ($status == Leave::STATUS_APPROVED) {
                $attendanceService->markOnLeave($leave->user_id, $leave->start_date, $leave->end_date, $leave->id, $currentUserId);
            }

            return $leave->refresh();
        });
    }

    /**
     * Revoke leave request and update attendance accordingly.
     */
    public function revokeLeave(Leave $leave, int $currentUserId): Leave
    {
        if ($leave->status !== Leave::STATUS_APPROVED) {
            throw new \RuntimeException('Only approved leaves can be revoked.');
        }

        if (! $leave->canBeRevoked()) {
            throw new \RuntimeException('The revocation period has expired.');
        }

        return DB::transaction(function () use ($leave, $currentUserId) {
            $attendanceService = app(AttendanceService::class);

            $revoked = Leave::whereKey($leave->id)
                ->where('status', Leave::STATUS_APPROVED)
                ->update([
                    'status' => Leave::STATUS_REVOKED,
                    'updated_by' => $currentUserId,
                ]);

            if (! $revoked) {
                throw new \RuntimeException('Leave request has already been processed.');
            }

            $attendanceService->revokeOnLeave($leave->user_id, $leave->start_date, $leave->end_date, $leave->id, $currentUserId);

            return $leave->refresh();
        });
    }
}
