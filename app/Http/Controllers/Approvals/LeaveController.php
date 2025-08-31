<?php

namespace App\Http\Controllers\Approvals;

use App\Exports\Approvals\Leaves\LeaveExport;
use App\Exports\Approvals\Leaves\LeaveHistoryExport;
use App\Exports\Approvals\Leaves\LeavePendingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\RevokeLeaveRequest;
use App\Http\Requests\Approvals\UpdateLeaveRequest;
use App\Models\Leave;
use App\Services\Approvals\LeaveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LeaveService $leaveService)
    {
        $user = $request->user();
        $activeTab = $request->query('tab', 'pending'); // default to 'pending'

        $pendings = $activeTab === 'pending'
            ? $leaveService->getPending($user)
            : collect();

        return view('approvals.leaves.index', ['pageName' => 'Leave Requests'] + compact('pendings', 'activeTab'));
    }

    public function history(Request $request, LeaveService $leaveService)
    {
        $user = $request->user();
        $histories = $leaveService->getHistory($user);

        $statusKey = config('constants.approve_status');

        return view('approvals.leaves.history', ['pageName' => 'Leave History'] + compact('histories', 'statusKey'));
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

    public function exportExcel()
    {
        return (new LeaveExport)->download('leaves.xlsx');
    }

    public function exportCsv()
    {
        if (! Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        $pendingPath = 'exports/leave_pending.csv';
        $historyPath = 'exports/leave_history.csv';

        (new LeavePendingExport)->store($pendingPath, 'local');
        (new LeaveHistoryExport)->store($historyPath, 'local');

        $zipPath = storage_path('app/private/exports/leaves.zip');
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFile(storage_path('app/private/'.$pendingPath), 'leave_pending.csv');
            $zip->addFile(storage_path('app/private/'.$historyPath), 'leave_history.csv');
            $zip->close();
        } else {
            return back()->with('error', 'Could not create zip file.');
        }

        Storage::delete([$pendingPath, $historyPath]);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
