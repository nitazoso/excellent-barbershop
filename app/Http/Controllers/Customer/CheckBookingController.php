<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckBookingController extends Controller
{
    public function index(): View
    {
        return view('pages.booking.check', ['bookings' => collect()]);
    }

    public function check(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $customer = Customer::query()->where('phone', $validated['phone'])->first();

        if (! $customer) {
            return back()
                ->withInput()
                ->withErrors(['phone' => 'Booking dengan nomor HP tersebut tidak ditemukan.']);
        }

        $bookings = Booking::query()
            ->with(['barber', 'service', 'schedule'])
            ->where('customer_id', $customer->id)
            ->latest('booking_date')
            ->get();

        return view('pages.booking.check', compact('bookings'));
    }
}
