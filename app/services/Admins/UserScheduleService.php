<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserScheduleService
{
    public function getUsersSchedules(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        $query = DB::table('user_schedule as us')
            ->join('users as u', 'us.user_id', '=', 'u.id')
            ->join('user_profiles as up', 'up.user_id', '=', 'u.id')
            ->join('schedules as s', 'us.schedule_id', '=', 's.id')
            ->select(
                'u.id as user_id',
                'up.first_name',
                'up.last_name',
                's.id as schedule_id',
                's.day_of_week',
                's.check_in_time',
                's.check_out_time',
                'us.active',
                'us.created_at',
                'us.created_by',
                'us.updated_at',
                'us.updated_by'
            )
            ->orderBy('u.id')
            ->orderBy('s.day_of_week');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return $query->paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createUserSchedules(array $validatedData, User $user, int $currentUserId)
    {
        if ($user->schedules()->where('schedule_id', $validatedData['schedule'])->exists()) {
            return back()->withErrors(['schedule' => 'This user already has the selected schedule.']);
        } else {
            $user->schedules()->attach($validatedData['schedule'], [
                'active' => $validatedData['active'],
                'created_by' => $currentUserId,
                'updated_by' => $currentUserId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function updateUserSchedules(array $validatedData, string $userId, string $scheduleId, int $currentUserId)
    {
        if (! empty($validatedData['schedule'])) {
            DB::table('user_schedule')
                ->where('user_id', $userId)
                ->where('schedule_id', $scheduleId)
                ->update([
                    'schedule_id' => $validatedData['schedule'],
                    'active' => $validatedData['active'],
                    'updated_by' => $currentUserId,
                    'updated_at' => now(),
                ]);
        }
    }
}
