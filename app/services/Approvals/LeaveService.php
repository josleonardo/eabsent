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
    private function selectQuery(bool $history)
    {
        $select = $history
            ? ['id', 'start_date', 'end_date', 'reason', 'status', 'approved_at', 'approved_by', 'created_at', 'created_by', 'updated_at']
            : ['id', 'start_date', 'end_date', 'reason', 'created_at', 'created_by'];

        return Leave::select($select)
            ->with([
                'requester.profile:user_id,first_name,last_name',
                'requester.levels:id,name',
                'requester.roles:id,name',
                'approver.profile:user_id,first_name,last_name',
                'files:user_id,fileable_id,fileable_type,path,filename,original_name,mime_type,size,category',
            ]);
    }

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

    /**
     * Get pending leave request based on the user's role and level.
     */
    public function getPending(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;
        $perPage = $perPage ?? config('constants.default_per_page');

        $query = $this->selectQuery(false);

        $query = $this->roleLevelFilters($query, $role, $level);

        return $query->whereNull('status')->latest()->paginate($perPage, ['*'], 'pending_page');
    }

    /**
     * Get leave request history based on the user's role and level.
     */
    public function getHistory(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;
        $perPage = $perPage ?? config('constants.default_per_page');

        $query = $this->selectQuery(true);

        $query = $this->roleLevelFilters($query, $role, $level);

        return $query->whereNotNull('status')->latest('approved_at')->paginate($perPage, ['*'], 'processed_page');
    }

    /**
     * Update leave request status and mark attendance as on leave if approved.
     */
    public function updateLeave(Leave $leave, array $validatedData, int $currentUserId): Leave
    {
        return DB::transaction(function () use ($leave, $validatedData, $currentUserId) {
            $attendanceService = app(AttendanceService::class);

            $leave->update([
                'status' => $validatedData['status'],
                'approved_at' => now(),
                'approved_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ]);

            if ($validatedData['status'] == Leave::STATUS_APPROVED) {
                $attendanceService->markOnLeave($leave->created_by, $leave->start_date, $leave->end_date, $leave->id, $currentUserId);
            }

            return $leave->refresh();
        });
    }

    /**
     * Revoke leave request and update attendance accordingly.
     */
    public function revokeLeave(Leave $leave, array $validatedData, int $currentUserId): Leave
    {
        if ($leave->status == Leave::STATUS_REVOKED) {
            throw new \Exception('Leave is already revoked.');
        }

        if ($leave->status != Leave::STATUS_APPROVED) {
            throw new \Exception('Only approved leaves can be revoked.');
        }

        return DB::transaction(function () use ($leave, $validatedData, $currentUserId) {
            $attendanceService = app(AttendanceService::class);

            $leave->update([
                'status' => $validatedData['status'],
                'updated_by' => $currentUserId,
            ]);

            $attendanceService->revokeOnLeave($leave->created_by, $leave->start_date, $leave->end_date, $leave->id, $currentUserId);

            return $leave->refresh();
        });
    }

    public function exportPending(User $user)
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $query = $this->selectQuery(false);

        $query = $this->roleLevelFilters($query, $role, $level);

        return $query->whereNull('status')->latest()->get();
    }

    public function exportHistory(User $user)
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $query = $this->selectQuery(true);

        $query = $this->roleLevelFilters($query, $role, $level);

        return $query->whereNotNull('status')->latest('approved_at')->get();
    }
}
