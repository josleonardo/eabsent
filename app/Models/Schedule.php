<?php

namespace App\Models;

use Carbon\Carbon;
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
        'group',
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

    public function getDayNameAttribute()
    {
        $days = config('constants.days');

        return isset($this->day_of_week) ? __($days[$this->day_of_week]) : null;
    }

    public function getFormattedCheckInAttribute()
    {
        return isset($this->check_in_time) ? Carbon::parse($this->check_in_time)->format('H:i') : null;
    }

    public function getFormattedCheckOutAttribute()
    {
        return isset($this->check_out_time) ? Carbon::parse($this->check_out_time)->format('H:i') : null;
    }
}
