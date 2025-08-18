<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolePermission;
class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rolepermission = RolePermission::all(); // Fetch all roles from the database
        return view('rolepermission.index', compact('rolepermission')); // Pass the roles to the view
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
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $rolepermission = new Rolepermission();
        $rolepermission->name = $request->input('name');
        $rolepermission->save();

        return redirect()->route('rolepermission.index')->with('success', 'Role Permission created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $rolepermission = RolePermission::findOrFail($id);
        $rolepermission->name = $request->input('name');
        $rolepermission->save();

        return redirect()->route('rolepermission.index')->with('success', 'Job Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rolepermission = RolePermission::findOrFail($id);
        $rolepermission->delete();

        return redirect()->route('rolepermission.index')->with('success', 'Job Category deleted successfully.');
    }
}
