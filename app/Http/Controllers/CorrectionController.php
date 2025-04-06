<?php

namespace App\Http\Controllers;

use App\Models\Correction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $status = ['Rejected', 'Approved'];
        
        $pending = Correction::getPending($user);
        $processed = Correction::getProcessed($user);
        
        return view('approvals.corrections.index', [
            'pageName' => 'Correction Requests',
            'pendingCorrections' => $pending,
            'processedCorrections' => $processed,
            'status' => $status,
        ]);
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
    public function update(Request $request, Correction $correction)
    {
        if (!$correction) {
            return redirect()->back()->with('error', 'No correction request found.');
        }
        
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'approve_status' => 'required|in:0,1',
        ]);

        $correction->update([
            'approve_status' => $validatedData['approve_status'],
            'approved_at' => now(),
            'approved_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->back()->with('success', 'Correction request processed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Correction $correction)
    {
        //
    }
}
