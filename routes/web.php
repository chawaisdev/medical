<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController,
    AddUserController,
    ClinicController,
    DashboardController,
    ReceptionController,
    AppointmentController,
    PatientController,
    ServiceController,
    RoleController,
    RolePermissionController,
    SettingController
};

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // ================================
    // DASHBOARD
    // ================================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================================
    // ADMIN ROUTES (Require Permissions)
    // ================================
    Route::middleware(['permission:User Management'])->group(function () {
        Route::resource('adduser', AddUserController::class);
        Route::post('/schedule.assign', [AddUserController::class, 'storeSchedule'])->name('schedule.assign');
        Route::get('/schedule.assign/{id}/schedules', [AddUserController::class, 'getSchedules'])->name('schedule.schedules');
    });

    Route::middleware(['permission:Clinics'])->group(function () {
        Route::resource('clinic', ClinicController::class);
    });

    Route::middleware(['permission:Services'])->group(function () {
        Route::resource('services', ServiceController::class);
    });

    Route::middleware(['permission:Roles'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('rolepermission', RolePermissionController::class);
    });

    Route::middleware(['permission:Refunds'])->group(function () {
        Route::get('/refunds', [ReceptionController::class, 'refundIndex'])->name('refunds.index');
        Route::post('/refunds/{refund}/approve', [ReceptionController::class, 'approve'])->name('refunds.approve');
        Route::post('/refunds/{refund}/reject', [ReceptionController::class, 'reject'])->name('refunds.reject');
        Route::post('/refunds/store', [ReceptionController::class, 'refundStore'])->name('refunds.store');
        Route::get('/refunds/{appointment}', [ReceptionController::class, 'showRefund'])->name('refunds.show');
    });

    // ================================
    // RECEPTION ROUTES
    // ================================
    Route::middleware(['permission:Patients'])->group(function () {
        Route::resource('reception', ReceptionController::class);
    });

    Route::middleware(['permission:Appointments'])->group(function () {
        Route::resource('appointment', AppointmentController::class);
        Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
        Route::get('/appointments/{id}/print', [AppointmentController::class, 'print'])->name('appointments.print');
    });

    Route::get('/get-top-patient', [ReceptionController::class, 'topPatientGet'])->name('reception.dashboard');
    Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
    // ================================
    // PATIENT ROUTES
    // ================================
    Route::middleware(['permission:My Appointments'])->group(function () {
        Route::get('/get-patient', [PatientController::class, 'index'])->name('patient.index');
    });

    Route::middleware(['permission:My Reports'])->group(function () {
        Route::get('/patient-reports/download', [PatientController::class, 'reportsDownload'])->name('patient.reports.download');
        Route::post('/patients/reports', [ReceptionController::class, 'patientReports'])->name('patients.patientReports');
        Route::delete('/patient-reports/{id}', [ReceptionController::class, 'destroyReport'])->name('patient-reports.destroy');
    });

    // ================================
    // DOCTOR ROUTES
    // ================================
    Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments'])
        ->name('doctor.appointments')
        ->middleware('permission:Doctor Appointment');

    // ================================
    // ALL PATIENTS (ADMIN ONLY)
    // ================================
    Route::get('/patients/list', [PatientController::class, 'listPatient'])
        ->name('patients.list')
        ->middleware('permission:Patients');

    // ================================
    // SETTINGS (ADMIN ONLY)
    // ================================
    Route::resource('settings', SettingController::class)
        ->middleware('permission:Settings');

    // ================================
    // PROFILE
    // ================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
