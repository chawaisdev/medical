<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Appointment;
use App\Models\User;
use App\Models\PatientReport;
class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $patient = Appointment::with(['doctor'])
                    ->where('patient_id', $user->id)
                    ->orderBy('id', 'desc')
                    ->get();
        return view('patient.index', compact('patient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'nullable|string|max:255',
            'father_name'    => 'nullable|string|max:255',
            'age'            => 'nullable|integer|min:0',
            'cnic'           => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'mr_number'      => 'nullable|string|max:50',
        ]);

        // Auto-generate MR Number if not provided
        $mrNumber = $request->mr_number ?: 'MR-' . str_pad(User::max('id') + 1, 5, '0', STR_PAD_LEFT);

        $patient = User::create([
            'name'           => $request->name,
            'father_name'    => $request->father_name,
            'age'            => $request->age,
            'cnic'           => $request->cnic,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'mr_number'      => $request->mr_number ?: 'MR-' . str_pad(User::max('id') + 1, 5, '0', STR_PAD_LEFT),
            'email'          => $request->email,
            'user_type'      => 'patient',
            'password'       => bcrypt($request->password),
        ]);


        return redirect()->back()->with('success', 'Patient added successfully!');
    }

    public function reportsDownload(Request $request)
    {
        // Fetch only the authenticated user's reports
        $reports = PatientReport::where('user_id', Auth::id())->with('user')->get();
        return view('patient.reports', compact('reports'));
    }

    public function listPatient()
    {
        $patients = User::with('creator')
            ->where('user_type', 'patient')
            ->latest()
            ->get();

        return view('patient.list-patient', compact('patients'));
    }

}

