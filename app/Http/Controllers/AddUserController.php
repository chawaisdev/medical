<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Appointment;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSchedule; // Make sure this is imported
use Carbon\Carbon;
class AddUserController extends Controller
{
    // Retrieve all users and pass them to the adduser index blade view
    public function index(Request $request)
    {
        $users = User::where('user_type', '!=', 'patient')
            ->orderBy('id', 'desc')
            ->get();
        return view('adduser.index', compact('users'));
    }

    // Return the create user form where admin can input user details
    public function create()
    {
        $role = Role::all();
        return view('adduser.create', compact('role'));
    }

    // Validate and store new user details including hashed password into database

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'user_type' => 'required|string|in:doctor,reception,patient',
            'fee' => 'nullable|numeric|min:0',
        ]);


        // Create user
        User::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'email' => $request->email,
            'user_type' => $request->user_type,
            'fee' => $request->fee,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('adduser.create')->with('success', 'User added successfully.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        $appointments = Appointment::where('doctor_id', $id)
            ->with(['patient', 'services'])
            ->get();

        $refunds = Refund::whereHas('appointment', function ($query) use ($id) {
                $query->where('doctor_id', $id);
            })
            ->where('status', 'approved')
            ->with(['appointment.patient', 'services'])
            ->get();

        $totalFinalFee = Appointment::where('doctor_id', $id)->sum('final_fee');

        $totalServicePrice = Appointment::where('doctor_id', $id)
            ->join('appointment_services', 'appointments.id', '=', 'appointment_services.appointment_id')
            ->join('services', 'appointment_services.services_id', '=', 'services.id')
            ->sum('services.price');

        $totalSales = $totalFinalFee + $totalServicePrice;

        $totalDoctorRefund = Refund::whereHas('appointment', function ($query) use ($id) {
                $query->where('doctor_id', $id);
            })
            ->where('status', 'approved')
            ->sum('doctor_fee_refund');

        $totalRefundServices = Refund::whereHas('appointment', function ($query) use ($id) {
                $query->where('doctor_id', $id);
            })
            ->where('status', 'approved')
            ->join('refund_services', 'refunds.id', '=', 'refund_services.refund_id')
            ->join('services', 'refund_services.service_id', '=', 'services.id')
            ->sum('services.price');

        $totalRefund = $totalDoctorRefund + $totalRefundServices;

        $profit = $totalSales - $totalRefund;

        return view('adduser.show', compact(
            'user',
            'appointments',
            'refunds',
            'totalSales',
            'totalDoctorRefund',
            'totalRefundServices',
            'totalRefund',
            'profit'
        ));
    }


    // Fetch user by ID and show it in the edit form for updating
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $role = Role::all();
        return view('adduser.edit', compact('user', 'role'));
    }

    // Validate and update the user details including optional password update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'user_type' => 'required|string|in:doctor,reception,patient',
            'fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->contact_number = $request->contact_number;
        $user->email = $request->email;
        $user->user_type = $request->user_type;
        $user->fee = $request->fee;
        $user->role_id = $request->role_id;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('adduser.index')->with('success', 'User updated successfully.');
    }


    // Find the user by ID and delete the user from the database
    public function destroy($id)
    {
        $adduser = User::findOrFail($id);
        $adduser->delete();

        return redirect()->route('adduser.index')->with('success', 'User deleted successfully.');
    }

    public function getSchedules($id)
    {
        $schedules = UserSchedule::where('user_id', $id)
            ->get()
            ->map(function ($schedule) {
                return [
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'is_active' => $schedule->is_active
                ];
            });

        return response()->json($schedules);
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'days' => 'required|array',
            'start_time' => 'array',
            'end_time' => 'array',
            'off_days' => 'array',
        ]);

        $userId = $request->user_id;
        $offDays = $request->off_days ?? [];

        foreach ($request->days as $day) {
            $start = $request->start_time[$day] ?? null;
            $end = $request->end_time[$day] ?? null;

            if (in_array($day, $offDays)) {
                UserSchedule::updateOrCreate(
                    ['user_id' => $userId, 'day' => $day],
                    [
                        'start_time' => null,
                        'end_time' => null,
                        'is_active' => 0,
                    ]
                );
                continue;
            }

            if (!$start && !$end) {
                UserSchedule::where('user_id', $userId)->where('day', $day)->delete();
                continue;
            }

            if ($start && $end) {
                UserSchedule::updateOrCreate(
                    ['user_id' => $userId, 'day' => $day],
                    [
                        'start_time' => $start,
                        'end_time' => $end,
                        'is_active' => 1,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'User schedule assigned/updated successfully.');
    }
}
