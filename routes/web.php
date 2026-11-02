<?php

<<<<<<< HEAD
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GoogleAuthController;
=======
use App\Http\Controllers\Admin\BarberController as AdminBarberController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
>>>>>>> d1fd5d604d1c0c98cc75b62623443ea7446e8400
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

Route::get('/booking/schedule', [BookingController::class, 'schedule'])
    ->name('booking.schedule');

Route::get('/booking/confirm', [BookingController::class, 'confirm'])
    ->name('booking.confirm');

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
    Route::redirect('/', '/' . $adminPath . '/dashboard')->name('index');

    Route::middleware(['throttle:10,1'])->group(function (): void {
        Route::get('/login', [GoogleAuthController::class, 'showLogin'])->name('login');
        Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    });

    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])
        ->name('dashboard.stats');

    Route::get('/bookings', [AdminBookingController::class, 'index'])
        ->name('bookings');

    Route::post('/bookings/manual', [AdminBookingController::class, 'storeManual'])
        ->name('bookings.manual.store');

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

    // Kelola Master Barber
    Route::get('/barbers', [AdminBarberController::class, 'index'])
        ->name('barbers.index');

    Route::post('/barbers', [AdminBarberController::class, 'store'])
        ->name('barbers.store');

    Route::get('/barbers/{barber}/edit', [AdminBarberController::class, 'edit'])
        ->name('barbers.edit');

    Route::match(['put', 'patch'], '/barbers/{barber}', [AdminBarberController::class, 'update'])
        ->name('barbers.update');

    Route::delete('/barbers/{barber}', [AdminBarberController::class, 'destroy'])
        ->name('barbers.destroy');

    Route::match(['post', 'patch'], '/barbers/{barber}/toggle-booking', [AdminBarberController::class, 'togglePublicBooking'])
        ->name('barbers.toggle-booking');

    Route::match(['post', 'patch'], '/barbers/{barber}/toggle-active', [AdminBarberController::class, 'toggleActive'])
        ->name('barbers.toggle-active');

    // Kelola Katalog Layanan
    Route::get('/services', [AdminServiceController::class, 'index'])
        ->name('services.index');

    Route::post('/services', [AdminServiceController::class, 'store'])
        ->name('services.store');

    Route::get('/services/{service}/edit', [AdminServiceController::class, 'edit'])
        ->name('services.edit');

    Route::match(['put', 'patch'], '/services/{service}', [AdminServiceController::class, 'update'])
        ->name('services.update');

    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])
        ->name('services.destroy');

    Route::match(['post', 'patch'], '/services/{service}/toggle-active', [AdminServiceController::class, 'toggleActive'])
        ->name('services.toggle-active');

    Route::match(['post', 'patch'], '/services/{service}/toggle-widget', [AdminServiceController::class, 'toggleWidget'])
        ->name('services.toggle-widget');

    Route::post('/settings', [AdminSettingController::class, 'update'])
        ->name('settings.update');
});