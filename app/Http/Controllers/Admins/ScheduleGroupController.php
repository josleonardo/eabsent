<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreScheduleGroupRequest;
use App\Http\Requests\Admins\UpdateScheduleGroupRequest;
use App\Models\ScheduleGroup;
use App\Services\Admins\ScheduleGroupService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ScheduleGroupService $scheduleGroupService)
    {
        $userRole = Auth::user()->roles->first()->name ?? '';
        $scheduleGroups = $scheduleGroupService->getScheduleGroups($userRole);

        $days = config('constants.days');
        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.schedule-groups.index', ['pageName' => 'Schedule Group'] + compact('scheduleGroups', 'days', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ScheduleGroupService $scheduleGroupService)
    {
        $schedules = $scheduleGroupService->getSchedulesForSelection();
        $days = config('constants.days');
        $activeKey = config('constants.actives');

        return view('administrators.schedule-groups.create', ['pageName' => 'Add Schedule Group'] + compact('schedules', 'days', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleGroupRequest $request, ScheduleGroupService $scheduleGroupService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $scheduleGroupService->createScheduleGroup($validatedData, $currentUserId);

            return redirect()->route('schedule-group.index')->with('success', 'Schedule group created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating schedule group: ' . $th->getMessage());

            return back()->with('error', 'An error occurred while creating the schedule group.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleGroup $scheduleGroup, ScheduleGroupService $scheduleGroupService)
    {
        $scheduleGroup = $scheduleGroupService->getScheduleGroupForEdit($scheduleGroup->id);
        $schedules = $scheduleGroupService->getSchedulesForSelection();
        $days = config('constants.days');
        $activeKey = config('constants.actives');

        return view('administrators.schedule-groups.edit', ['pageName' => 'Edit Schedule Group'] + compact('scheduleGroup', 'schedules', 'days', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleGroupRequest $request, ScheduleGroup $scheduleGroup, ScheduleGroupService $scheduleGroupService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $scheduleGroupService->updateScheduleGroup($scheduleGroup, $validatedData, $currentUserId);

            return redirect()->route('schedule-group.index')->with('success', 'Schedule group updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating schedule group: ' . $th->getMessage());

            return back()->with('error', 'An error occurred while updating the schedule group.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
