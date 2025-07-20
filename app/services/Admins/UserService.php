<?php

namespace App\Services\Admins;

use App\Models\Role;
use App\Models\Schedule;
use App\Models\User;
use App\Services\Settings\AvatarService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $avatarService;

    public function __construct(AvatarService $avatarService)
    {
        $this->avatarService = $avatarService;
    }

    public function getUsers(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin) {
            return User::paginate($perPage);
        }

        if ($userRole == $admin) {
            return User::whereHas('roles', function ($query) use ($superAdmin) {
                $query->whereNot('name', $superAdmin);
            })
                ->with('roles:id,name')
                ->paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    /**
     * Create a new user with the provided validated data and default data.
     */
    public function createUser(array $validatedData, int $currentUserId): User
    {
        $defaultData = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        return DB::transaction(function () use ($validatedData, $defaultData) {
            $user = User::create(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
                'password' => Hash::make($validatedData['password']),
            ], $defaultData));

            $user->profile()->create(array_merge([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'nik' => $validatedData['nik'],
                'nuptk' => $validatedData['nuptk'],
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'employment_start' => $validatedData['employment_start'],
                'employment_end' => $validatedData['employment_end'],
            ], $defaultData));

            // If avatar uploaded, store the avatar
            if (isset($validatedData['avatar']) && $validatedData['avatar']->isValid()) {
                $path = $this->avatarService->upload($validatedData['avatar'], $user->id);

                $user->profile()->update(['avatar' => $path]);
            }

            if (! empty($validatedData['role'])) {
                $user->roles()->attach($validatedData['role'], $defaultData);
            }

            if (! empty($validatedData['level'])) {
                $user->levels()->attach($validatedData['level'], $defaultData);
            }

            $scheduleIds = [];
            if (! empty($validatedData['schedule'])) {
                $scheduleIds = explode(',', $validatedData['schedule']);

                // Validate all IDs exist in DB
                $validIds = Schedule::whereIn('id', $scheduleIds)->pluck('id')->toArray();
                if (count($validIds) !== count($scheduleIds)) {
                    throw new \Exception('One or more selected schedules are invalid.');
                }

                $user->schedules()->attach($scheduleIds, $defaultData);
            }

            return $user;
        });
    }

    /**
     * Update an existing user with the provided validated data and default data.
     */
    public function updateUser(User $user, array $validatedData, int $currentUserId): User
    {
        $defaultData = [
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ];

        $defaultSync = [
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ];

        return DB::transaction(function () use ($user, $validatedData, $defaultData, $defaultSync) {
            // If avatar uploaded, update the avatar
            if (isset($validatedData['avatar']) && $validatedData['avatar']->isValid()) {
                $path = $this->avatarService->upload($validatedData['avatar'], $user->id, $user->profile->avatar);

                $validatedData['avatar'] = $path;
            }

            $user->update(array_merge([
                'email' => $validatedData['email'],
                'username' => $validatedData['username'],
            ], $defaultData));

            $user->profile()->update(array_merge([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'nik' => $validatedData['nik'],
                'nuptk' => $validatedData['nuptk'],
                'position' => $validatedData['position'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
                'employment_start' => $validatedData['employment_start'],
                'employment_end' => $validatedData['employment_end'],
                'avatar' => $validatedData['avatar'] ?? $user->profile->avatar,
            ], $defaultData));

            if (! empty($validatedData['role'])) {
                $user->roles()->syncWithPivotValues([$validatedData['role']], $defaultSync);
            } else {
                $user->roles()->detach();
            }

            if (! empty($validatedData['level'])) {
                $user->levels()->syncWithPivotValues([$validatedData['level']], $defaultSync);
            } else {
                $user->levels()->detach();
            }

            $scheduleIds = [];
            if (! empty($validatedData['schedule'])) {
                $scheduleIds = explode(',', $validatedData['schedule']);

                // Validate all IDs exist in DB
                $validIds = Schedule::whereIn('id', $scheduleIds)->pluck('id')->toArray();
                if (count($validIds) !== count($scheduleIds)) {
                    return back()->withErrors(['schedule' => 'One or more selected schedules are invalid.']);
                }

                $user->schedules()->syncWithPivotValues($scheduleIds, $defaultSync);
            } else {
                $user->schedules()->detach();
            }

            return $user;
        });
    }
}
