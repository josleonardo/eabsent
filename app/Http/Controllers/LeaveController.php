<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $status = ['Rejected', 'Approved'];

        $pending = Leave::getPending($user);
        $processed = Leave::getProcessed($user);

        return view('approvals.leaves.index', [
            'pageName' => 'Leave Requests',
            'pendingLeaves' => $pending,
            'processedLeaves' => $processed,
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
    public function update(Request $request, Leave $leave)
    {
        if (!$leave) {
            return redirect()->back()->with('error', 'No leave request found.');
        }
        
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'approve_status' => 'required|in:0,1',
        ]);

        $leave->update([
            'approve_status' => $validatedData['approve_status'],
            'approved_at' => now(),
            'approved_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->back()->with('success', 'Leave request processed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }
}
