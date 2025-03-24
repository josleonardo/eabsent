<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::paginate(10);
        return view('administrators.settings.setting', ['pageName' => 'Settings', 'singleName' => 'setting'], compact('settings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.settings.create', ['pageName' => 'Add setting']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'setting_name' => 'required|string|max:255|unique:settings',
            'key' => 'nullable|string|max:255|unique:settings',
            'value_1' => 'nullable|string|max:255',
            'value_2' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        Setting::create([
            'setting_name' => $validatedData['setting_name'],
            'key' => $validatedData['key'],
            'value_1' => $validatedData['value_1'],
            'value_2' => $validatedData['value_2'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('setting.index')->with('success', 'Setting added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
