<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSchedule;
use App\Models\Appointment;
use App\Models\AppointmentServices;
use App\Models\Refund;
use App\Models\RefundService;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalDoctors = User::where('user_type', 'doctor')->count();
        $totalReceptions = User::where('user_type', 'reception')->count();
        $totalPatients = User::where('user_type', 'patient')->count();

        $topDoctors = User::where('user_type', 'doctor')->latest()->take(5)->get();
        $userSchedules = UserSchedule::with('user')->get();

        $totalAppointmentIncome = Appointment::sum('final_fee');
        $totalServiceIncome = AppointmentServices::join('services', 'appointment_services.services_id', '=', 'services.id')
            ->sum('services.price');
        $totalSales = $totalAppointmentIncome + $totalServiceIncome;

        $totalDoctorRefund = Refund::whereNotNull('approved_at')
            ->sum('doctor_fee_refund');

        $totalServiceRefund = RefundService::join('services', 'refund_services.service_id', '=', 'services.id')
            ->whereIn('refund_services.refund_id', Refund::whereNotNull('approved_at')->pluck('id'))
            ->sum('services.price');

        $profit = $totalSales - ($totalDoctorRefund + $totalServiceRefund);

        $todayAppointmentIncome = Appointment::whereDate('created_at', today())->sum('final_fee');
        $todayServiceIncome = AppointmentServices::join('services', 'appointment_services.services_id', '=', 'services.id')
            ->whereDate('appointment_services.created_at', today())
            ->sum('services.price');
        $todaySales = $todayAppointmentIncome + $todayServiceIncome;

        $todayDoctorRefund = Refund::whereNotNull('approved_at')
            ->whereDate('approved_at', today())
            ->sum('doctor_fee_refund');

        $todayServiceRefund = RefundService::join('services', 'refund_services.service_id', '=', 'services.id')
            ->join('refunds', 'refund_services.refund_id', '=', 'refunds.id')
            ->whereDate('refunds.approved_at', today())
            ->sum('services.price');

        $todayProfit = $todaySales - ($todayDoctorRefund + $todayServiceRefund);

        return view('dashboard.index', compact(
            'totalUsers',
            'totalDoctors',
            'totalReceptions',
            'totalPatients',
            'topDoctors',
            'userSchedules',
            'totalSales',
            'totalDoctorRefund',
            'totalServiceRefund',
            'profit',
            'todaySales',
            'todayDoctorRefund',
            'todayServiceRefund',
            'todayProfit'
        ));
    }


}
