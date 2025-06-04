<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class UserProfile extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nik',
        'nuptk',
        'first_name',
        'last_name',
        'position',
        'address',
        'phone_number',
        'employment_start',
        'employment_end',
        'avatar',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $primaryKey = 'user_id';

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
