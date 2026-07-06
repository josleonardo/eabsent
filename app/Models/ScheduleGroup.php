<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleGroup extends Model
{
    use ActivityLogTrait, HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'group_schedule', 'schedule_group_id', 'schedule_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'schedule_group_id', 'id');
    }
}
