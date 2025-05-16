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
        $types = ['Web', 'Android',];

        return view('administrators.menus.index', ['pageName' => 'Menus'] + compact('menus', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = ['Web', 'Android',];

        return view('administrators.menus.create', ['pageName' => 'Add menu'] + compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'menu_id' => 'required|integer',
            'menu_name' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:menus,url',
            'type' => 'required|integer|max:5',
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);
        
        $currentUserId = Auth::id();

        Menu::create([
            'menu_id' => $validatedData['menu_id'],
            'name' => $validatedData['menu_name'],
            'url' => $validatedData['url'],
            'type' => $validatedData['type'],
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
        $types = ['Web', 'Android',];

        return view('administrators.menus.edit', ['pageName' => 'Edit menu'] + compact('menu', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'menu_id' => 'required|integer',
            'menu_name' => 'required|string|max:255',
            'url' => 'required|string|max:255|unique:menus,url,' . $menu->id,
            'type' => 'required|integer|max:5',
            'order' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ]);
        
        $currentUserId = Auth::id();

        $menu->update([
            'menu_id' => $validatedData['menu_id'],
            'name' => $validatedData['menu_name'],
            'url' => $validatedData['url'],
            'type' => $validatedData['type'],
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
