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
    Route::middleware(['userType:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // ================================
    // ADMIN ROUTES (Require Permissions)
    // ================================
    Route::middleware(['userType:admin'])->group(function () {
        Route::resource('adduser', AddUserController::class);
        Route::post('/schedule.assign', [AddUserController::class, 'storeSchedule'])->name('schedule.assign');
        Route::get('/schedule.assign/{id}/schedules', [AddUserController::class, 'getSchedules'])->name('schedule.schedules');
    });

    Route::middleware(['userType:admin'])->group(function () {
        Route::resource('clinic', ClinicController::class);
    });

    Route::middleware(['userType:admin'])->group(function () {
        Route::resource('services', ServiceController::class);
    });

    Route::middleware(['userType:admin'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('rolepermission', RolePermissionController::class);
    });

    Route::middleware(['userType:admin'])->group(function () {
        Route::get('/refunds', [ReceptionController::class, 'refundIndex'])->name('refunds.index');
        Route::post('/refunds/{refund}/approve', [ReceptionController::class, 'approve'])->name('refunds.approve');
        Route::post('/refunds/{refund}/reject', [ReceptionController::class, 'reject'])->name('refunds.reject');
    });
    
    Route::post('/refunds/store', [ReceptionController::class, 'refundStore'])->name('refunds.store');
    Route::get('/refunds/{appointment}', [ReceptionController::class, 'showRefund'])->name('refunds.show');
    // ================================
    // RECEPTION ROUTES
    // ================================
    Route::middleware(['userType:reception'])->group(function () {
        Route::resource('reception', ReceptionController::class);
    });

    Route::middleware(['userType:reception'])->group(function () {
        Route::resource('appointment', AppointmentController::class);
        Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
        Route::get('/appointments/{id}/print', [AppointmentController::class, 'print'])->name('appointments.print');
        Route::get('/get-top-patient', [ReceptionController::class, 'topPatientGet'])->name('reception.dashboard');
        Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
    });

    // ================================
    // PATIENT ROUTES
    // ================================
    Route::middleware(['userType:patient'])->group(function () {
        Route::get('/my-appointments', [PatientController::class, 'index'])->name('patient.index');
    });

    Route::middleware(['userType:patient'])->group(function () {
        Route::get('/patient-reports/download', [PatientController::class, 'reportsDownload'])->name('patient.reports.download');
        Route::delete('/patient-reports/{id}', [ReceptionController::class, 'destroyReport'])->name('patient-reports.destroy');
    });
    Route::middleware(['permission:Patients Reports'])->group(function () {
        Route::post('/patients/reports', [ReceptionController::class, 'patientReports'])->name('patients.patientReports');
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
    Route::middleware(['permission:All Patients List'])->group(function () {
    Route::get('/patients/list', [PatientController::class, 'listPatient'])
        ->name('patients.list')
        ->middleware('permission:Patients');
    });

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
