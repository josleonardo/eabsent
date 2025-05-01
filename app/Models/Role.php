<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_name',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'role_menu', 'role_id', 'menu_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }
}
