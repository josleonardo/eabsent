<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AttendanceHistory extends Model
{
    /**
     * The change source constants.
     */
    const SOURCE_CORRECTION = 'correction';
    const SOURCE_LEAVE = 'leave';
    const SOURCE_MANUAL = 'manual';

    /**
     * The change reason constants.
     */
    const REASON_APPROVED = 'approved';
    const REASON_REVOKED = 'revoked';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'attendance_id',
        'actual_in',
        'actual_out',
        'status',
        'change_source',
        'reference_id',
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
