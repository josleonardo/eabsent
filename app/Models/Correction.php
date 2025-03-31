<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correction extends Model
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
        'updated_at',
        'updated_by',
    ];

    /**
     * Get corrections based on the user's level.
     *
     * @param int $userLevelId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getCorrections($userLevelId)
    {
        if ($userLevelId == 1) {
            // If the user level is 1 or 2, get all corrections
            return self::where('approve_status', null)->paginate(10);
        }

        // Otherwise, get corrections where level_id matches the user's level_id
        return self::where([
            ['level_id', $userLevelId],
            ['approve_status', null],
        ])->paginate(10);
    }
}
