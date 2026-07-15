<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Leave extends Model
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
        'start_date' => 'date',
        'end_date' => 'date',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public const ACTION_STATUS_MAP = [
        'approve' => self::STATUS_APPROVED,
        'reject' => self::STATUS_REJECTED,
    ];

    /**
     * Relationship to the user who created the leave request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withDefault();
    }

    /**
     * Relationship to the user who processed the leave request.
     */
    public function processer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by', 'id')->withDefault();
    }

    /**
     * Get all of the leave's files.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function leaveType(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }

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
            ->addDays(2)
            ->min($this->start_date->copy()->startOfDay());

        return now()->lt($deadline);
    }
}
