<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(): View
    {
        return view('pages.booking.index', [
            'services' => Service::query()->where('is_active', true)->orderBy('name')->get(),
            'schedules' => Schedule::query()
                ->with('barber')
                ->where('is_available', true)
                ->whereDate('date', '>=', today())
                ->whereHas('barber', fn ($query) => $query->where('is_active', true))
                ->orderBy('date')
                ->orderBy('start_time')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:services,id'],
            'barber_id' => ['required', 'integer', 'exists:barbers,id'],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $booking = DB::transaction(function () use ($validated): Booking {
            $schedule = Schedule::query()
                ->whereKey($validated['schedule_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $service = Service::query()
                ->whereKey($validated['service_id'])
                ->where('is_active', true)
                ->first();

            $barberIsActive = $schedule->barber()
                ->where('is_active', true)
                ->exists();

            $bookingAlreadyExists = Booking::query()
                ->where('schedule_id', $schedule->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if (
                ! $schedule->is_available
                || $schedule->date->lt(today())
                || (int) $schedule->barber_id !== (int) $validated['barber_id']
                || ! $barberIsActive
                || ! $service
                || $bookingAlreadyExists
            ) {
                throw ValidationException::withMessages([
                    'schedule_id' => 'Jadwal yang dipilih sudah tidak tersedia. Silakan pilih jadwal lain.',
                ]);
            }

            $customer = Customer::query()->firstOrCreate(
                ['phone' => $validated['phone']],
                ['name' => $validated['name']],
            );

            if ($customer->name !== $validated['name']) {
                $customer->update(['name' => $validated['name']]);
            }

            $schedule->update(['is_available' => false]);

            return Booking::query()->create([
                'booking_code' => 'EB-'.Str::upper(Str::random(10)),
                'customer_id' => $customer->id,
                'barber_id' => $schedule->barber_id,
                'service_id' => $service->id,
                'schedule_id' => $schedule->id,
                'booking_date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return to_route('booking.result', ['booking_code' => $booking->booking_code]);
    }
}
