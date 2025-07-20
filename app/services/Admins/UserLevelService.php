<?php

namespace App\Services\Admins;

use App\Models\Level;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserLevelService
{
    public function getUsersLevels(string $userLevel, ?int $perPage = null): LengthAwarePaginator
    {
        $admin = Level::LEVEL_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userLevel == $admin) {
            return User::select('id', 'email', 'username')->with('levels:id,name')->paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function updateUserLevel(array $validatedData, User $user, int $currentUserId)
    {
        if (! empty($validatedData['level'])) {
            $user->levels()->syncWithPivotValues(
                [$validatedData['level']],
                [
                    'active' => $validatedData['active'],
                    'created_by' => $currentUserId,
                    'updated_by' => $currentUserId,
                ]
            );
        } else {
            $user->levels()->detach();
        }
    }
}
