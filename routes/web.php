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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Schedule assign routes
Route::post('/schedule.assign', [AddUserController::class, 'storeSchedule'])->name('schedule.assign');
Route::get('/schedule.assign/{id}/schedules', [AddUserController::class, 'getSchedules'])->name('schedule.schedules');

// Admin Resource Controllers
Route::resource('adduser', AddUserController::class);
Route::resource('clinic', ClinicController::class);

// Patient Routes
Route::get('/get-patient', [PatientController::class, 'index'])->name('patient.index');
Route::get('/patient-reports/download', [PatientController::class, 'reportsDownload'])->name('patient.reports.download');

// Roles & Permissions
Route::resource('roles', RoleController::class);
Route::resource('rolepermission', RolePermissionController::class);

// Reception Routes
// Appointment routes
Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointment.index');
Route::get('/appointments/{id}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');

// Reception resource controllers
Route::resource('reception', ReceptionController::class);
Route::resource('appointment', AppointmentController::class);
Route::resource('services', ServiceController::class);

Route::post('/patients/store', [PatientController::class, 'store'])->name('patients.store');
Route::post('/patients/reports', [ReceptionController::class, 'patientReports'])->name('patients.patientReports');
Route::delete('/patient-reports/{id}', [ReceptionController::class, 'destroyReport'])->name('patient-reports.destroy');

Route::get('/get-top-patient', [ReceptionController::class, 'topPatientGet'])->name('reception.dashboard');

// Profile routes
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
