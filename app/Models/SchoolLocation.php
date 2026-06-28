<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolLocation extends Model
{
    use ActivityLogTrait, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'key',
        'latitude',
        'longitude',
        'radius',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class, 'school_id', 'id');
    }
}
