<?php

namespace App\Services\Approvals;

use App\Models\Correction;
use App\Models\Level;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CorrectionService
{
    /**
     * Default pagination limit.
     */
    private const DEFAULT_PER_PAGE = 10;

    /**
     * Get pending correction request based on the user's role and level.
     */
    public static function getPending(string $role, string $level, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? self::DEFAULT_PER_PAGE;

        $query = self::selectQuery(false);

        $query = self::roleLevelFilters($query, $role, $level);

        return $query->whereNull('status')->latest()->paginate($perPage, ['*'], 'pending_page');
    }

    /**
     * Get correction request history based on the user's role and level.
     */
    public static function getHistory(string $role, string $level, ?int $perPage = null): LengthAwarePaginator
    {
        $perPage = $perPage ?? self::DEFAULT_PER_PAGE;

        $query = self::selectQuery(true);

        $query = self::roleLevelFilters($query, $role, $level);

        return $query->whereNotNull('status')->latest('approved_at')->paginate($perPage, ['*'], 'processed_page');
    }

    /**
     * Select query builder for corrections.
     */
    private static function selectQuery(bool $history): Builder
    {
        $select = $history
            ? ['id', 'date', 'actual_in', 'actual_out', 'reason', 'status', 'approved_at', 'approved_by', 'created_at', 'created_by', 'updated_at']
            : ['id', 'date', 'actual_in', 'actual_out', 'reason', 'created_at', 'created_by'];

        return Correction::select($select)
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

    /**
     * Update correction request.
     */
    public function updateCorrection(Correction $correction, array $validatedData, int $currentUserId): Correction
    {
        $correction->update([
            'status' => $validatedData['status'],
            'approved_at' => now(),
            'approved_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $correction;
    }
}
