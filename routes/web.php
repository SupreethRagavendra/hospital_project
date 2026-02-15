<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;

Route::controller(\App\Http\Controllers\SetupController::class)->group(function () {
    Route::get('/setup', 'index')->name('setup.index');
    Route::post('/setup', 'store')->name('setup.store');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->middleware('auth')->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::patch('/users/{id}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::get('/members', [AdminController::class, 'members'])->name('members');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs');
    Route::get('/audit-logs/export', [AdminController::class, 'exportAuditLogs'])->name('audit-logs.export');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

Route::prefix('doctor')->middleware(['auth', 'role:doctor'])->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
    Route::get('/patients', [DoctorController::class, 'patients'])->name('patients');
    Route::get('/patients/search', [DoctorController::class, 'searchPatients'])->name('patients.search');
    Route::get('/patients/{id}', [DoctorController::class, 'showPatient'])->name('patients.show');
    Route::get('/records', [DoctorController::class, 'records'])->name('records');
    Route::get('/records/create/{patient_id}', [DoctorController::class, 'createRecord'])->name('records.create');
    Route::post('/records', [DoctorController::class, 'storeRecord'])->name('records.store');
    Route::get('/records/{id}', [DoctorController::class, 'showRecord'])->name('records.show');
    Route::get('/records/{id}/edit', [DoctorController::class, 'editRecord'])->name('records.edit');
    Route::put('/records/{id}', [DoctorController::class, 'updateRecord'])->name('records.update');
    Route::delete('/records/{id}', [DoctorController::class, 'deleteRecord'])->name('records.delete');
    Route::get('/prescriptions', [DoctorController::class, 'prescriptions'])->name('prescriptions');
    Route::post('/prescriptions', [DoctorController::class, 'storePrescription'])->name('prescriptions.store');
    Route::put('/prescriptions/{id}', [DoctorController::class, 'updatePrescription'])->name('prescriptions.update');
    Route::delete('/prescriptions/{id}', [DoctorController::class, 'deletePrescription'])->name('prescriptions.delete');
    Route::get('/lab-reports', [DoctorController::class, 'labReports'])->name('lab-reports');
    Route::post('/lab-reports', [DoctorController::class, 'storeLabReport'])->name('lab-reports.store');
    Route::get('/lab-reports/{id}', [DoctorController::class, 'showLabReport'])->name('lab-reports.show');
    Route::patch('/lab-reports/{id}/review', [DoctorController::class, 'reviewLabReport'])->name('lab-reports.review');
    Route::get('/lab-reports/{id}/download', [DoctorController::class, 'downloadLabReport'])->name('lab-reports.download');
});

Route::prefix('patient')->middleware(['auth', 'role:patient'])->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
    Route::get('/records', [PatientController::class, 'records'])->name('records');
    Route::get('/records/{id}', [PatientController::class, 'showRecord'])->name('records.show');
    Route::get('/prescriptions', [PatientController::class, 'prescriptions'])->name('prescriptions');
    Route::get('/prescriptions/{id}', [PatientController::class, 'showPrescription'])->name('prescriptions.show');
    Route::get('/lab-reports', [PatientController::class, 'labReports'])->name('lab-reports');
    Route::get('/lab-reports/{id}', [PatientController::class, 'showLabReport'])->name('lab-reports.show');
    Route::get('/lab-reports/{id}/download', [PatientController::class, 'downloadLabReport'])->name('lab-reports.download');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('profile.password');
});
