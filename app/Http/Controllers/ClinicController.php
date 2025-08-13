<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClinicAvailability;
use App\Models\User;
class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinics = ClinicAvailability::orderBy('day')->get();
        return view('clinic.index', compact('clinics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time' => 'array',
            'end_time' => 'array',
            'off_days' => 'array',
            'should_save' => 'array'
        ]);

        $startTimes = $request->start_time ?? [];
        $endTimes = $request->end_time ?? [];
        $offDays = $request->off_days ?? [];
        $shouldSave = $request->should_save ?? [];

        // Optional: clear old schedule
        ClinicAvailability::truncate();

        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
            if (isset($shouldSave[$day]) && $shouldSave[$day] == 1) {
                $isOffDay = in_array($day, $offDays);

                ClinicAvailability::create([
                    'day' => $day,
                    'start_time' => $isOffDay ? null : ($startTimes[$day] ?? null),
                    'end_time' => $isOffDay ? null : ($endTimes[$day] ?? null),
                    'is_active' => $isOffDay ? 0 : 1,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Clinic schedule saved successfully.');
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
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $clinic = ClinicAvailability::findOrFail($id);
        $clinic->user_id = $request->user_id;
        $clinic->day = $request->day;
        $clinic->start_time = $request->start_time;
        $clinic->end_time = $request->end_time;
        $clinic->save();

        return redirect()->back()->with('success', 'Clinic availability updated.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        ClinicAvailability::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Clinic availability deleted.');
    }
}
