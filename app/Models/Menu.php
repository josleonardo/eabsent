<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'menu_name',
        'menu_url',
        'type',
        'main_menu_id',
        'order',
        'icon',
        'active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_menu', 'menu_id', 'role_id')
            ->withTimestamps()
            ->withPivot('active', 'created_by', 'updated_by');
    }
}
