<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DashboardDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            BarberSeeder::class,
            ServiceSeeder::class,
            ScheduleSeeder::class,
        ]);

        $barbers = Barber::query()->where('is_active', true)->orderBy('id')->get();
        $services = Service::query()->where('is_active', true)->orderBy('id')->get();

        if ($barbers->count() < 2 || $services->isEmpty()) {
            return;
        }

        $demoBookings = [
            [
                'sequence' => 42,
                'customer' => 'Aditya Pratama',
                'phone' => '081200000101',
                'barber' => $barbers[0],
                'service' => $services[0],
                'start_time' => '15:00',
                'status' => Booking::STATUS_PENDING,
            ],
            [
                'sequence' => 43,
                'customer' => 'Bagas Kurniawan',
                'phone' => '081200000102',
                'barber' => $barbers[1],
                'service' => $services[1] ?? $services[0],
                'start_time' => '16:15',
                'status' => Booking::STATUS_PENDING,
            ],
            [
                'sequence' => 44,
                'customer' => 'Kevin Sanjaya',
                'phone' => '081200000103',
                'barber' => $barbers[0],
                'service' => $services[0],
                'start_time' => '18:00',
                'status' => Booking::STATUS_PENDING,
            ],
            [
                'sequence' => 45,
                'customer' => 'Fauzan Ramli',
                'phone' => '081200000104',
                'barber' => $barbers[1],
                'service' => $services[0],
                'start_time' => '20:30',
                'status' => Booking::STATUS_CONFIRMED,
            ],
            [
                'sequence' => 46,
                'customer' => 'Dion Wiyoko',
                'phone' => '081200000105',
                'barber' => $barbers[0],
                'service' => $services[1] ?? $services[0],
                'start_time' => '14:15',
                'status' => Booking::STATUS_REJECTED,
            ],
        ];

        foreach ($demoBookings as $demoBooking) {
            $bookingDate = today()->toDateString();
            $bookingCode = sprintf('EXC-%s-%03d', today()->format('Ymd'), $demoBooking['sequence']);
            $startTime = $demoBooking['start_time'];
            $endTime = Carbon::createFromFormat('H:i', $startTime)
                ->addMinutes($demoBooking['service']->duration_minutes)
                ->format('H:i');

            $customer = Customer::query()->updateOrCreate(
                ['phone' => $demoBooking['phone']],
                ['name' => $demoBooking['customer']]
            );

            $schedule = Schedule::query()->updateOrCreate(
                [
                    'barber_id' => $demoBooking['barber']->id,
                    'date' => $bookingDate,
                    'start_time' => $startTime,
                ],
                [
                    'end_time' => $endTime,
                    'is_available' => false,
                ]
            );

            $booking = Booking::query()->where('booking_code', $bookingCode)->first();

            if (! $booking) {
                $legacyCodes = [
                    sprintf('DEMO-%s-%03d', today()->format('Ymd'), $demoBooking['sequence'] - 41),
                    sprintf('DEMO-%03d', $demoBooking['sequence'] - 41),
                ];

                $booking = Booking::query()->whereIn('booking_code', $legacyCodes)->first();
                $booking?->update(['booking_code' => $bookingCode]);
            }

            Booking::query()->updateOrCreate(
                ['booking_code' => $bookingCode],
                [
                    'customer_id' => $customer->id,
                    'barber_id' => $demoBooking['barber']->id,
                    'service_id' => $demoBooking['service']->id,
                    'schedule_id' => $schedule->id,
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => $demoBooking['status'],
                    'notes' => 'Data contoh dashboard',
                    'rejection_reason' => $demoBooking['status'] === Booking::STATUS_REJECTED
                        ? 'Data demo untuk pratinjau dashboard.'
                        : null,
                ]
            );
        }
    }
}
