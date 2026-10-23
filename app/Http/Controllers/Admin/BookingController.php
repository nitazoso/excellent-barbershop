<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Contracts\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->latest('booking_date')
            ->latest('start_time')
            ->paginate(20);

        return view('pages.admin.bookings', compact('bookings'));
    }
}
