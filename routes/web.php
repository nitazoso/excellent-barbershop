<?php

use App\Http\Controllers\Admin\BarberController as AdminBarberController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
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

    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])
        ->name('dashboard.stats');

    Route::get('/bookings', [AdminBookingController::class, 'index'])
        ->name('bookings');

    Route::get('/bookings/report', [AdminBookingController::class, 'report'])
        ->name('bookings.report');

    Route::get('/bookings/export-pdf', [AdminBookingController::class, 'exportPdf'])
        ->name('bookings.export-pdf');

    Route::post('/bookings/bulk-confirm', [AdminBookingController::class, 'bulkConfirm'])
        ->name('bookings.bulk-confirm');

    Route::match(['post', 'patch'], '/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])
        ->name('bookings.confirm');

    Route::match(['post', 'patch'], '/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])
        ->name('bookings.reject');

    Route::match(['post', 'patch'], '/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])
        ->name('bookings.status');

    // Quick Actions
    Route::post('/barbers', [AdminBarberController::class, 'store'])
        ->name('barbers.store');

    Route::post('/services', [AdminServiceController::class, 'store'])
        ->name('services.store');

    Route::post('/settings', [AdminSettingController::class, 'update'])
        ->name('settings.update');
});
