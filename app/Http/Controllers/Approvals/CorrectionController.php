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
        $role = $user->roles->first()->name;
        $level = $user->levels->first()->name;

        $pendings = $correctionService->getPending($role, $level);
        $histories = $correctionService->getHistory($role, $level);
        $activeTab = $request->query('tab', 'pending'); // default to 'pending'

        $statusKey = config('constants.approve_status');

        return view('approvals.corrections.index', ['pageName' => 'Correction Requests'] + compact('pendings', 'histories', 'activeTab', 'statusKey'));
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
    public function show(Correction $correction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Correction $correction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCorrectionRequest $request, Correction $correction)
    {
        if (! $correction) {
            return redirect()->back()->with('error', 'No correction request found.');
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $correction->update([
                'status' => $validatedData['status'],
                'approved_at' => now(),
                'approved_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ]);

            return redirect()->back()->with('success', 'Correction request updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

            return redirect()->back()->with('error', 'An error occurred while updating the correction request.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Correction $correction)
    {
        //
    }
}
