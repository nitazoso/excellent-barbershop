<?php

namespace Tests\Feature;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBookingTest extends TestCase
{
    use RefreshDatabase;

    protected Customer $customer;
    protected Barber $barber;
    protected Service $service;
    protected Schedule $schedule;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barber = Barber::create([
            'name' => 'Budi Barber',
            'photo' => null,
            'description' => 'Senior Barber',
            'is_active' => true,
        ]);

        $this->service = Service::create([
            'name' => 'Gentleman Haircut',
            'description' => 'Potong rambut dan cuci',
            'price' => 50000,
            'duration_minutes' => 45,
            'is_active' => true,
        ]);

        $this->schedule = Schedule::create([
            'barber_id' => $this->barber->id,
            'date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'is_available' => false,
        ]);

        $this->customer = Customer::create([
            'name' => 'John Doe',
            'phone' => '081234567890',
        ]);
    }

    protected function createBooking(array $attributes = []): Booking
    {
        return Booking::create(array_merge([
            'booking_code' => 'EB-TEST'.rand(1000, 9999),
            'customer_id' => $this->customer->id,
            'barber_id' => $this->barber->id,
            'service_id' => $this->service->id,
            'schedule_id' => $this->schedule->id,
            'booking_date' => now()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'pending',
            'notes' => 'Catatan test',
        ], $attributes));
    }

    public function test_can_view_bookings_page(): void
    {
        $this->createBooking();

        $response = $this->get(route('admin.bookings'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Booking');
        $response->assertSee('John Doe');
    }

    public function test_can_confirm_booking(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $response = $this->patch(route('admin.bookings.confirm', $booking));

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
            'rejection_reason' => null,
        ]);
    }

    public function test_can_confirm_booking_via_json_api(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $response = $this->patchJson(route('admin.bookings.confirm', $booking));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Booking berhasil dikonfirmasi.',
            ]);

        $this->assertEquals('confirmed', $booking->fresh()->status);
    }

    public function test_can_reject_booking_with_reason(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $response = $this->patch(route('admin.bookings.reject', $booking), [
            'rejection_reason' => 'Barber berhalangan hadir',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'rejected',
            'rejection_reason' => 'Barber berhalangan hadir',
        ]);

        // Pastikan jadwal kembali tersedia
        $this->assertTrue((bool) $booking->schedule->fresh()->is_available);
    }

    public function test_reject_booking_fails_without_reason(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $response = $this->patch(route('admin.bookings.reject', $booking), [
            'rejection_reason' => '',
        ]);

        $response->assertSessionHasErrors('rejection_reason');
        $this->assertEquals('pending', $booking->fresh()->status);
    }

    public function test_reject_booking_fails_without_reason_via_json(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $response = $this->patchJson(route('admin.bookings.reject', $booking), [
            'rejection_reason' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['rejection_reason']);
    }

    public function test_can_filter_bookings_by_status(): void
    {
        $pendingBooking = $this->createBooking(['status' => 'pending', 'booking_code' => 'EB-PENDING1']);
        $confirmedBooking = $this->createBooking(['status' => 'confirmed', 'booking_code' => 'EB-CONFIRM1']);

        $response = $this->get(route('admin.bookings', ['status' => 'confirmed']));

        $response->assertStatus(200);
        $response->assertSee('EB-CONFIRM1');
        $response->assertDontSee('EB-PENDING1');
    }

    public function test_can_access_report_endpoint(): void
    {
        $this->createBooking(['status' => 'confirmed']);

        $response = $this->getJson(route('admin.bookings.report'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'summary' => [
                    'total',
                    'pending',
                    'confirmed',
                    'rejected',
                    'revenue',
                ],
                'filters',
                'data',
            ]);
    }
}

