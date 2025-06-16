<?php

namespace App\Http\Controllers\Approvals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\UpdateLeaveRequest;
use App\Models\Leave;
use App\Services\Approvals\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LeaveService $leaveService)
    {
        $user = $request->user();
        $role = $user->roles->first()->name ?? null;
        $level = $user->levels->first()->name ?? null;

        $pendings = $leaveService->getPending($role, $level);
        $histories = $leaveService->getHistory($role, $level);
        $activeTab = $request->query('tab', 'pending'); // default to 'pending'

        $statusKey = config('constants.approve_status');

        return view('approvals.leaves.index', ['pageName' => 'Leave Requests'] + compact('pendings', 'histories', 'activeTab', 'statusKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave, LeaveService $leaveService)
    {
        if (! $leave) {
            return redirect()->back()->with('error', 'No leave request found.');
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $leaveService->updateLeave($leave, $validatedData, $currentUserId);

            return redirect()->back()->with('success', 'Leave request updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->back()->with('error', 'An error occurred while updating the leave request.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }
}
