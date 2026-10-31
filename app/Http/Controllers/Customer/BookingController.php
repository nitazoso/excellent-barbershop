<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
            'barbers' => Barber::query()->where('is_active', true)->orderBy('name')->get(),
            'services' => Service::query()->where('is_active', true)->orderBy('name')->get(),
            'schedules' => Schedule::query()
                ->with('barber')
                ->where('is_available', true)
                ->whereDoesntHave('bookings', function ($q) {
                    $q->whereIn('status', ['pending', 'confirmed']);
                })
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
            'schedule_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $map = [
            1 => ['start_time' => '15:00', 'end_time' => '15:30'],
            2 => ['start_time' => '15:30', 'end_time' => '16:00'],
            3 => ['start_time' => '16:00', 'end_time' => '16:30'],
            4 => ['start_time' => '16:30', 'end_time' => '17:00'],
            5 => ['start_time' => '17:00', 'end_time' => '17:30'],
            6 => ['start_time' => '17:30', 'end_time' => '18:00'],
            7 => ['start_time' => '19:30', 'end_time' => '20:00'],
            8 => ['start_time' => '20:00', 'end_time' => '20:30'],
            9 => ['start_time' => '20:30', 'end_time' => '21:00'],
            10 => ['start_time' => '21:00', 'end_time' => '21:30'],
        ];

        if (! array_key_exists($validated['schedule_id'], $map)) {
            throw ValidationException::withMessages([
                'schedule_id' => 'Jadwal yang dipilih tidak valid.',
            ]);
        }

        $date = $request->input('date') ? Carbon::parse($request->input('date')) : today();
        $timeSlot = $map[$validated['schedule_id']];

        $booking = DB::transaction(function () use ($validated, $timeSlot, $date): Booking {
            $schedule = Schedule::query()
                ->where('barber_id', $validated['barber_id'])
                ->whereDate('date', $date)
                ->where('start_time', $timeSlot['start_time'])
                ->lockForUpdate()
                ->first();

            if (! $schedule) {
                $schedule = Schedule::query()->create([
                    'barber_id' => $validated['barber_id'],
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $timeSlot['start_time'],
                    'end_time' => $timeSlot['end_time'],
                    'is_available' => true,
                ]);
            }

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

    public function schedule(Request $request): View
    {
        $barber = Barber::query()->where('is_active', true)->findOrFail($request->query('barber_id', 1));
        $dateObj = $request->query('date') ? Carbon::parse($request->query('date')) : today();

        return view('pages.booking.schedule', [
            'barber' => $barber,
            'date' => $dateObj->format('Y-m-d'),
            'dateObj' => $dateObj,
        ]);
    }

    public function confirm(Request $request): View
    {
        $barber = Barber::query()->where('is_active', true)->findOrFail($request->query('barber_id', 1));
        $service = Service::query()->where('is_active', true)->first();
        $date = $request->query('date', today()->format('Y-m-d'));
        $scheduleId = $request->query('schedule_id', 5);
        $timeSlot = [1 => ['start_time' => '15:00', 'end_time' => '15:30'], 2 => ['start_time' => '15:30', 'end_time' => '16:00'], 3 => ['start_time' => '16:00', 'end_time' => '16:30'], 4 => ['start_time' => '16:30', 'end_time' => '17:00'], 5 => ['start_time' => '17:00', 'end_time' => '17:30'], 6 => ['start_time' => '17:30', 'end_time' => '18:00'], 7 => ['start_time' => '19:30', 'end_time' => '20:00'], 8 => ['start_time' => '20:00', 'end_time' => '20:30'], 9 => ['start_time' => '20:30', 'end_time' => '21:00'], 10 => ['start_time' => '21:00', 'end_time' => '21:30']];
        $timeStr = $timeSlot[$scheduleId]['start_time'] ?? '17:00';

        return view('pages.booking.confirm', [
            'barber' => $barber,
            'service' => $service,
            'date' => $date,
            'schedule_id' => $scheduleId,
            'timeStr' => $timeStr,
        ]);
    }

    public function schedules(Request $request): JsonResponse
    {
        $barberId = $request->query('barber_id');
        $date = $request->query('date');

        $dateObj = $date ? Carbon::parse($date) : today();
        $now = now();

        $Result = collect();
        $queryBarberId = $barberId ? (int) $barberId : 1;
        $slots = [
            ['id' => 1, 'start_time' => '15:00', 'end_time' => '15:30'],
            ['id' => 2, 'start_time' => '15:30', 'end_time' => '16:00'],
            ['id' => 3, 'start_time' => '16:00', 'end_time' => '16:30'],
            ['id' => 4, 'start_time' => '16:30', 'end_time' => '17:00'],
            ['id' => 5, 'start_time' => '17:00', 'end_time' => '17:30'],
            ['id' => 6, 'start_time' => '17:30', 'end_time' => '18:00'],
            ['id' => 7, 'start_time' => '19:30', 'end_time' => '20:00'],
            ['id' => 8, 'start_time' => '20:00', 'end_time' => '20:30'],
            ['id' => 9, 'start_time' => '20:30', 'end_time' => '21:00'],
            ['id' => 10, 'start_time' => '21:00', 'end_time' => '21:30'],
        ];
        foreach ($slots as $slot) {
            $isBooked = Booking::query()
                ->where('barber_id', $queryBarberId)
                ->whereDate('booking_date', $dateObj)
                ->where('start_time', $slot['start_time'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            $slotTime = $dateObj->copy()->setTimeFromTimeString($slot['start_time']);
            $sessionEnd = $slotTime->copy()->addMinutes(30);
            $isBlocked = $isBooked || $now->gte($slotTime);

            $available = ! $isBlocked;

            $Result->push([
                'id' => $slot['id'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'is_available' => $available,
                'barber_id' => $barberId ? (int) $barberId : 1,
            ]);
        }

        return response()->json($Result);
    }
}
