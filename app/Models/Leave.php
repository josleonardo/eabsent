<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
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
     * Get pending leaves based on the user's level.
     *
     * @param int $userLevelId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPendingLeaves($userLevelId)
    {
        if ($userLevelId == 1) {
            // If the user level is 1, get all leaves
            return self::where('approve_status', null)->latest()->paginate(10);
        }

        // Otherwise, get leaves where level_id matches the user's level_id
        return self::where([
            ['level_id', $userLevelId],
            ['approve_status', null],
        ])->latest()->paginate(10);
    }

    /**
     * Get processed leaves based on the user's level.
     *
     * @param int $userLevelId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getProcessedLeaves($userLevelId)
    {
        if ($userLevelId == 1) {
            // If the user level is 1, get all leaves
            return self::where('approve_status', '!=', null)->latest('approved_at')->paginate(10);
        }

        // Otherwise, get leaves where level_id matches the user's level_id
        return self::where([
            ['level_id', $userLevelId],
            ['approve_status', '!=', null],
        ])->latest('approved_at')->paginate(10);
    }
}
