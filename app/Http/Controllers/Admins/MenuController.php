<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admins\StoreMenuRequest;
use App\Http\Requests\Admins\UpdateMenuRequest;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::paginate(10);

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
    public function store(StoreMenuRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            Menu::create([
                'menu_group' => $validatedData['menu_group'],
                'name' => $validatedData['menu_name'],
                'url' => $validatedData['url'],
                'platform' => $validatedData['platform'],
                'order' => $validatedData['order'],
                'icon' => $validatedData['icon'],
                'active' => $validatedData['active'],
                'created_by' => $currentUserId,
                'updated_by' => $currentUserId,
            ]);

            return redirect()->route('menu.index')->with('success', 'Menu created successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

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
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validatedData = $request->validated();

        try {
            $currentUserId = $request->user()->id;

            $menu->update([
                'menu_group' => $validatedData['menu_group'],
                'name' => $validatedData['menu_name'],
                'url' => $validatedData['url'],
                'platform' => $validatedData['platform'],
                'order' => $validatedData['order'],
                'icon' => $validatedData['icon'],
                'active' => $validatedData['active'],
                'updated_by' => $currentUserId,
            ]);

            return redirect()->route('menu.index')->with('success', 'Menu updated successfully.');
        } catch (\Throwable $th) {
            Log::error($th);

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
