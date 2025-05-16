<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appSettings = AppSetting::paginate(10);
        return view('administrators.app-settings.index', ['pageName' => 'App Settings'] + compact('appSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.app-settings.create', ['pageName' => 'Add app setting']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'setting_name' => 'required|string|max:255|unique:app_settings,name',
            'key' => 'nullable|string|max:255|unique:app_settings,key',
            'value_1' => 'nullable|string|max:255',
            'value_2' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);
        
        $currentUserId = Auth::id();

        AppSetting::create([
            'name' => $validatedData['setting_name'],
            'key' => $validatedData['key'],
            'value_1' => $validatedData['value_1'],
            'value_2' => $validatedData['value_2'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('app-setting.index')->with('success', 'App setting added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AppSetting $appSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppSetting $appSetting)
    {
        return view('administrators.app-settings.edit', ['pageName' => 'Edit app setting'] + compact('appSetting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppSetting $appSetting)
    {
        $validatedData = $request->validate([
            'setting_name' => 'required|string|max:255|unique:app_settings,name,' . $appSetting->id,
            'key' => 'nullable|string|max:255|unique:app_settings,key,' . $appSetting->id,
            'value_1' => 'nullable|string|max:255',
            'value_2' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);
        
        $currentUserId = Auth::id();

        $appSetting->update([
            'name' => $validatedData['setting_name'],
            'key' => $validatedData['key'],
            'value_1' => $validatedData['value_1'],
            'value_2' => $validatedData['value_2'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('app-setting.index')->with('success', 'App setting updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppSetting $appSetting)
    {
        //
    }
}
