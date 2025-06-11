<?php

namespace App\Services\Reports;

use App\Models\Attendance;
use App\Models\Level;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class AttendanceService
{
    /**
     * Get attendances based on the user's role and level.
     */
    public function getAttendances(User $user): LengthAwarePaginator
    {
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $query = Attendance::select(['id', 'user_id', 'date', 'sched_in', 'sched_out', 'actual_in', 'actual_out', 'status', 'updated_at', 'updated_by'])
            ->with(['users.profile:user_id,first_name,last_name', 'users.levels:id,name', 'users.roles:id,name']);

        if (in_array($role, [Role::ROLE_SUPERADMIN, Role::ROLE_ADMIN])) {
            if ($level == Level::LEVEL_ADMIN) {
                return $query->latest('date')->paginate(25);
            } else {
                return $query->whereHas('users.levels', function (Builder $q) use ($level) {
                    $q->where('name', $level);
                })->latest('date')->paginate(25);
            }
        }

        if ($role == Role::ROLE_HEADMASTER) {
            return $query->whereHas('users.roles', function (Builder $q) {
                $q->where('name', Role::ROLE_TEACHER);
            })
                ->whereHas('users.levels', function (Builder $q) use ($level) {
                    $q->where('name', $level);
                })->latest('date')->paginate(25);
        }

        // Default case: return an empty result if no conditions are met
        return $query->collect([])->paginate(0);
    }
}
