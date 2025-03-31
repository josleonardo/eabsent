<?php

namespace App\Http\Controllers;

use App\Models\Correction;
use App\Models\Level;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorrectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserLevel = Auth::user()->levels->first()->id;
        $users = UserProfile::all()->pluck('fullname', 'user_id');
        $levels = Level::all()->pluck('level_name', 'id');
        
        $corrections = Correction::getCorrections($currentUserLevel);
        
        return view('approvals.corrections.request', [
            'pageName' => 'Correction Requests',
            'corrections' => $corrections,
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Correction $correction)
    {
        //
    }
}
