<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $todayBookings = Booking::query()
            ->with(['customer', 'barber', 'service'])
            ->whereDate('booking_date', today())
            ->orderBy('start_time')
            ->get();

        return view('pages.admin.dashboard', [
            'totalBookingsCount' => Booking::query()->count(),
            'todayDateLabel' => now()->locale('id')->translatedFormat('l, d F Y'),
            'todayBookingsCount' => Booking::query()->whereDate('booking_date', today())->count(),
            'pendingBookingsCount' => Booking::query()->where('status', 'pending')->count(),
            'confirmedTodayBookingsCount' => Booking::query()
                ->whereDate('booking_date', today())
                ->where('status', 'confirmed')
                ->count(),
            'pendingBookings' => Booking::query()
                ->with(['customer', 'barber', 'service'])
                ->where('status', 'pending')
                ->latest('created_at')
                ->limit(4)
                ->get(),
            'todayBookings' => $todayBookings,
            'availableSchedulesCount' => Schedule::query()
                ->where('is_available', true)
                ->whereDate('date', '>=', today())
                ->count(),
            'activeServicesCount' => Service::query()->where('is_active', true)->count(),
        ]);
    }
}
