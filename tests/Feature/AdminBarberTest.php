<?php

namespace Tests\Feature;

use App\Models\Barber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBarberTest extends TestCase
{
    use RefreshDatabase;

    protected Barber $barber;

    protected function setUp(): void
    {
        parent::setUp();

        $this->barber = Barber::create([
            'name' => 'Bramantyo',
            'chair_number' => 1,
            'role_title' => 'Founder / Senior',
            'specialization' => 'Atelier Specialist & Grooming Director',
            'skills_tags' => 'Classic Scissor Cut, Gentlemen Taper Fade',
            'availability_status' => 'online',
            'rating' => 4.96,
            'review_count' => 124,
            'scheduled_sessions_count' => 6,
            'accept_public_booking' => true,
            'is_active' => true,
            'description' => 'Founder & Senior Barber',
        ]);
    }

    public function test_barber_index_page_loads_successfully(): void
    {
        $response = $this->get(route('admin.barbers.index'));

        $response->assertStatus(200)
            ->assertSee('Kelola Master Barber')
            ->assertSee('Bramantyo')
            ->assertSee('KURSI 01')
            ->assertSee('ONLINE')
            ->assertSee('Founder / Senior')
            ->assertSee('4.96')
            ->assertSee('Terima Booking Publik');
    }

    public function test_admin_can_store_new_barber(): void
    {
        $response = $this->post(route('admin.barbers.store'), [
            'name' => 'Dimas Wicaksono',
            'chair_number' => 2,
            'role_title' => 'Master Barber',
            'specialization' => 'Beard Architecture & Skin Fade Pro',
            'skills_tags' => 'Beard Sculpting, Modern Skin Fade',
            'availability_status' => 'online',
        ]);

        $response->assertRedirect(route('admin.barbers.index'));

        $this->assertDatabaseHas('barbers', [
            'name' => 'Dimas Wicaksono',
            'chair_number' => 2,
            'role_title' => 'Master Barber',
        ]);
    }

    public function test_admin_can_toggle_public_booking_via_api(): void
    {
        $this->assertTrue($this->barber->accept_public_booking);

        $response = $this->postJson(route('admin.barbers.toggle-booking', $this->barber));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'accept_public_booking' => false,
            ]);

        $this->assertFalse($this->barber->fresh()->accept_public_booking);
    }

    public function test_admin_can_toggle_active_status_via_api(): void
    {
        $this->assertTrue($this->barber->is_active);

        $response = $this->postJson(route('admin.barbers.toggle-active', $this->barber));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_active' => false,
            ]);

        $this->assertFalse($this->barber->fresh()->is_active);
    }

    public function test_admin_can_update_barber(): void
    {
        $response = $this->put(route('admin.barbers.update', $this->barber), [
            'name' => 'Bramantyo Wicaksono',
            'role_title' => 'Director',
            'specialization' => 'Master Grooming',
            'availability_status' => 'online',
        ]);

        $response->assertRedirect(route('admin.barbers.index'));

        $this->assertDatabaseHas('barbers', [
            'id' => $this->barber->id,
            'name' => 'Bramantyo Wicaksono',
            'role_title' => 'Director',
        ]);
    }

    public function test_admin_can_destroy_barber(): void
    {
        $response = $this->delete(route('admin.barbers.destroy', $this->barber));

        $response->assertRedirect(route('admin.barbers.index'));

        $this->assertDatabaseMissing('barbers', [
            'id' => $this->barber->id,
        ]);
    }
}
