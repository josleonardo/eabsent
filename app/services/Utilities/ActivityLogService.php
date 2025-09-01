<?php

namespace App\Services\Utilities;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function log(string $action, $model = null, $changes = null): void
    {
        $user = Auth::user();
        $role = $user->roles->first()->name ?? null;

        if (! $user) {
            return;
        }

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
