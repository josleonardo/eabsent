<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreLeaveTypeRequest;
use App\Http\Requests\Admins\UpdateLeaveTypeRequest;
use App\Models\LeaveType;
use App\Services\Admins\LeaveTypeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(LeaveTypeService $leaveTypeService)
    {
        $userRole = Auth::user()->roles->first()->name ?? '';
        $leaveTypes = $leaveTypeService->getLeaveTypes($userRole);

        $yesNoKey = config('constants.yes_no');
        $activeKey = config('constants.actives');

        return view('administrators.leave-types.index', ['pageName' => 'Leave Types'] + compact('leaveTypes', 'yesNoKey', 'activeKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $yesNoKey = config('constants.yes_no');
        $activeKey = config('constants.actives');

        return view('administrators.leave-types.create', ['pageName' => 'Add leave type'] + compact('yesNoKey', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveTypeRequest $request, LeaveTypeService $leaveTypeService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $leaveTypeService->createLeaveType($validatedData, $currentUserId);

            return redirect()->route('leave-type.index')->with('success', 'Leave type created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating leave type: ' . $th->getMessage());

            return back()->with('error', 'An error occurred while creating the leave type.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveType $leaveType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        $yesNoKey = config('constants.yes_no');
        $activeKey = config('constants.actives');

        return view('administrators.leave-types.edit', ['pageName' => 'Edit leave type'] + compact('leaveType', 'yesNoKey', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveTypeRequest $request, LeaveType $leaveType, LeaveTypeService $leaveTypeService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $leaveTypeService->updateLeaveType($leaveType, $validatedData, $currentUserId);

            return redirect()->route('leave-type.index')->with('success', 'Leave type updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating leave type: ' . $th->getMessage());

            return back()->with('error', 'An error occurred while updating the leave type.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        //
    }
}
