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
        return view('pages.admin.dashboard', [
            'todayBookingsCount' => Booking::query()->whereDate('booking_date', today())->count(),
            'pendingBookingsCount' => Booking::query()->where('status', 'pending')->count(),
            'availableSchedulesCount' => Schedule::query()
                ->where('is_available', true)
                ->whereDate('date', '>=', today())
                ->count(),
            'activeServicesCount' => Service::query()->where('is_active', true)->count(),
        ]);
    }
}
