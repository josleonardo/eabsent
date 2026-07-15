<?php

namespace App\Http\Controllers\Approvals;

use App\Exports\Approvals\Leaves\LeaveExport;
use App\Exports\Approvals\Leaves\LeaveHistoryExport;
use App\Exports\Approvals\Leaves\LeavePendingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\UpdateLeaveStatusRequest;
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
        $currentUser = $request->user();
        $pendings = $leaveService->getPending($currentUser);

        return view('approvals.leaves.index', ['pageName' => 'Pending Leave Request'] + compact('pendings'));
    }

    public function history(Request $request, LeaveService $leaveService)
    {
        $currentUser = $request->user();
        $histories = $leaveService->getHistory($currentUser);

        $statusKey = config('constants.approve_status');

        return view('approvals.leaves.history', ['pageName' => 'Leave History'] + compact('histories', 'statusKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveStatusRequest $request, Leave $leave, LeaveService $leaveService)
    {
        try {
            $leaveService->updateLeave(
                $leave,
                $request->validated()['action'],
                $request->user()->id
            );

            return back()->with('success', 'Leave request updated successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            Log::error(
                'Error revoking leave request: ' . $th->getMessage(),
                ['exception' => $th]
            );

            return back()->with(
                'error',
                'An unexpected error occurred while revoking the leave request.'
            );
        }
    }

    /**
     * Revoke a leave request.
     */
    public function revoke(Request $request, Leave $leave, LeaveService $leaveService)
    {
        try {
            $leaveService->revokeLeave(
                $leave,
                $request->user()->id
            );

            return back()->with('success', 'Leave request revoked successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            Log::error(
                'Error revoking leave request: ' . $th->getMessage(),
                ['exception' => $th]
            );

            return back()->with(
                'error',
                'An unexpected error occurred while revoking the leave request.'
            );
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
            $zip->addFile(storage_path('app/private/' . $pendingPath), 'leave_pending.csv');
            $zip->addFile(storage_path('app/private/' . $historyPath), 'leave_history.csv');
            $zip->close();
        } else {
            return back()->with('error', 'Could not create zip file.');
        }

        Storage::delete([$pendingPath, $historyPath]);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
