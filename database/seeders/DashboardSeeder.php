<?php

namespace Database\Seeders;

use App\Models\Barber;
use App\Models\BarbershopSetting;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardSeeder extends Seeder
{
    /**
     * Seed realistic data for the Admin Dashboard exactly matching the Figma design screenshot.
     */
    public function run(): void
    {
        $today = Carbon::today();

        // 1. Settings Studio
        BarbershopSetting::query()->updateOrCreate(
            ['name' => 'Excellent Atelier'],
            [
                'address' => 'Jl. Senopati No. 42B, Kebayoran Baru, Jakarta Selatan',
                'phone' => '+62 21 5550 1928',
                'opening_time' => '10:00:00',
                'closing_time' => '21:00:00',
            ]
        );

        // 2. Data Barbers sesuai Screenshot Figma: Bramantyo (01), Dimas Wicaksono (02), Rendy Setiawan (03), Fajar Nugraha (04)
        $barber1 = Barber::query()->updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Bramantyo',
                'chair_number' => 1,
                'role_title' => 'Founder / Senior',
                'specialization' => 'Atelier Specialist & Grooming Director',
                'skills_tags' => 'Classic Scissor Cut, Gentlemen Taper Fade, Traditional Straight Razor',
                'availability_status' => 'online',
                'rating' => 4.96,
                'review_count' => 124,
                'scheduled_sessions_count' => 6,
                'accept_public_booking' => true,
                'is_active' => true,
                'description' => 'Master Barber (Spesialis Klasik & Razor Fade)',
            ]
        );

        $barber2 = Barber::query()->updateOrCreate(
            ['id' => 2],
            [
                'name' => 'Dimas Wicaksono',
                'chair_number' => 2,
                'role_title' => 'Master Barber',
                'specialization' => 'Beard Architecture & Skin Fade Pro',
                'skills_tags' => 'Beard Sculpting, Modern Skin Fade, Ritual Hot Towel',
                'availability_status' => 'online',
                'rating' => 4.98,
                'review_count' => 98,
                'scheduled_sessions_count' => 7,
                'accept_public_booking' => true,
                'is_active' => true,
                'description' => 'Senior Stylist (Spesialis Modern Textured & Beard)',
            ]
        );

        $barber3 = Barber::query()->updateOrCreate(
            ['id' => 3],
            [
                'name' => 'Rendy Setiawan',
                'chair_number' => 3,
                'role_title' => 'Barber Stylist',
                'specialization' => 'Contemporary Cut & Scalp Specialist',
                'skills_tags' => 'Textured Crop, French Crop, Scalp Detox Ritual',
                'availability_status' => 'online',
                'rating' => 4.91,
                'review_count' => 76,
                'scheduled_sessions_count' => 6,
                'accept_public_booking' => true,
                'is_active' => true,
                'description' => 'Artistic Barber (Spesialis Pompadour & Styling)',
            ]
        );

        $barber4 = Barber::query()->updateOrCreate(
            ['id' => 4],
            [
                'name' => 'Fajar Nugraha',
                'chair_number' => 4,
                'role_title' => 'Junior Stylist',
                'specialization' => 'Sedang Dalam Program Sertifikasi',
                'skills_tags' => 'Basic Cut & Assist',
                'availability_status' => 'cuti',
                'rating' => 4.80,
                'review_count' => 15,
                'scheduled_sessions_count' => 0,
                'accept_public_booking' => false,
                'warning_note' => 'Sedang masa pelatihan lanjutan atelier dan cuti operasional. Profil tidak muncul di web booking.',
                'is_active' => false,
                'description' => 'Junior Stylist dalam program sertifikasi atelier.',
            ]
        );

        // 3. Data Layanan sesuai Screenshot Figma
        $service1 = Service::query()->updateOrCreate(
            ['name' => 'Signature Royal Grooming'],
            [
                'category' => Service::CAT_HAIRCUT,
                'badge' => 'BEST SELLER',
                'price' => 120000,
                'duration_minutes' => 60,
                'description' => 'Potong rambut presisi, konsultasi bentuk wajah, cuci rambut 2x, pijat kepala & pundak, hot towel aromaterapi, tonic styling.',
                'is_active' => true,
                'is_featured' => true,
                'show_in_widget' => true,
            ]
        );

        $service2 = Service::query()->updateOrCreate(
            ['name' => 'Classic Gentleman Fade'],
            [
                'category' => Service::CAT_HAIRCUT,
                'badge' => null,
                'price' => 85000,
                'duration_minutes' => 45,
                'description' => 'Cukur fade bertahap presisi (low/mid/high taper fade), cuci rambut, pendingin kulit kepala, styling pomade.',
                'is_active' => true,
                'is_featured' => false,
                'show_in_widget' => true,
            ]
        );

        $service3 = Service::query()->updateOrCreate(
            ['name' => 'Beard Sculpting & Hot Towel Shave'],
            [
                'category' => Service::CAT_BEARD,
                'badge' => null,
                'price' => 65000,
                'duration_minutes' => 35,
                'description' => 'Pembentukan jenggot dan kumis presisi dengan pisau silet steril, kompres handuk hangat rempah, beard oil organic.',
                'is_active' => true,
                'is_featured' => false,
                'show_in_widget' => true,
            ]
        );

        $service4 = Service::query()->updateOrCreate(
            ['name' => 'Scalp Revitalizing Detox'],
            [
                'category' => Service::CAT_TREATMENT,
                'badge' => null,
                'price' => 75000,
                'duration_minutes' => 40,
                'description' => 'Terapi pembersihan pori-pori kulit kepala, scrub ketombe, serum pendingin, dan pijatan relaksasi mendalam.',
                'is_active' => true,
                'is_featured' => false,
                'show_in_widget' => true,
            ]
        );

        $service5 = Service::query()->updateOrCreate(
            ['name' => 'Royal Hot Towel Shave Only'],
            [
                'category' => Service::CAT_BEARD,
                'badge' => null,
                'price' => 50000,
                'duration_minutes' => 30,
                'description' => 'Cukur bersih jenggot/kumis tanpa potong rambut dengan busa hangat dan pisau silet ganti baru.',
                'is_active' => true,
                'is_featured' => false,
                'show_in_widget' => true,
            ]
        );

        $service6 = Service::query()->updateOrCreate(
            ['name' => 'Premium Hair Coloring'],
            [
                'category' => Service::CAT_HAIRCUT,
                'badge' => 'RESTOCK BAHAN',
                'price' => 180000,
                'duration_minutes' => 70,
                'description' => 'Pewarnaan rambut eksklusif pria (hitam pekat, dark ash grey). Sedang restock produk kimia import.',
                'is_active' => false,
                'is_featured' => false,
                'show_in_widget' => false,
            ]
        );

        // 4. Data Pelanggan sesuai Screenshot Figma
        $cust1 = Customer::query()->firstOrCreate(['phone' => '+62 812-9844-3321'], ['name' => 'Aditya Pratama']);
        $cust2 = Customer::query()->firstOrCreate(['phone' => '+62 878-1120-8988'], ['name' => 'Bagas Kurniawan']);
        $cust3 = Customer::query()->firstOrCreate(['phone' => '+62 811-2099-1234'], ['name' => 'Kevin Sanjaya']);
        $cust4 = Customer::query()->firstOrCreate(['phone' => '+62 812-3344-5566'], ['name' => 'Fauzan Ramli']);
        $cust5 = Customer::query()->firstOrCreate(['phone' => '+62 813-4455-6677'], ['name' => 'Glenn Pratama']);
        $cust6 = Customer::query()->firstOrCreate(['phone' => '+62 814-5566-7788'], ['name' => 'Dion Wiyoko']);

        // Pelanggan Tambahan untuk memenuhi total 24 booking
        $extraCustomers = [];
        for ($i = 1; $i <= 18; $i++) {
            $extraCustomers[] = Customer::query()->firstOrCreate(
                ['phone' => '+62 812-0000-'.sprintf('%04d', $i)],
                ['name' => 'Pelanggan Studio '.sprintf('%02d', $i)]
            );
        }

        // Hapus data booking & jadwal hari ini sebelum re-seed agar angka presisi sesuai Figma
        Booking::query()->where('booking_code', 'like', 'EXC-%')->delete();
        Booking::query()->whereDate('booking_date', $today->toDateString())->delete();
        Schedule::query()->whereDate('date', $today->toDateString())->delete();

        // 5. Buat Jadwal untuk Hari Ini
        $barbers = [$barber1, $barber2, $barber3];
        $allSchedules = [];

        for ($h = 10; $h < 22; $h++) {
            foreach ($barbers as $b) {
                $sched = Schedule::query()->create([
                    'barber_id' => $b->id,
                    'date' => $today->toDateString(),
                    'start_time' => sprintf('%02d:00:00', $h),
                    'end_time' => sprintf('%02d:45:00', $h),
                    'is_available' => false,
                ]);
                $allSchedules[] = $sched;
            }
        }

        // 6. Data 3 Booking PENDING (Antrean Perlu Ditinjau Segera) sesuai Screenshot Figma
        Booking::query()->create([
            'booking_code' => 'EXC-9402',
            'customer_id' => $cust1->id,
            'barber_id' => $barber1->id,
            'service_id' => $service1->id,
            'schedule_id' => $allSchedules[0]->id,
            'booking_date' => $today,
            'start_time' => '17:00:00',
            'end_time' => '17:45:00',
            'status' => Booking::STATUS_PENDING,
            'notes' => 'Potongan taper fade rapi untuk acara wisuda.',
            'created_at' => now()->subMinutes(5),
        ]);

        Booking::query()->create([
            'booking_code' => 'EXC-9403',
            'customer_id' => $cust2->id,
            'barber_id' => $barber2->id,
            'service_id' => $service2->id,
            'schedule_id' => $allSchedules[1]->id,
            'booking_date' => $today,
            'start_time' => '18:00:00',
            'end_time' => '18:30:00',
            'status' => Booking::STATUS_PENDING,
            'notes' => 'Kulit sensitif, gunakan handuk hangat aromaterapi.',
            'created_at' => now()->subMinutes(11),
        ]);

        Booking::query()->create([
            'booking_code' => 'EXC-9404',
            'customer_id' => $cust3->id,
            'barber_id' => $barber3->id,
            'service_id' => $service3->id,
            'schedule_id' => $allSchedules[2]->id,
            'booking_date' => $today,
            'start_time' => '20:15:00',
            'end_time' => '21:00:00',
            'status' => Booking::STATUS_PENDING,
            'notes' => 'Hot towel treatment dengan styling pomade.',
            'created_at' => now()->subMinutes(14),
        ]);

        // 7. Data Jadwal Berjalan Utama sesuai Screenshot Figma
        // Item 1 (Selesai): Fauzan Ramli • Classic Cut (15:00 WIB, 60 Menit, Kursi 01 Bramantyo)
        Booking::query()->create([
            'booking_code' => 'EXC-DON001',
            'customer_id' => $cust4->id,
            'barber_id' => $barber1->id,
            'service_id' => $service4->id,
            'schedule_id' => $allSchedules[3]->id,
            'booking_date' => $today,
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'status' => Booking::STATUS_COMPLETED,
            'notes' => null,
        ]);

        // Item 2 (Sedang Cukur): Glenn Pratama • Royal Cut (16:15 WIB, 60 Menit, Kursi 02 Dimas W.)
        Booking::query()->create([
            'booking_code' => 'EXC-NOW002',
            'customer_id' => $cust5->id,
            'barber_id' => $barber2->id,
            'service_id' => $service5->id,
            'schedule_id' => $allSchedules[4]->id,
            'booking_date' => $today,
            'start_time' => '16:15:00',
            'end_time' => '17:15:00',
            'status' => Booking::STATUS_IN_PROGRESS,
            'notes' => 'Sedang potong rambut di kursi 02.',
        ]);

        // Item 3 (Jeda Terjadwal): Istirahat Maghrib & Sanitasi (18:00 WIB, 45 Menit)
        Booking::query()->create([
            'booking_code' => 'EXC-BRK003',
            'customer_id' => $cust1->id,
            'barber_id' => $barber1->id,
            'service_id' => $service1->id,
            'schedule_id' => $allSchedules[5]->id,
            'booking_date' => $today,
            'start_time' => '18:00:00',
            'end_time' => '18:45:00',
            'status' => Booking::STATUS_BREAK,
            'notes' => 'Jeda Maghrib & sanitasi kursi atelier.',
        ]);

        // Item 4 (Mendatang / Datang): Dion Wiyoko • Executive Treatment (20:30 WIB, Kursi 01 Bramantyo)
        Booking::query()->create([
            'booking_code' => 'EXC-UPC004',
            'customer_id' => $cust6->id,
            'barber_id' => $barber1->id,
            'service_id' => $service6->id,
            'schedule_id' => $allSchedules[6]->id,
            'booking_date' => $today,
            'start_time' => '20:30:00',
            'end_time' => '21:30:00',
            'status' => Booking::STATUS_CONFIRMED,
            'notes' => 'Pelanggan VIP konfirmasi hadir tepat waktu.',
        ]);

        // 8. Tambahkan 13 Booking SELESAI lainnya agar total Selesai = 14
        for ($i = 2; $i <= 14; $i++) {
            $sched = $allSchedules[6 + $i] ?? $allSchedules[0];
            $c = $extraCustomers[$i - 2];
            Booking::query()->create([
                'booking_code' => 'EXC-DON'.sprintf('%03d', $i),
                'customer_id' => $c->id,
                'barber_id' => $sched->barber_id,
                'service_id' => $service1->id,
                'schedule_id' => $sched->id,
                'booking_date' => $today,
                'start_time' => sprintf('%02d:00:00', 10 + ($i % 5)),
                'end_time' => sprintf('%02d:45:00', 10 + ($i % 5)),
                'status' => Booking::STATUS_COMPLETED,
                'notes' => null,
            ]);
        }

        // 9. Tambahkan 1 Booking DIBATALKAN agar total Dibatalkan = 01
        $schedRej = $allSchedules[21] ?? $allSchedules[1];
        Booking::query()->create([
            'booking_code' => 'EXC-REJ001',
            'customer_id' => $extraCustomers[15]->id,
            'barber_id' => $schedRej->barber_id,
            'service_id' => $service1->id,
            'schedule_id' => $schedRej->id,
            'booking_date' => $today,
            'start_time' => '14:00:00',
            'end_time' => '14:45:00',
            'status' => Booking::STATUS_REJECTED,
            'rejection_reason' => 'Pelanggan meminta reschedule karena ada urusan keluarga mendadak.',
            'notes' => 'Minta dijadwalkan ulang.',
        ]);

        // 10. Tambahkan 2 Booking MENDATANG lainnya agar total booking hari ini = 24
        // (3 pending + 14 completed + 1 in_progress + 1 break + 4 confirmed + 1 rejected = 24)
        for ($k = 1; $k <= 2; $k++) {
            $schedConf = $allSchedules[23 + $k] ?? $allSchedules[2];
            Booking::query()->create([
                'booking_code' => 'EXC-UPC'.sprintf('%03d', 5 + $k),
                'customer_id' => $extraCustomers[15 + $k]->id,
                'barber_id' => $schedConf->barber_id,
                'service_id' => $service2->id,
                'schedule_id' => $schedConf->id,
                'booking_date' => $today,
                'start_time' => '21:00:00',
                'end_time' => '21:30:00',
                'status' => Booking::STATUS_CONFIRMED,
                'notes' => null,
            ]);
        }

        // Total Booking Hari Ini:
        // 3 Pending + 14 Selesai + 1 Sedang Cukur + 1 Jeda + 4 Mendatang + 1 Dibatalkan = 24 Total Booking!
    }
}
