<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PatientReport;
use App\Models\Refund;
use App\Models\RefundService;
use App\Models\Appointment;
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
            ->with('reports') 
            ->orderBy('id', 'desc')
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
            'father_name'    => 'nullable|string|max:255',
            'age'            => 'nullable|integer|min:0',
            'cnic'           => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
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
            'created_by'     => auth()->id(),
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
            'father_name'    => 'nullable|string|max:255',
            'age'            => 'nullable|integer|min:0',
            'cnic'           => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string',
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

    public function showRefund($appointmentId)
    {
        $appointment = Appointment::with(['doctor', 'patient', 'services'])->findOrFail($appointmentId);

        $refund = Refund::where('appointment_id', $appointmentId)->latest()->first();

        $refundExists = (bool) $refund;
        $canSubmit = true;
        $statusMessage = null;

        if ($refund) {
            $expireTime = $refund->created_at->addHours(8);

            if (now()->lt($expireTime)) {
                // 8 ghante se kam guzray → submit kar sakta hai
                $canSubmit = true;
                $statusMessage = 'Submit Refund Request';
            } else {
                // 8 ghante se zyada ho gaye → expire
                $canSubmit = false;
                $statusMessage = 'Expire Refund Request';
            }
        }

        return view('appointments.refund', compact(
            'appointment',
            'refund',
            'refundExists',
            'canSubmit',
            'statusMessage'
        ));
    }

    // Receptionist creates refund request
    public function refundStore(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'patient_id' => 'required|exists:users,id',
            'requested_amount' => 'required|numeric|min:0',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'doctor_fee_refunded' => 'nullable|boolean',
            'reason' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($request->appointment_id);
        $selectedServices = $request->services ?? [];

        $doctorFeeAmount = 0;
        if ($request->doctor_fee_refunded) {
            // full doctor fee without discount
            $doctorFeeAmount = $appointment->fee;
        }

        $totalRefundable = $this->calculateRefundableAmount($appointment, $selectedServices, $request->doctor_fee_refunded);

        if ($request->requested_amount > $totalRefundable) {
            return back()->withErrors([
                'requested_amount' => "Requested amount exceeds the total refundable amount (" . number_format($totalRefundable, 2) . ")"
            ]);
        }

        $refund = Refund::where('appointment_id', $request->appointment_id)->first();

        if ($refund) {
            $refund->update([
                'reason' => $request->reason ?? $refund->reason,
                'requested_amount' => $refund->requested_amount + $request->requested_amount,
                'doctor_fee_refund' => $refund->doctor_fee_refund > 0 ? $refund->doctor_fee_refund : $doctorFeeAmount,
            ]);
        } else {
            $refund = Refund::create([
                'appointment_id' => $request->appointment_id,
                'patient_id' => $request->patient_id,
                'created_by_user_id' => auth()->id(),
                'reason' => $request->reason,
                'requested_amount' => $request->requested_amount,
                'doctor_fee_refund' => $doctorFeeAmount,
            ]);
        }

        if (!empty($selectedServices)) {
            $alreadyRefunded = $refund->services()->pluck('service_id')->toArray();

            foreach ($selectedServices as $serviceId) {
                if (!in_array($serviceId, $alreadyRefunded)) {
                    RefundService::create([
                        'refund_id' => $refund->id,
                        'service_id' => $serviceId,
                    ]);
                }
            }
        }

        return back()->with('success', 'Refund request saved/updated successfully');
    }

    private function calculateRefundableAmount(Appointment $appointment, array $selectedServices, $doctorFeeRefunded): float
    {
        $totalServicesFee = 0;

        foreach ($selectedServices as $serviceId) {
            $service = $appointment->services()->where('services.id', $serviceId)->first();
            if ($service) {
                $totalServicesFee += $service->price;
            }
        }

        // doctor fee always full refund (no discount)
        $doctorFee = $doctorFeeRefunded ? $appointment->fee : 0;

        return $totalServicesFee + $doctorFee;
    }

    public function refundIndex(Request $request)
    {
        $refunds = Refund::with(['appointment','patient','creator','approver','services'])
            ->orderBy('id', 'desc')
            ->get();
        return view('refunds.index', compact('refunds'));
    }

    // Doctor approves refund
    public function approve(Request $request, Refund $refund)
    {
        $request->validate([
            'approved_amount' => 'required|numeric|min:0',
        ]);

        $refund->update([
            'approved_by_user_id' => auth()->id(),
            'approved_amount'     => $request->approved_amount,
            'approved_at'         => now(),
            'status'              => 'approved'
        ]);

        return back()->with('success','Refund approved successfully');
    }

    // Doctor rejects refund
    public function reject(Refund $refund)
    {
        $refund->update([
            'approved_by_user_id' => auth()->id(),
            'approved_at'         => now(),
            'status'              => 'rejected'
        ]);

        return back()->with('error','Refund rejected');
    }
}
