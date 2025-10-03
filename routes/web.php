<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddUserController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReceptionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ================== Admin Routes ==================
    Route::middleware(['permission:User Management'])->group(function () {
        Route::resource('adduser', AddUserController::class);
        Route::post('/schedule.assign', [AddUserController::class, 'storeSchedule'])
            ->name('schedule.assign');
        Route::get('/schedule.assign/{id}/schedules', [AddUserController::class, 'getSchedules'])
            ->name('schedule.schedules');
    });

    Route::middleware(['permission:Clinics'])->group(function () {
        Route::resource('clinic', ClinicController::class);
    });

    Route::middleware(['permission:Roles'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('rolepermission', RolePermissionController::class);
    });

    Route::middleware(['permission:Services'])->group(function () {
        Route::resource('services', ServiceController::class);
    });

    Route::middleware(['permission:Settings'])->group(function () {
        Route::resource('settings', SettingController::class);
    });

    // Refunds
    Route::middleware(['permission:Refunds'])->group(function () {
        Route::post('/refunds/{refund}/approve', [ReceptionController::class, 'approve'])
            ->name('refunds.approve');
        Route::post('/refunds/{refund}/reject', [ReceptionController::class, 'reject'])
            ->name('refunds.reject');
        Route::get('/refunds', [ReceptionController::class, 'refundIndex'])
            ->name('refunds.index');
        Route::post('/refunds/store', [ReceptionController::class, 'refundStore'])
            ->name('refunds.store');
        Route::get('/refunds/{appointment}', [ReceptionController::class, 'showRefund'])
            ->name('refunds.show');
    });

    // Reception
    Route::middleware(['permission:Patients'])->group(function () {
        Route::resource('reception', ReceptionController::class);
        Route::post('/patients/store', [PatientController::class, 'store'])
            ->name('patients.store');
        Route::post('/patients/reports', [ReceptionController::class, 'patientReports'])
            ->name('patients.patientReports');
        Route::delete('/patient-reports/{id}', [ReceptionController::class, 'destroyReport'])
            ->name('patient-reports.destroy');
        Route::get('/patients/list', [PatientController::class, 'listPatient'])
            ->name('patients.list');
        Route::get('/get-top-patient', [ReceptionController::class, 'topPatientGet'])
            ->name('reception.dashboard');
    });

    Route::middleware(['permission:Appointments'])->group(function () {
        Route::resource('appointment', AppointmentController::class);
        Route::get('/appointments', [AppointmentController::class, 'index'])
            ->name('appointment.index');
        Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])
            ->name('appointment.edit');
        Route::get('/appointments/{id}/print', [AppointmentController::class, 'print'])
            ->name('appointments.print');
    });

    Route::middleware(['permission:Doctor Appointment'])->group(function () {
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments'])
            ->name('doctor.appointments');
    });

    // Patient routes
    Route::middleware(['permission:My Appointments'])->group(function () {
        Route::get('/get-patient', [PatientController::class, 'index'])
            ->name('patient.index');
    });

    Route::middleware(['permission:My Reports'])->group(function () {
        Route::get('/patient-reports/download', [PatientController::class, 'reportsDownload'])
            ->name('patient.reports.download');
    });

    // Profile routes (sab ke liye accessible)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
