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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin routes
    Route::middleware(['userType:admin'])->group(function () {
        Route::post('/schedule.assign', [AddUserController::class, 'storeSchedule'])->name('schedule.assign');
        Route::get('/schedule.assign/{id}/schedules', [AddUserController::class, 'getSchedules'])->name('schedule.schedules');

        Route::resource('adduser', AddUserController::class);
        Route::resource('clinic', ClinicController::class);
        Route::resource('settings', SettingController::class);

        Route::resource('roles', RoleController::class);
        Route::resource('rolepermission', RolePermissionController::class);
        Route::resource('services', ServiceController::class);
    });

    // Reception routes
    Route::middleware(['userType:reception'])->group(function () {
        Route::resource('reception', ReceptionController::class);
        Route::resource('appointment', AppointmentController::class);

        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointment.index');
        Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
        Route::get('/appointments/{id}/print', [AppointmentController::class, 'print'])->name('appointments.print');

        Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
        Route::post('/patients/reports', [ReceptionController::class, 'patientReports'])->name('patients.patientReports');
        Route::delete('/patient-reports/{id}', [ReceptionController::class, 'destroyReport'])->name('patient-reports.destroy');

        Route::get('/get-top-patient', [ReceptionController::class, 'topPatientGet'])->name('reception.dashboard');
    });

    // Patient routes
    Route::middleware(['userType:patient'])->group(function () {
        Route::get('/get-patient', [PatientController::class, 'index'])->name('patient.index');
        Route::get('/patient-reports/download', [PatientController::class, 'reportsDownload'])->name('patient.reports.download');
    });


// Profile routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});