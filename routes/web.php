<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.home.index')->name('home');
Route::view('/booking', 'pages.booking.index')->name('booking.index');
Route::view('/booking/result', 'pages.booking.result')->name('booking.result');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/dashboard')->name('index');
    Route::view('/login', 'pages.admin.login')->name('login');
    Route::view('/dashboard', 'pages.admin.dashboard')->name('dashboard');
    Route::view('/bookings', 'pages.admin.bookings')->name('bookings');
});
