<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'nik',
        'nuptk',
        'fullname',
        'position',
        'address',
        'phone_number',
        'employment_start',
        'employment_end',
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
