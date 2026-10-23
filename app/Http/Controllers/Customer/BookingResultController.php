<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Contracts\View\View;

class BookingResultController extends Controller
{
    public function show(string $booking_code): View
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('pages.booking.result', ['booking' => $booking]);
    }
}
