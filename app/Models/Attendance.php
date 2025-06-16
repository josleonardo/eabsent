<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'actual_in',
        'actual_out',
        'status',
        'active',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationship to the user.
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getDayNameAttribute()
    {
        return isset($this->date) ? Carbon::parse($this->date)->format('l') : null;
    }

    public function getFormattedActualInAttribute()
    {
        return isset($this->actual_in) ? Carbon::parse($this->actual_in)->format('H:i') : null;
    }

    public function getFormattedActualOutAttribute()
    {
        return isset($this->actual_out) ? Carbon::parse($this->actual_out)->format('H:i') : null;
    }
}
