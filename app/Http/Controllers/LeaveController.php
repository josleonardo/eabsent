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
        
        $leaves = Leave::getLeaves($currentUserLevel);

        return view('approvals.leaves.request', [
            'pageName' => 'Leave Requests',
            'leaves' => $leaves,
            'users' => $users,
            'levels' => $levels,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }
}
