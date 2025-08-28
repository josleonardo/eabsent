<?php

namespace App\Services\Approvals;

use App\Models\Correction;
use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CorrectionService
{
    private function selectQuery(bool $history): Builder
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
     * Get pending correction request based on the user's role and level.
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
     * Get correction request history based on the user's role and level.
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
