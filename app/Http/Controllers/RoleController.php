<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePermission;

class RoleController extends Controller
{
    public function index()
    {
        $jobroles = Role::with('permission')->get();
        // dd(Role::with('permission')->get());
        return view('roles.index', compact('jobroles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        foreach ($request->permissions as $permission) {
            RolePermission::create([
                'role_id' => $role->id,
                'name' => $permission,
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'string',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
        ]);

        RolePermission::where('role_id', $role->id)->delete();

        foreach ($request->permissions as $permission) {
            RolePermission::create([
                'role_id' => $role->id,
                'name' => $permission,
            ]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        RolePermission::where('role_id', $id)->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
