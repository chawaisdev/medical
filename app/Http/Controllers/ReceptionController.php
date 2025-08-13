<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PatientReport;
use Illuminate\Support\Facades\Hash;
class ReceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Retrieve all users and pass them to the adduser index blade view
    public function index(Request $request)
    {
        $users = User::where('user_type', 'patient')
                    ->with('reports') // eager load reports
                    ->get();

        return view('reception.index', compact('users'));
    }


    // Return the create user form where admin can input user details
    public function create()
    {
        return view('reception.create');
    }

    // Validate and store new user details including hashed password into database

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8',
            'father_name'    => 'nullable|string|max:255',
            'age'            => 'nullable|integer|min:0',
            'cnic'           => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'mr_number'      => 'nullable|string|max:50',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'user_type'      => 'patient',
            'father_name'    => $request->father_name,
            'age'            => $request->age,
            'cnic'           => $request->cnic,
            'contact_number' => $request->contact_number,
            'address'        => $request->address,
            'mr_number'      => $request->mr_number ?: 'MR-' . str_pad(User::max('id') + 1, 5, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('reception.index')->with('success', 'Patient added successfully.');
    }

    // Fetch user by ID and show it in the edit form for updating
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('reception.edit', compact('user'));
    }

    // Validate and update the user details including optional password update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $id,
            'password'       => 'nullable|string|min:8',
            'father_name'    => 'nullable|string|max:255',
            'age'            => 'nullable|integer|min:0',
            'cnic'           => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'mr_number'      => 'nullable|string|max:50',
        ]);

        $user = User::findOrFail($id);

        $user->name           = $request->name;
        $user->email          = $request->email;
        $user->user_type      = 'patient';
        $user->father_name    = $request->father_name;
        $user->age            = $request->age;
        $user->cnic           = $request->cnic;
        $user->contact_number = $request->contact_number;
        $user->address        = $request->address;
        $user->mr_number      = $request->mr_number;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('reception.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('reception.index')->with('success', 'Patient deleted successfully.');
    }

    public function topPatientGet(Request $request)
    {
        $dashboard = User::where('user_type', 'patient')->get();
        $totalDoctor = User::where('user_type', 'patient')->count();
        return view('reception.dashboard',compact('dashboard','totalDoctor'));
    }

    public function patientReports(Request $request)
    {
        $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|array',
        'title.*' => 'required|string|max:255',
        'report_date' => 'required|array',
        'report_date.*' => 'required|date',
        'report_file' => 'required|array',
        'report_file.*' => 'required|file|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx|max:5120',
    ]);


        $userId = $request->user_id;
        $titles = $request->input('title');
        $dates = $request->input('report_date');
        $files = $request->file('report_file');

        foreach ($dates as $index => $date) {
            if (isset($files[$index])) {
                $randomName = uniqid() . '_' . bin2hex(random_bytes(5)) . '.' . $files[$index]->getClientOriginalExtension();
                $files[$index]->move(public_path('assets/reports'), $randomName);
                $relativePath = 'assets/reports/' . $randomName;

                PatientReport::create([
                    'user_id' => $userId,
                    'title' => $titles[$index],
                    'date' => $date,
                    'reports' => $relativePath,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Patient reports saved successfully.');
    }

    public function destroyReport($id)
    {
        $report = PatientReport::findOrFail($id);
        if (file_exists(public_path($report->reports))) {
            unlink(public_path($report->reports));
        }
        $report->delete();
        return redirect()->back()->with('success', 'Report deleted successfully.');
    }

}
