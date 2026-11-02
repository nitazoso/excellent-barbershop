<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PendingBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barbers = Barber::query()->where('is_active', true)->orderBy('id')->get();
        $services = Service::query()->where('is_active', true)->orderBy('id')->get();

        if ($barbers->count() < 3 || $services->isEmpty()) {
            return;
        }

        $pendingCount = Booking::query()->where('status', Booking::STATUS_PENDING)->count();
        $bookingDate = today()->addDay();
        $samples = [
            [
                'booking_code' => 'EXC-WAIT001',
                'name' => 'Aris Munandar',
                'phone' => '+62 812-2345-6789',
                'membership_type' => 'vip',
                'start_time' => '15:30:00',
            ],
            [
                'booking_code' => 'EXC-WAIT002',
                'name' => 'Glenn Alinskie',
                'phone' => '+62 812-9876-5432',
                'membership_type' => 'regular',
                'start_time' => '16:00:00',
            ],
            [
                'booking_code' => 'EXC-WAIT003',
                'name' => 'Bagas Pratama',
                'phone' => '+62 813-4567-8901',
                'membership_type' => 'regular',
                'start_time' => '16:30:00',
            ],
        ];

        foreach ($samples as $index => $sample) {
            if ($pendingCount >= 3) {
                break;
            }

            if (Booking::query()->where('booking_code', $sample['booking_code'])->exists()) {
                continue;
            }

            $barber = $barbers[$index];
            $service = $services[$index % $services->count()];
            $startTime = Carbon::createFromFormat('H:i:s', $sample['start_time']);
            $endTime = $startTime->copy()->addMinutes($service->duration_minutes);
            $schedule = Schedule::query()->firstOrCreate(
                [
                    'barber_id' => $barber->id,
                    'date' => $bookingDate->toDateString(),
                    'start_time' => $startTime->format('H:i:s'),
                ],
                [
                    'end_time' => $endTime->format('H:i:s'),
                    'is_available' => false,
                ],
            );

            if (Booking::query()->where('schedule_id', $schedule->id)->exists()) {
                continue;
            }

            $schedule->update(['is_available' => false]);
            $customer = Customer::query()->updateOrCreate(
                ['phone' => $sample['phone']],
                [
                    'name' => $sample['name'],
                    'membership_type' => $sample['membership_type'],
                ],
            );

            Booking::query()->create([
                'booking_code' => $sample['booking_code'],
                'customer_id' => $customer->id,
                'barber_id' => $barber->id,
                'service_id' => $service->id,
                'schedule_id' => $schedule->id,
                'booking_date' => $bookingDate->toDateString(),
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'status' => Booking::STATUS_PENDING,
                'notes' => 'Booking contoh yang menunggu konfirmasi admin.',
            ]);

            $pendingCount++;
        }
    }
}
