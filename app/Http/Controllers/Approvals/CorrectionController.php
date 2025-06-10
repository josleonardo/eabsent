<?php

namespace App\Http\Controllers\Approvals;

use App\Http\Controllers\Controller;
use App\Http\Requests\Approvals\UpdateCorrectionRequest;
use App\Models\Correction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CorrectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $statusKey = config('constants.approve_status');

        $pendings = Correction::getPending($user);
        $histories = Correction::getHistory($user);
        $activeTab = $request->query('tab', 'pending'); // default to 'pending'

        return view('approvals.corrections.index', ['pageName' => 'Correction Requests'] + compact('pendings', 'histories', 'statusKey', 'activeTab'));
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
                'approve_status' => $validatedData['approve_status'],
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
