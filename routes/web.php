<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GoogleAuthController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\BookingResultController;
use App\Http\Controllers\Customer\CheckBookingController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

// Booking pelanggan
Route::get('/booking', [BookingController::class, 'index'])
    ->name('booking.index');

Route::post('/booking', [BookingController::class, 'store'])
    ->name('booking.store');

// API jadwal untuk wizard booking
Route::get('/api/booking/schedules', [BookingController::class, 'schedules'])
    ->name('booking.schedules');

// Hasil booking
Route::get('/booking/result/{booking_code}/download', [BookingResultController::class, 'download'])
    ->name('booking.result.download');
Route::get('/booking/result/{booking_code}', [BookingResultController::class, 'show'])
    ->name('booking.result');

// Cek booking pelanggan
Route::get('/cek-booking', [CheckBookingController::class, 'index'])
    ->name('booking.check');

Route::post('/cek-booking', [CheckBookingController::class, 'check'])
    ->name('booking.check.submit');

// Admin
$adminPath = config('services.admin.path', env('ADMIN_PATH', 'ctrl-excellent'));

Route::prefix($adminPath)->name('admin.')->group(function () use ($adminPath): void {
    Route::redirect('/', '/'.$adminPath.'/dashboard')->name('index');

    Route::middleware(['throttle:10,1'])->group(function (): void {
        Route::get('/login', [GoogleAuthController::class, 'showLogin'])->name('login');
        Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    });

    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');

        Route::get('/admins', [AdminManagementController::class, 'index'])->name('admins.index');
        Route::post('/admins', [AdminManagementController::class, 'store'])->name('admins.store');
        Route::patch('/admins/{user}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('admins.toggle-status');
    });
});
