<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use ActivityLogTrait, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'school_location_id',
        'schedule_group_id',
        'language',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'user_level', 'user_id', 'level_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }
    
    public function schoolLocation(): BelongsTo
    {
        return $this->belongsTo(SchoolLocation::class, 'school_location_id', 'id');
    }

    public function scheduleGroup(): BelongsTo
    {
        return $this->belongsTo(ScheduleGroup::class, 'schedule_group_id', 'id');
    }

    public function getFullNameAttribute()
    {
        if ($this->profile) {
            $first = $this->profile->first_name;
            $last = $this->profile->last_name;
            $full = trim(($first ?? '').' '.($last ?? ''));
            if ($full !== '') {
                return $full;
            }
        }

        return $this->id ? $this->id : 'Unknown User';
    }
}
