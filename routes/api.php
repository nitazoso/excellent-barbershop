<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('api.admin.')->group(function () {
    Route::get('/bookings', [AdminBookingController::class, 'report'])->name('bookings.index');
    Route::get('/bookings/report', [AdminBookingController::class, 'report'])->name('bookings.report');
    Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
});

