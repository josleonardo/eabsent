<?php

namespace App\Models;

use App\Traits\ActivityLogTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use ActivityLogTrait, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'requires_description',
        'active',
        'created_by',
        'updated_by',
    ];

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'leave_type_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->withDefault();
    }
    
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->withDefault();
    }
}
