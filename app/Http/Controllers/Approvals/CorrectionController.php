<?php

namespace App\Http\Controllers\Approvals;

use App\Exports\Approvals\Corrections\CorrectionExport;
use App\Exports\Approvals\Corrections\CorrectionHistoryExport;
use App\Exports\Approvals\Corrections\CorrectionPendingExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\UpdateCorrectionRequest;
use App\Models\Correction;
use App\Services\Approvals\CorrectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class CorrectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        Request $request,
        CorrectionService $correctionService
    ) {
        $pendings = $correctionService->getPending($request->user());

        return view('approvals.corrections.index', ['pageName' => 'Pending Correction Requests'] + compact('pendings'));
    }

    public function history(
        Request $request,
        CorrectionService $correctionService
    ) {
        $histories = $correctionService->getHistory($request->user());

        return view('approvals.corrections.history', ['pageName' => 'Correction History'] + compact('histories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateCorrectionRequest $request,
        Correction $correction,
        CorrectionService $correctionService
    ) {
        try {
            $correctionService->updateCorrection(
                $correction,
                $request->validated()['action'],
                $request->user()->id
            );

            return back()->with('success', 'Correction request updated successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            Log::error(
                'Error updating correction request: ' . $th->getMessage(),
                ['exception' => $th]
            );

            return back()->with(
                'error',
                'An unexpected error occurred while updating the correction request.'
            );
        }
    }

    /**
     * Revoke a correction request.
     */
    public function revoke(
        Request $request,
        Correction $correction,
        CorrectionService $correctionService
    ) {
        try {
            $correctionService->revokeCorrection(
                $correction,
                $request->user()->id
            );

            return back()->with('success', 'Correction request revoked successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $th) {
            Log::error(
                'Error revoking correction request: ' . $th->getMessage(),
                ['exception' => $th]
            );

            return back()->with(
                'error',
                'An unexpected error occurred while revoking the correction request.'
            );
        }
    }

    public function exportExcel()
    {
        return (new CorrectionExport)->download('Corrections.xlsx');
    }

    public function exportCsv()
    {
        if (! Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        $pendingPath = 'exports/correction_pending.csv';
        $historyPath = 'exports/correction_history.csv';

        (new CorrectionPendingExport)->store($pendingPath, 'local');
        (new CorrectionHistoryExport)->store($historyPath, 'local');

        $zipPath = storage_path('app/private/exports/corrections.zip');
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFile(storage_path('app/private/' . $pendingPath), 'correction_pending.csv');
            $zip->addFile(storage_path('app/private/' . $historyPath), 'correction_history.csv');
            $zip->close();
        } else {
            return back()->with('error', 'Could not create zip file.');
        }

        Storage::delete([$pendingPath, $historyPath]);

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
