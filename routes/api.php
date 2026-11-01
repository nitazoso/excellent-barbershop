<?php

use App\Http\Controllers\Admin\BarberController as AdminBarberController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('api.admin.')->group(function () {
    // Ringkasan Statistik Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // Data Booking & Laporan
    Route::get('/bookings', [AdminBookingController::class, 'report'])->name('bookings.index');
    Route::get('/bookings/report', [AdminBookingController::class, 'report'])->name('bookings.report');

    // Aksi Konfirmasi Massal & Per Item
    Route::post('/bookings/bulk-confirm', [AdminBookingController::class, 'bulkConfirm'])->name('bookings.bulk-confirm');
    Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');

    // Pengelolaan Status Jadwal Booking Harian
    Route::match(['post', 'patch'], '/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.status');

    // Kelola Master Barber (API)
    Route::get('/barbers', [AdminBarberController::class, 'index'])->name('barbers.index');
    Route::post('/barbers', [AdminBarberController::class, 'store'])->name('barbers.store');
    Route::get('/barbers/{barber}/edit', [AdminBarberController::class, 'edit'])->name('barbers.edit');
    Route::match(['put', 'patch'], '/barbers/{barber}', [AdminBarberController::class, 'update'])->name('barbers.update');
    Route::delete('/barbers/{barber}', [AdminBarberController::class, 'destroy'])->name('barbers.destroy');
    Route::match(['post', 'patch'], '/barbers/{barber}/toggle-booking', [AdminBarberController::class, 'togglePublicBooking'])->name('barbers.toggle-booking');
    Route::match(['post', 'patch'], '/barbers/{barber}/toggle-active', [AdminBarberController::class, 'toggleActive'])->name('barbers.toggle-active');

    // Kelola Katalog Layanan (API)
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::get('/services/{service}/edit', [AdminServiceController::class, 'edit'])->name('services.edit');
    Route::match(['put', 'patch'], '/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
    Route::match(['post', 'patch'], '/services/{service}/toggle-active', [AdminServiceController::class, 'toggleActive'])->name('services.toggle-active');
    Route::match(['post', 'patch'], '/services/{service}/toggle-widget', [AdminServiceController::class, 'toggleWidget'])->name('services.toggle-widget');

    // Settings
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
