<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
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

    public function role(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withTimestamps()
            ->withPivot('id', 'active', 'created_by', 'updated_by')
            ->limit(1); // Ensures only one role per user
    }

    public function roleActive(array $allowedRoles)
    {
        return $this->role()
            ->wherePivot('active', 1) // role_user table
            ->whereIn('role_id', $allowedRoles)
            ->where('roles.active', 1) // roles table
            ->first();
    }
    
    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'level_user', 'user_id', 'level_id')
            ->withTimestamps()
            ->withPivot('id', 'active', 'created_by', 'updated_by');
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'schedule_user', 'user_id', 'schedule_id')
            ->withTimestamps()
            ->withPivot('id', 'active', 'created_by', 'updated_by');
    }
}
