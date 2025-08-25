<?php

namespace App\Http\Controllers\Approvals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\UpdateCorrectionRequest;
use App\Models\Correction;
use App\Services\Approvals\CorrectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        $statusKey = config('constants.approve_status');

        return view('approvals.corrections.index', ['pageName' => 'Correction Requests'] + compact('pendings', 'activeTab', 'statusKey'));
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
}
