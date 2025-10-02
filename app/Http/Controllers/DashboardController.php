<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSchedule;
use App\Models\Appointment;
use App\Models\AppointmentServices; // Updated to singular naming
use App\Models\Service;
use App\Models\Refund;
use App\Models\RefundService;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // === USERS ===
        $totalUsers = User::count();
        $totalDoctors = User::where('user_type', 'doctor')->count();
        $totalReceptions = User::where('user_type', 'reception')->count();
        $totalPatients = User::where('user_type', 'patient')->count();
        $topDoctors = User::where('user_type', 'doctor')->latest()->take(5)->get();
        $userSchedules = UserSchedule::with('user')->get();

        // === SALES ===
        // Today's Sales: Appointment fees + Service fees
        $todayAppointmentIncome = Appointment::whereDate('created_at', Carbon::today())->sum('final_fee');
        $todayServiceIncome = AppointmentServices::join('services', 'appointment_services.services_id', '=', 'services.id')
            ->whereDate('appointment_services.created_at', Carbon::today())
            ->sum('services.price');
        $todaySales = $todayAppointmentIncome + $todayServiceIncome;

        // Last 7 Days Sales
        $sevenDaysAppointmentIncome = Appointment::whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])->sum('final_fee');
        $sevenDaysServiceIncome = AppointmentServices::join('services', 'appointment_services.services_id', '=', 'services.id')
            ->whereBetween('appointment_services.created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('services.price');
        $sevenDaysSales = $sevenDaysAppointmentIncome + $sevenDaysServiceIncome;

        // Last Month Sales
        $lastMonthAppointmentIncome = Appointment::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('final_fee');
        $lastMonthServiceIncome = AppointmentServices::join('services', 'appointment_services.services_id', '=', 'services.id')
            ->whereMonth('appointment_services.created_at', Carbon::now()->subMonth()->month)
            ->whereYear('appointment_services.created_at', Carbon::now()->subMonth()->year)
            ->sum('services.price');
        $lastMonthSales = $lastMonthAppointmentIncome + $lastMonthServiceIncome;

        // Total Sales
        $totalAppointmentIncome = Appointment::sum('final_fee');
        $totalServiceIncome = AppointmentServices::join('services', 'appointment_services.services_id', '=', 'services.id')
            ->sum('services.price');
        $totalSales = $totalAppointmentIncome + $totalServiceIncome;

        // === REFUNDS ===
        // Today's Refund (using approved_at)
        $todayRefund = Refund::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->whereDate('approved_at', Carbon::today())
            ->sum('requested_amount');

        // Last 7 Days Refund (using approved_at)
        $sevenDaysRefund = Refund::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->whereBetween('approved_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->sum('requested_amount');

        // Last Month Refund (using approved_at)
        $lastMonthRefund = Refund::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->whereMonth('approved_at', Carbon::now()->subMonth()->month)
            ->whereYear('approved_at', Carbon::now()->subMonth()->year)
            ->sum('requested_amount');

        // Doctor Fee Refund (for reporting, using approved_at)
        $doctorFeeRefund = Refund::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->sum('doctor_fee_refund');

        // Refunded Services (for reporting, using approved refunds)
        $refundServiceAmount = RefundService::join('services', 'refund_services.service_id', '=', 'services.id')
            ->whereIn('refund_services.refund_id', Refund::where('status', 'approved')->whereNotNull('approved_at')->pluck('id'))
            ->sum('services.price');

        // === PROFIT ===
        // Profit = Total Sales - Last Month's Approved Refunds
        $profit = $totalSales - $lastMonthRefund;

        // Alternative: Profit = Total Sales - Total Approved Refunds
        // $totalRefunds = Refund::where('status', 'approved')->whereNotNull('approved_at')->sum('requested_amount');
        // $profit = $totalSales - $totalRefunds;

        return view('dashboard.index', compact(
            'totalUsers',
            'totalDoctors',
            'totalReceptions',
            'totalPatients',
            'topDoctors',
            'userSchedules',
            'todaySales',
            'sevenDaysSales',
            'lastMonthSales',
            'totalSales',
            'todayRefund',
            'sevenDaysRefund',
            'lastMonthRefund',
            'doctorFeeRefund',
            'refundServiceAmount',
            'profit'
        ));
    }
}