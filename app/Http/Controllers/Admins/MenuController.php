<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreMenuRequest;
use App\Http\Requests\Admins\UpdateMenuRequest;
use App\Models\Menu;
use App\Services\Admins\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, MenuService $menuService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('viewAny', Menu::class)) {
            abort(403);
        }

        $menus = $menuService->getMenus($currentUser);

        $platforms = config('constants.platforms');
        $activeKey = config('constants.actives');
        $yesNoKey = config('constants.yes_no');

        return view('administrators.menus.index', ['pageName' => 'Menus'] + compact('menus', 'platforms', 'activeKey', 'yesNoKey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $platforms = collect(config('constants.platforms'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();
        $activeKey = config('constants.actives');

        return view('administrators.menus.create', ['pageName' => 'Add menu'] + compact('platforms', 'activeKey'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request, MenuService $menuService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('create', Menu::class)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $menuService->createMenu($validatedData, $currentUserId);

            return redirect()->route('menu.index')->with('success', 'Menu created successfully.');
        } catch (\Throwable $th) {
            Log::error('Error creating menu: '.$th->getMessage());

            return back()->with('error', 'An error occurred while creating the menu.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $platforms = collect(config('constants.platforms'))
            ->mapWithKeys(function ($value, $key) {
                return [$key => __($value)];
            })
            ->toArray();
        $activeKey = config('constants.actives');

        return view('administrators.menus.edit', ['pageName' => 'Edit menu'] + compact('menu', 'platforms', 'activeKey'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu, MenuService $menuService)
    {
        $currentUser = $request->user();
        
        if ($currentUser->cannot('update', $menu)) {
            abort(403);
        }

        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $menuService->updateMenu($menu, $validatedData, $currentUserId);

            return redirect()->route('menu.index')->with('success', 'Menu updated successfully.');
        } catch (\Throwable $th) {
            Log::error('Error updating menu: '.$th->getMessage());

            return back()->with('error', 'An error occurred while updating the menu.');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
