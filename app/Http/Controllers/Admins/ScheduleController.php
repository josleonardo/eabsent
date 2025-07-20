<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreScheduleRequest;
use App\Http\Requests\Admins\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Services\Admins\ScheduleService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ScheduleService $scheduleService)
    {
        $userRole = Auth::user()->roles->first()->name ?? '';
        $schedules = $scheduleService->getSchedules($userRole);

        $days = config('constants.days');
        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.schedules.index', ['pageName' => 'Schedule'] + compact('schedules', 'days', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = collect(config('constants.days'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();
        $activeKey = config('constants.actives');

        return view('administrators.schedules.create', ['pageName' => 'Add schedule'] + compact('days', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request, ScheduleService $scheduleService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $scheduleService->createSchedule($validatedData, $currentUserId);

            return redirect()->route('schedule.index')->with('success', 'Schedule created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating schedule: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the schedule.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        $days = collect(config('constants.days'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();
        $activeKey = config('constants.actives');

        return view('administrators.schedules.edit', ['pageName' => 'Edit schedule'] + compact('schedule', 'days', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule, ScheduleService $scheduleService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $scheduleService->updateSchedule($schedule, $validatedData, $currentUserId);

            return redirect()->route('schedule.index')->with('success', 'Schedule updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating schedule: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the schedule.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        //
    }
}
