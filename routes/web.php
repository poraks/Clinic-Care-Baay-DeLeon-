<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Shared Authenticated Routes (Accessible by both Admin and Patient)
    Route::get('/dashboard', [AppointmentController::class, 'index'])->name('dashboard');
    Route::get('/prescriptions', [AppointmentController::class, 'prescriptions'])->name('prescriptions.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patient-Only Routes
    Route::middleware('role:patient')->group(function () {
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    });

    // Admin-Only Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AppointmentController::class, 'adminDashboard'])->name('dashboard');
        Route::patch('/appointments/{id}/accept', [AppointmentController::class, 'accept'])->name('appointments.accept');
        Route::patch('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::patch('/appointments/{id}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    });

});

require __DIR__.'/auth.php';