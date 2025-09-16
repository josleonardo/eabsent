<?php

namespace App\Services\Reports;

use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    private function selectQuery(): Builder
    {
        $select = ['user_id', 'role', 'action', 'model_type', 'model_id', 'changes', 'ip', 'user_agent', 'created_at', 'updated_at'];

        return ActivityLog::select($select)
            ->with([
                'users.profile:user_id,first_name,last_name',
            ]);
    }

    private function roleFilters(Builder $query, string $role, int $userId): Builder
    {
        if (in_array($role, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN])) {
            return $query;
        }

        if ($role == Role::ROLE_HEADMASTER) {
            return $query->where('role', Role::ROLE_HEADMASTER)->where('user_id', $userId);
        }

        return $query->whereRaw('1 = 0');
    }

    public function getLogs(User $user, ?int $perPage = null): LengthAwarePaginator
    {
        $userId = $user->id;
        $role = $user->roles->first()->name ?? null;
        $perPage = $perPage ?? config('constants.default_per_page');

        $query = $this->selectQuery();

        $query = $this->roleFilters($query, $role, $userId);

        return $query->latest('created_at')->paginate($perPage);
    }

    public function log(string $action, $model = null, $changes = null): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        $role = $user->roles->first()->name ?? null;

        if (! in_array($role, ['superadmin', 'admin', 'headmaster'])) {
            return;
        }

        if ($action === 'updated' && $model && $changes === null) {
            $changedKeys = array_keys($model->getChanges());
            $exclude = ['updated_at', 'created_at', 'deleted_at'];
            $filteredKeys = array_diff($changedKeys, $exclude);

            $before = array_intersect_key($model->getOriginal(), array_flip($filteredKeys));
            $after = array_intersect_key($model->getChanges(), array_flip($filteredKeys));

            $changes = [
                'before' => $before,
                'after' => $after,
            ];
        }

        ActivityLog::create([
            'user_id' => $user->id,
            'role' => $role,
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model?->id,
            'changes' => $changes,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
