<?php

namespace Tests\Feature;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected Barber $barber;

    protected Service $service;

    protected Customer $customer;

    protected Schedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barber = Barber::create([
            'name' => 'Bramantyo',
            'description' => 'Master Barber',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'name' => 'Gentleman Haircut',
            'price' => 50000,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        $this->customer = Customer::create([
            'name' => 'John Doe',
            'phone' => '081234567890',
        ]);

        $this->schedule = Schedule::create([
            'barber_id' => $this->barber->id,
            'date' => Carbon::today(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'is_available' => false,
        ]);
    }

    public function test_admin_can_view_dashboard_with_operational_stats(): void
    {
        Booking::create([
            'booking_code' => 'EB-TEST1',
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $this->schedule->id,
            'booking_date' => Carbon::today(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => Booking::STATUS_PENDING,
        ]);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('EXCELLENT');
        $response->assertSee('Antrean Perlu Ditinjau Segera');
        $response->assertSee('Jadwal Booking Hari Ini');
        $response->assertSee('Bramantyo');
        $response->assertSee('EB-TEST1');
    }

    public function test_admin_can_fetch_dashboard_stats_api(): void
    {
        Booking::create([
            'booking_code' => 'EB-TEST2',
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $this->schedule->id,
            'booking_date' => Carbon::today(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => Booking::STATUS_PENDING,
        ]);

        $response = $this->getJson(route('admin.dashboard.stats'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'stats' => [
                    'total_today',
                    'pending_review',
                    'in_progress_today',
                    'completed_today',
                    'rejected_today',
                    'confirmed_today',
                ],
                'pending_count',
                'pending_bookings',
                'today_schedules',
            ]);
    }

    public function test_admin_can_bulk_confirm_all_pending_bookings(): void
    {
        $sched2 = Schedule::create([
            'barber_id' => $this->barber->id,
            'date' => Carbon::today(),
            'start_time' => '11:00:00',
            'end_time' => '11:45:00',
            'is_available' => false,
        ]);

        $b1 = Booking::create([
            'booking_code' => 'EB-BLK1',
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $this->schedule->id,
            'booking_date' => Carbon::today(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => Booking::STATUS_PENDING,
        ]);

        $b2 = Booking::create([
            'booking_code' => 'EB-BLK2',
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $sched2->id,
            'booking_date' => Carbon::today(),
            'start_time' => '11:00:00',
            'end_time' => '11:45:00',
            'status' => Booking::STATUS_PENDING,
        ]);

        $response = $this->postJson(route('admin.bookings.bulk-confirm'));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'confirmed_count' => 2,
            ]);

        $this->assertEquals(Booking::STATUS_CONFIRMED, $b1->fresh()->status);
        $this->assertEquals(Booking::STATUS_CONFIRMED, $b2->fresh()->status);
    }

    public function test_admin_can_update_booking_status(): void
    {
        $booking = Booking::create([
            'booking_code' => 'EB-STAT1',
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $this->schedule->id,
            'booking_date' => Carbon::today(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        $response = $this->patchJson(route('admin.bookings.status', $booking), [
            'status' => Booking::STATUS_IN_PROGRESS,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals(Booking::STATUS_IN_PROGRESS, $booking->fresh()->status);

        // Update ke completed
        $response2 = $this->patchJson(route('admin.bookings.status', $booking), [
            'status' => Booking::STATUS_COMPLETED,
        ]);

        $response2->assertStatus(200);
        $this->assertEquals(Booking::STATUS_COMPLETED, $booking->fresh()->status);
    }

    public function test_quick_action_add_barber(): void
    {
        $response = $this->postJson(route('admin.barbers.store'), [
            'name' => 'Dimas Wahyu',
            'description' => 'Senior Hair Stylist',
            'is_active' => true,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('barbers', [
            'name' => 'Dimas Wahyu',
        ]);
    }

    public function test_quick_action_add_service(): void
    {
        $response = $this->postJson(route('admin.services.store'), [
            'name' => 'Beard Grooming Express',
            'price' => 35000,
            'duration_minutes' => 30,
            'description' => 'Rapikan jenggot dan kumis',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('services', [
            'name' => 'Beard Grooming Express',
            'price' => 35000,
        ]);
    }

    public function test_quick_action_update_settings(): void
    {
        $response = $this->postJson(route('admin.settings.update'), [
            'opening_time' => '09:00:00',
            'closing_time' => '22:00:00',
            'phone' => '+62 811 2233 44',
            'address' => 'Senopati Atelier No. 42B',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('barbershop_settings', [
            'opening_time' => '09:00:00',
            'closing_time' => '22:00:00',
        ]);
    }

    public function test_filter_today_schedules_by_barber(): void
    {
        $barber2 = Barber::create([
            'name' => 'Rian Pratama',
            'is_active' => true,
        ]);

        $schedBarber2 = Schedule::create([
            'barber_id' => $barber2->id,
            'date' => Carbon::today(),
            'start_time' => '13:00:00',
            'end_time' => '13:45:00',
            'is_available' => false,
        ]);

        Booking::create([
            'booking_code' => 'EB-BRAM1',
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $this->schedule->id,
            'booking_date' => Carbon::today(),
            'start_time' => '10:00:00',
            'end_time' => '10:45:00',
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        Booking::create([
            'booking_code' => 'EB-RIAN1',
            'customer_id' => $this->customer->id,
            'barber_id' => $barber2->id,
            'service_id' => $this->service->id,
            'schedule_id' => $schedBarber2->id,
            'booking_date' => Carbon::today(),
            'start_time' => '13:00:00',
            'end_time' => '13:45:00',
            'status' => Booking::STATUS_CONFIRMED,
        ]);

        $response = $this->get(route('admin.dashboard', ['barber_id' => $this->barber->id]));

        $response->assertStatus(200);
        $response->assertSee('EB-BRAM1');
        $response->assertDontSee('EB-RIAN1');
    }
}
