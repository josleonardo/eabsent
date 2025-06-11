<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Level extends Model
{
    /** @use HasFactory<\Database\Factories\LevelFactory> */
    use HasFactory;

    /**
     * The level constants.
     */
    const LEVEL_ADMIN = 'admin';

    const LEVEL_KINDERGARTEN = 'kindergarten';

    const LEVEL_ELEMENTARY = 'elementary';

    const LEVEL_MIDDLESCHOOL = 'middle school';

    const LEVEL_HIGHSCHOOL = 'high school';

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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_level', 'level_id', 'user_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }
}
