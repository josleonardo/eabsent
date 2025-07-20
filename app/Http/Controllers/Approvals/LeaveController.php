<?php

namespace App\Http\Controllers\Approvals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\RevokeLeaveRequest;
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
        $statusMap = [
            'approve' => Leave::STATUS_APPROVED,
            'reject' => Leave::STATUS_REJECTED,
        ];

        $action = $request->input('action');
        $status = $statusMap[$action] ?? null;

        if ($status === null) {
            return back()->with('error', 'Invalid action.');
        }

        $validatedData = $request->validated();
        $validatedData['status'] = $status;

        try {
            $currentUserId = $request->user()->id;

            $leaveService->updateLeave($leave, $validatedData, $currentUserId);

            return back()->with('success', 'Leave request updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating leave request: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the leave request.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }

    /**
     * Revoke a leave request.
     */
    public function revoke(RevokeLeaveRequest $request, Leave $leave, LeaveService $leaveService)
    {
        if ($leave->status == Leave::STATUS_REVOKED) {
            return back()->with('error', 'Leave is already revoked.');
        }

        if ($leave->status != Leave::STATUS_APPROVED) {
            return back()->with('error', 'Only approved leaves can be revoked.');
        }

        $statusMap = [
            'revoke' => Leave::STATUS_REVOKED,
        ];

        $action = $request->input('action');
        $status = $statusMap[$action] ?? null;

        if ($status === null) {
            return back()->with('error', 'Invalid action.');
        }

        $validatedData = $request->validated();
        $validatedData['status'] = $status;

        try {
            $currentUserId = $request->user()->id;

            $leaveService->revokeLeave($leave, $validatedData, $currentUserId);

            return back()->with('success', 'Leave request revoked successfully.');
        } catch (\Throwable $th) {
            Log::error('Error revoking leave request: '.$th->getMessage());

            return back()->with('error', 'An error occurred while revoking the leave request.');
        }
    }
}
