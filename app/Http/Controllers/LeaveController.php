<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Level;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserLevel = Auth::user()->levels->first()->id;
        $users = UserProfile::all()->pluck('fullname', 'user_id');
        $levels = Level::all()->pluck('level_name', 'id');
        $status = ['Rejected', 'Approved'];

        $pendingLeaves = Leave::getPendingLeaves($currentUserLevel);
        $processedLeaves = Leave::getProcessedLeaves($currentUserLevel);

        return view('approvals.leaves.index', [
            'pageName' => 'Leave Requests',
            'pendingLeaves' => $pendingLeaves,
            'processedLeaves' => $processedLeaves,
            'users' => $users,
            'levels' => $levels,
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
            return redirect()->back()->with('error', 'Leave request not found.');
        }
        
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'approve_status' => 'required|in:0,1',
        ]);

        $leave->update([
            'approve_status' => $validatedData['approve_status'],
            'approved_at' => now(),
            'approved_by' => $currentUserId,
            'active' => $validatedData['approve_status'] == 1 ? 1 : 0,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->back()->with('success', 'Leave request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }
}
