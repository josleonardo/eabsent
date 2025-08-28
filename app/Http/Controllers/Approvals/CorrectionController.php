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
    public function index(Request $request, CorrectionService $correctionService)
    {
        $user = $request->user();
        $activeTab = $request->query('tab', 'pending'); // default to 'pending'

        $pendings = $activeTab === 'pending'
            ? $correctionService->getPending($user)
            : collect();

        return view('approvals.corrections.index', ['pageName' => 'Correction Requests'] + compact('pendings', 'activeTab'));
    }

    public function history(Request $request, CorrectionService $correctionService)
    {
        $user = $request->user();
        $histories = $correctionService->getHistory($user);

        $statusKey = config('constants.approve_status');

        return view('approvals.corrections.history', ['pageName' => 'Correction History'] + compact('histories', 'statusKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorrectionRequest $request, Correction $correction, CorrectionService $correctionService)
    {
        if (! $correction) {
            return redirect()->back()->with('error', 'No correction request found.');
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $correctionService->updateCorrection($correction, $validatedData, $currentUserId);

            return redirect()->back()->with('success', 'Correction request updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->back()->with('error', 'An error occurred while updating the correction request.');
        }
    }

    public function exportExcel()
    {
        return (new CorrectionExport)->download('Corrections.xlsx');
    }

    public function exportCsv()
    {
        if (!Storage::exists('exports')) {
            Storage::makeDirectory('exports');
        }

        $pendingPath = 'exports/correction_pending.csv';
        $historyPath = 'exports/correction_history.csv';

        (new CorrectionPendingExport)->store($pendingPath, 'local');
        (new CorrectionHistoryExport)->store($historyPath, 'local');

        $zipPath = storage_path('app/private/exports/corrections.zip');
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
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
