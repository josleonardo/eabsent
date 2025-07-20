<?php

namespace App\Services\Admins;

use App\Models\Level;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LevelService
{
    public function getLevels(string $userLevel, ?int $perPage = null): LengthAwarePaginator
    {
        $admin = Level::LEVEL_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userLevel == $admin) {
            return Level::paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createLevel(array $validatedData, int $currentUserId): Level
    {
        $level = Level::create([
            'name' => $validatedData['level_name'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $level;
    }

    public function updateLevel(Level $level, array $validatedData, int $currentUserId): Level
    {
        $level->update([
            'name' => $validatedData['level_name'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $level;
    }
}
