<?php

namespace App\Services\Approvals;

use App\Models\Leave;
use App\Models\Level;
use App\Models\Role;
use App\Services\Reports\AttendanceService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    /**
     * Default pagination limit.
     */
    private const DEFAULT_PER_PAGE = 10;

    /**
     * Get pending leave request based on the user's role and level.
     */
    public static function getPending(string $role, string $level, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? self::DEFAULT_PER_PAGE;

        $query = self::selectQuery(false);

        $query = self::roleLevelFilters($query, $role, $level);

        return $query->whereNull('status')->latest()->paginate($perPage, ['*'], 'pending_page');
    }

    /**
     * Get leave request history based on the user's role and level.
     */
    public static function getHistory(string $role, string $level, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? self::DEFAULT_PER_PAGE;

        $query = self::selectQuery(true);

        $query = self::roleLevelFilters($query, $role, $level);

        return $query->whereNotNull('status')->latest('approved_at')->paginate($perPage, ['*'], 'processed_page');
    }

    /**
     * Select query builder for leaves.
     */
    private static function selectQuery(bool $history): Builder
    {
        $select = $history
            ? ['id', 'start_date', 'end_date', 'reason', 'file_path', 'status', 'approved_at', 'approved_by', 'created_at', 'created_by', 'updated_at']
            : ['id', 'start_date', 'end_date', 'reason', 'file_path', 'created_at', 'created_by'];

        return Leave::select($select)
            ->with([
                'requester.profile:user_id,first_name,last_name',
                'requester.levels:id,name',
                'requester.roles:id,name',
                'approver.profile:user_id,first_name,last_name',
            ]);
    }

    /**
     * Apply role and level filters to the query.
     */
    private static function roleLevelFilters(Builder $query, string $role, string $level): Builder
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

        // Default: return empty result
        return $query->whereRaw('1 = 0');
    }

    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Update leave request status and mark attendance as on leave if approved.
     */
    public function updateLeave(Leave $leave, array $validatedData, int $currentUserId): Leave
    {
        return DB::transaction(function () use ($leave, $validatedData, $currentUserId) {
            $leave->update([
                'status' => $validatedData['status'],
                'approved_at' => now(),
                'approved_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ]);

            if ($validatedData['status'] == Leave::STATUS_APPROVED) {
                $this->attendanceService->markOnLeave($leave->created_by, $leave->start_date, $leave->end_date, $leave->id, $currentUserId);
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
            $leave->update([
                'status' => $validatedData['status'],
                'updated_by' => $currentUserId,
            ]);

            $this->attendanceService->revokeOnLeave($leave->created_by, $leave->start_date, $leave->end_date, $leave->id, $currentUserId);

            return $leave->refresh();
        });
    }
}
