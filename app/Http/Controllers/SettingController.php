<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
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
    public function update(Request $request, $id)
    {
        $user = Auth::user(); // Always use logged-in user

        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1',
            'cnic' => 'nullable|string|max:15',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'profile_pic_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user->fill($request->only([
            'name', 'father_name', 'age', 'cnic', 'contact_number', 'address', 'email'
        ]));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_pic_path')) {
            $file = $request->file('profile_pic_path');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/profile'), $filename);
            $user->profile_pic_path = 'uploads/profile/' . $filename;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
