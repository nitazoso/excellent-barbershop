<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
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

// Hasil booking
Route::get('/booking/result/{booking_code}', [BookingResultController::class, 'show'])
    ->name('booking.result');

// Cek booking pelanggan
Route::get('/cek-booking', [CheckBookingController::class, 'index'])
    ->name('booking.check');

Route::post('/cek-booking', [CheckBookingController::class, 'check'])
    ->name('booking.check.submit');

// Admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard')->name('index');

    Route::view('/login', 'pages.admin.login')->name('login');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])
        ->name('bookings');
});
