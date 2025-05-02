<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'approve_status',
        'approved_at',
        'approved_by',
        'active',
        'updated_by',
    ];

    /**
     * Relationship to the user who created the correction.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Relationship to the user who approve the correction.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    /**
     * Get pending leaves.
     *
     * @param $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPending($user)
    {
        $userRole = $user->roles->first()->id ?? null;
        $userLevel = $user->levels->first()->id ?? null;

        $query = self::select(['id', 'start_date', 'end_date', 'reason', 'file_path', 'created_at', 'created_by']);

        // If the user role is a superadmin or admin
        if (in_array($userRole, [1, 2])) {
            if ($userLevel == 1) {
                // If the user level is admin, get all leaves
                return $query->where('approve_status', null)->latest()->paginate(10, ['*'], 'pending_page');
            } else {
                // Otherwise, get leaves where level_id matches the user's level_id
                return $query->whereHas('requester.levels', function (Builder $q) use ($userLevel) {
                    $q->where('level_id', $userLevel)
                        ->where('approve_status', null);
                })->latest()->paginate(10, ['*'], 'pending_page');
            }
        }

        // If the user role is headmaster, get attendances where role is teacher and level matches the user's level
        if ($userRole == 3) {
            return $query->whereHas('requester.roles', function (Builder $q) {
                $q->where('role_id', 4);
            })
                ->whereHas('requester.levels', function (Builder $q) use ($userLevel) {
                    $q->where('level_id', $userLevel)
                        ->where('approve_status', null);
                })->latest()->paginate(10, ['*'], 'pending_page');
        }

        // Default case: return an empty result if no conditions are met
        return $query->whereRaw('1 = 0')->paginate(10, ['*'], 'pending_page');
    }

    /**
     * Get processed leaves.
     *
     * @param $user
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getHistory($user)
    {
        $userRole = $user->roles->first()->id ?? null;
        $userLevel = $user->levels->first()->id ?? null;

        $query = self::select(['id', 'start_date', 'end_date', 'reason', 'file_path', 'approve_status', 'approved_at', 'approved_by', 'created_at', 'created_by', 'updated_at']);

        // If the user role is a superadmin or admin
        if (in_array($userRole, [1, 2])) {
            if ($userLevel == 1) {
                // If the user level is admin, get all processed leaves
                return $query->where('approve_status', '!=', null)->latest('approved_at')->paginate(10, ['*'], 'processed_page');
            } else {
                // Get processed leaves where level matches the current user level
                return $query->whereHas('requester.levels', function (Builder $q) use ($userLevel) {
                    $q->where('level_id', $userLevel)
                        ->where('approve_status', '!=', null);
                })->latest('approved_at')->paginate(10, ['*'], 'processed_page');
            }
        }

        // If the user role is headmaster
        if ($userRole == 3) {
            // Get processed leaves where role is teacher
            return $query->whereHas('requester.roles', function (Builder $q) {
                $q->where('role_id', 4);
            })
                // and level matches the current user level
                ->whereHas('requester.levels', function (Builder $q) use ($userLevel) {
                    $q->where('level_id', $userLevel)
                        ->where('approve_status', '!=', null);;
                })->latest('approved_at')->paginate(10, ['*'], 'processed_page');
        }

        // Default case: return an empty result if no conditions are met
        return $query->whereRaw('1 = 0')->paginate(10, ['*'], 'processed_page');
    }
}
