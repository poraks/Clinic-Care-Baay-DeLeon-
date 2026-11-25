<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Shared Authenticated Routes (Accessible by both Admin and Patient)
Route::middleware('role:patient,admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Patient-Only Routes (Admins visiting these will get 403 Forbidden)
Route::middleware('role:patient')->group(function () {
    Route::get('/dashboard', [AppointmentController::class, 'index'])->name('dashboard');
    Route::get('/prescriptions', [AppointmentController::class, 'prescriptions'])->name('prescriptions.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
});

// Admin-Only Routes (Patients visiting these will get 403 Forbidden)
Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AppointmentController::class, 'adminDashboard'])->name('dashboard');
    Route::patch('/appointments/{id}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
    Route::patch('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::patch('/appointments/{id}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
});

require __DIR__.'/auth.php';

// Optional: Preview/trigger specific HTTP error codes directly
Route::get('/error/{code}', function (int $code) {
    abort_if(! in_array($code, [401, 402, 403, 404], true), 404);
    abort($code);
})->name('error.test');

// Catch-all fallback for any compromised or undefined route (MUST be last)
Route::fallback(function () {
    abort(404);
});