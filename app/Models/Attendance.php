<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'real_check_in',
        'real_check_out',
        'status',
        'updated_by',
    ];

    /**
     * Relationship to the user.
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get attendances based on the user's role and level.
     *
     * @param int $userLevel
     * @param int $userRole
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getAttendances($user)
    {
        $userRole = $user->roles->first()->id ?? null;
        $userLevel = $user->levels->first()->id ?? null;

        $query = self::select(['id', 'user_id', 'day_of_week', 'date', 'sched_check_in', 'sched_check_out', 'real_check_in', 'real_check_out', 'status', 'updated_at', 'updated_by'])
            ->with(['users.profile:user_id,first_name,last_name', 'users.levels:id,name', 'users.roles:id,name']);

        // If the user role is a superadmin or admin
        if (in_array($userRole, [1, 2])) {
            if ($userLevel == 1) {
                // If level admin, get all attendances
                return $query->latest('date')->paginate(25);
            } else {
                // If not level admin, get attendances where level matches the user's level
                return $query->whereHas('users.levels', function (Builder $q) use ($userLevel) {
                    $q->where('level_id', $userLevel);
                })->latest('date')->paginate(25);
            }
        }

        // If the user role is headmaster, get attendances where role is teacher and level matches the user's level
        if ($userRole == 3) {
            return $query->whereHas('users.roles', function (Builder $q) {
                $q->where('role_id', 4);
            })
                ->whereHas('users.levels', function (Builder $q) use ($userLevel) {
                    $q->where('level_id', $userLevel);
                })->latest('date')->paginate(25);
        }

        // Default case: return an empty result if no conditions are met
        return $query->whereRaw('1 = 0')->paginate(25);
    }
}
