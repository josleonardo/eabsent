<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreSchoolLocationRequest;
use App\Http\Requests\Admins\UpdateSchoolLocationRequest;
use App\Models\SchoolLocation;
use App\Services\Admins\SchoolLocationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SchoolLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SchoolLocationService $schoolLocationService)
    {
        $userRole = Auth::user()->roles->first()->name ?? '';
        $schoolLocations = $schoolLocationService->getSchoolLocations($userRole);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.school-locations.index', ['pageName' => 'School Locations'] + compact('schoolLocations', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeKey = config('constants.actives');

        return view('administrators.school-locations.create', ['pageName' => 'Add school location'] + compact('activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolLocationRequest $request, SchoolLocationService $schoolLocationService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $schoolLocationService->createSchoolLocation($validatedData, $currentUserId);

            return redirect()->route('school-location.index')->with('success', 'School location created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating school location: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the school location.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SchoolLocation $schoolLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolLocation $schoolLocation)
    {
        $activeKey = config('constants.actives');

        return view('administrators.school-locations.edit', ['pageName' => 'Edit school location'] + compact('schoolLocation', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolLocationRequest $request, SchoolLocation $schoolLocation, SchoolLocationService $schoolLocationService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $schoolLocationService->updateSchoolLocation($schoolLocation, $validatedData, $currentUserId);

            return redirect()->route('school-location.index')->with('success', 'School location updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating school location: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the school location.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolLocation $schoolLocation)
    {
        //
    }
}
