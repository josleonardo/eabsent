<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Correction extends Model
{
    use ActivityLogTrait, HasFactory;

    /**
     * The status constants.
     */
    const STATUS_PENDING = 0;

    const STATUS_APPROVED = 1;

    const STATUS_REJECTED = 2;

    const STATUS_REVOKED = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'status',
        'processed_at',
        'processed_by',
        'updated_by',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const ACTION_STATUS_MAP = [
        'approve' => self::STATUS_APPROVED,
        'reject' => self::STATUS_REJECTED,
    ];

    /**
     * Relationship to the attendance.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }

    /**
     * Relationship to the user who created the correction.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

    /**
     * Relationship to the user who processed the correction request.
     */
    public function processer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by', 'id')->withDefault();
    }

    /**
     * Relationship to the user who last updated the correction request.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault();
    }

    public function canBeRevoked(): bool
    {
        if ($this->status !== self::STATUS_APPROVED || $this->processed_at === null) {
            return false;
        }

        $deadline = $this->processed_at
            ->copy()
            ->addDays(2);

        return now()->lt($deadline);
    }

    public function getRequestStatusLabelAttribute(): string
    {
        $status = config("constants.approve_status.{$this->status}.status");

        return $status ? __($status) : __('Unknown');
    }

    public function getRequestStatusColorAttribute(): ?string
    {
        return config("constants.approve_status.{$this->status}.color");
    }

    public function getCorrectStatusLabelAttribute(): string
    {
        if ($this->correct_status === null) {
            return '';
        }

        $status = config("constants.attendance_status.{$this->correct_status}.status");

        return $status ? __($status) : __('Unknown');
    }
}
