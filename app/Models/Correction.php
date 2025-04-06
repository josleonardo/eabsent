<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correction extends Model
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
     * Get pending corrections based on the user's level.
     *
     * @param int $userLevelId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getPendingCorrections($userLevelId)
    {
        if ($userLevelId == 1) {
            // If the user level is super admin, get all corrections
            return self::where('approve_status', null)->latest()->paginate(10);
        }

        // Otherwise, get corrections where level_id matches the user's level_id
        return self::where([
            ['level_id', $userLevelId],
            ['approve_status', null],
        ])->latest()->paginate(10);
    }

    /**
     * Get processed corrections based on the user's level.
     *
     * @param int $userLevelId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getProcessedCorrections($userLevelId)
    {
        if ($userLevelId == 1) {
            // If the user level is super admin, get all corrections
            return self::where('approve_status', '!=', null)->latest('approved_at')->paginate(10);
        }

        // Otherwise, get corrections where level_id matches the user's level_id
        return self::where([
            ['level_id', $userLevelId],
            ['approve_status', '!=', null],
        ])->latest('approved_at')->paginate(10);
    }
}
