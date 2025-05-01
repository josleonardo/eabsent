<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    /** @use HasFactory<\Database\Factories\ScheduleFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'schedule_name',
        'day_of_week',
        'check_in_time',
        'check_out_time',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_schedule', 'schedule_id', 'user_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }
}
