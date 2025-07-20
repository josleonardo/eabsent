<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'attendance_id',
        'user_id',
        'date',
        'actual_in',
        'actual_out',
        'status',
        'source',
        'source_id',
        'change_reason',
        'changed_at',
        'changed_by',
    ];

    public $timestamps = false;

    /**
     * Relationship to the attendance.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }
}
