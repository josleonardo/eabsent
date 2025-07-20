<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreAppSettingRequest;
use App\Http\Requests\Admins\UpdateAppSettingRequest;
use App\Models\AppSetting;
use App\Services\Admins\AppSettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AppSettingService $appSettingService)
    {
        $userRole = Auth::user()->roles->first()->name ?? '';
        $appSettings = $appSettingService->getAppSettings($userRole);

        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.app-settings.index', ['pageName' => 'App Settings'] + compact('appSettings', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $activeKey = config('constants.actives');

        return view('administrators.app-settings.create', ['pageName' => 'Add app setting'] + compact('activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppSettingRequest $request, AppSettingService $appSettingService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $appSettingService->createAppSetting($validatedData, $currentUserId);

            return redirect()->route('app-setting.index')->with('success', 'App setting created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating app setting: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the app setting.');
        }
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
        $activeKey = config('constants.actives');

        return view('administrators.app-settings.edit', ['pageName' => 'Edit app setting'] + compact('appSetting', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppSettingRequest $request, AppSetting $appSetting, AppSettingService $appSettingService)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $appSettingService->updateAppSetting($appSetting, $validatedData, $currentUserId);

            return redirect()->route('app-setting.index')->with('success', 'App setting updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating app setting: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the app setting.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppSetting $appSetting)
    {
        //
    }
}
