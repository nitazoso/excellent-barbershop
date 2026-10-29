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

    // Quick Actions
    Route::post('/barbers', [AdminBarberController::class, 'store'])->name('barbers.store');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
