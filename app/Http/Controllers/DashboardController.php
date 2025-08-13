<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserSchedule;
class DashboardController extends Controller
{
    public function index()
    {
        $totaluser = User::count();
        $totalDoctors = User::where('user_type', 'doctor')->count();
        $totalReceptions = User::where('user_type', 'reception')->count();
        $totalPatients = User::where('user_type', 'patient')->count();

        $topDoctors = User::where('user_type', 'doctor')->latest()->take(5)->get();

        $userSchedules = UserSchedule::with('user')->get();

        return view('dashboard.index', compact(
            'totaluser',
            'totalDoctors',
            'totalReceptions',
            'totalPatients',
            'topDoctors',
            'userSchedules'
        ));
    }

}
