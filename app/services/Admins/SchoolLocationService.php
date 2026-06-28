<?php

namespace App\Services\Admins;

use App\Models\SchoolLocation;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SchoolLocationService
{
    public function getSchoolLocations(string $userRole, ?int $perPage = null): LengthAwarePaginator
    {
        $superAdmin = Role::ROLE_SUPERADMIN;
        $admin = Role::ROLE_ADMIN;
        $perPage = $perPage ?? config('constants.default_per_page');

        if ($userRole == $superAdmin || $userRole == $admin) {
            return SchoolLocation::paginate($perPage);
        }

        return abort(403, 'Unauthorized');
    }

    public function createSchoolLocation(array $validatedData, int $currentUserId): SchoolLocation
    {
        $schoolLocation = SchoolLocation::create([
            'name' => $validatedData['school_location_name'],
            'key' => $validatedData['key'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'radius' => $validatedData['radius'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return $schoolLocation;
    }

    public function updateSchoolLocation(SchoolLocation $schoolLocation, array $validatedData, int $currentUserId): SchoolLocation
    {
        $schoolLocation->update([
            'name' => $validatedData['school_location_name'],
            'key' => $validatedData['key'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'radius' => $validatedData['radius'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return $schoolLocation;
    }
}
