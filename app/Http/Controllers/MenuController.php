<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::paginate(10);
        return view('administrators.menus.menu', ['pageName' => 'Menus', 'singleName' => 'menu'], compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('administrators.menus.create', ['pageName' => 'Add menu']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_url' => 'required|string|max:255|unique:menus',
            'type' => 'required|integer|max:5',
            'main_menu_id' => 'required|integer',
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        Menu::create([
            'menu_name' => $validatedData['menu_name'],
            'menu_url' => $validatedData['menu_url'],
            'type' => $validatedData['type'],
            'main_menu_id' => $validatedData['main_menu_id'],
            'order' => $validatedData['order'],
            'icon' => $validatedData['icon'],
            'active' => $validatedData['active'],
            'created_by' => $currentUserId,
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu added successfully.');
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
        $types = [
            0 => 'Web',
            1 => 'Android',
        ];

        return view('administrators.menus.edit', ['pageName' => 'Edit menu'], compact('menu', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $currentUserId = Auth::id();

        $validatedData = $request->validate([
            'menu_name' => 'required|string|max:255',
            'menu_url' => 'required|string|max:255|unique:menus,menu_url,' . $menu->id,
            'type' => 'required|integer|max:5',
            'main_menu_id' => 'required|integer',
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);

        $menu->update([
            'menu_name' => $validatedData['menu_name'],
            'menu_url' => $validatedData['menu_url'],
            'type' => $validatedData['type'],
            'main_menu_id' => $validatedData['main_menu_id'],
            'order' => $validatedData['order'],
            'icon' => $validatedData['icon'],
            'active' => $validatedData['active'],
            'updated_by' => $currentUserId,
        ]);

        return redirect()->route('menu.index')->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
