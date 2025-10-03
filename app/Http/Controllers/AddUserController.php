<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Appointment;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UserSchedule; // Make sure this is imported

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

        // Default
        $todaySales = $sevenDaysSales = $lastMonthSales = $totalSales = 0;
        $todayRefund = $sevenDaysRefund = $lastMonthRefund = $totalRefund = 0;
        $profit = 0;
        $appointments = [];

        if ($user->user_type === 'doctor') {
            // Get doctor appointments with services
            $appointments = Appointment::with(['patient', 'services'])
                ->where('doctor_id', $user->id)
                ->get();

            // --- Sales (sum of services->price instead of just final_fee) ---
            $totalSales = $appointments->sum(function ($appointment) {
                return $appointment->services->sum('price');
            });

            $todaySales = $appointments->where('date', today()->toDateString())
                ->sum(function ($appointment) {
                    return $appointment->services->sum('price');
                });

            $sevenDaysSales = $appointments->whereBetween('date', [now()->subDays(6)->toDateString(), now()->toDateString()])
                ->sum(function ($appointment) {
                    return $appointment->services->sum('price');
                });

            $lastMonthSales = $appointments->filter(function ($appointment) {
                return \Carbon\Carbon::parse($appointment->date)->month === now()->subMonth()->month
                    && \Carbon\Carbon::parse($appointment->date)->year === now()->subMonth()->year;
            })->sum(function ($appointment) {
                return $appointment->services->sum('price');
            });

            // --- Refunds (only approved) ---
            $refunds = Refund::with('services')
                ->where('approved_by_user_id', $user->id)
                ->where('status', 'approved')
                ->get();

            $totalRefund = $refunds->sum(function ($refund) {
                return $refund->services->sum('price');
            });

            $todayRefund = $refunds->where('created_at', '>=', today())
                ->sum(function ($refund) {
                    return $refund->services->sum('price');
                });

            $sevenDaysRefund = $refunds->whereBetween('created_at', [now()->subDays(6), now()])
                ->sum(function ($refund) {
                    return $refund->services->sum('price');
                });

            $lastMonthRefund = $refunds->filter(function ($refund) {
                return $refund->created_at->month === now()->subMonth()->month
                    && $refund->created_at->year === now()->subMonth()->year;
            })->sum(function ($refund) {
                return $refund->services->sum('price');
            });

            // --- Profit = Total services - refunded services ---
            $profit = $totalSales - $totalRefund;
        }

        return view('adduser.show', compact(
            'user',
            'appointments',
            'todaySales',
            'sevenDaysSales',
            'lastMonthSales',
            'totalSales',
            'todayRefund',
            'sevenDaysRefund',
            'lastMonthRefund',
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
