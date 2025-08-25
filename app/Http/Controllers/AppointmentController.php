<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use Carbon\Carbon;
class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor', 'patient']);
        // dd($query)
        // if ($request->has('date')) {
        //     $query->whereDate('date', $request->query('date'));
        // } else {
        //     $query->whereDate('date', \Carbon\Carbon::today());
        // }
        return view('appointments.index', [
            'appointments' => $query->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = User::where('user_type', 'doctor')->get();
        $services = Service::all();
        $patients = User::where('user_type', 'patient')->get();
        return view('appointments.create', compact('doctors', 'patients', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $appointment = Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'date' => $request->date,
            'time' => $request->time,
            'fee' => $request->fee,
            'discount' => $request->discount,
            'final_fee' => $request->final_fee,
        ]);

        if ($request->has('services')) {
            $appointment->services()->attach($request->services);
        }

        return redirect()->route('appointment.index')
                        ->with('success', 'Appointment scheduled successfully with services.');
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
        $appointment = Appointment::findOrFail($id);
        $doctors = User::where('user_type', 'doctor')->get();
        $patients = User::where('user_type', 'patient')->get();
        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->doctor_id = $request->doctor_id;
        $appointment->patient_id = $request->patient_id;
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->save();

        return redirect()->route('appointment.index')->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }

    public function print($id)
    {
        $appointment = Appointment::with(['doctor', 'patient', 'services'])->findOrFail($id);

        return view('appointments.print', compact('appointment'));
    }

}
