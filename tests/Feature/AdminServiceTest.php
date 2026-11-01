<?php

namespace Tests\Feature;

use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    protected Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Service::create([
            'name' => 'Signature Royal Grooming',
            'category' => Service::CAT_HAIRCUT,
            'badge' => 'BEST SELLER',
            'description' => 'Potong rambut presisi, konsultasi bentuk wajah, cuci rambut 2x.',
            'price' => 120000,
            'duration_minutes' => 60,
            'is_active' => true,
            'is_featured' => true,
            'show_in_widget' => true,
        ]);
    }

    public function test_service_index_page_loads_successfully(): void
    {
        $response = $this->get(route('admin.services.index'));

        $response->assertStatus(200)
            ->assertSee('Kelola Katalog Layanan')
            ->assertSee('KATALOG MASTER')
            ->assertSee('TERAKHIR SYNC')
            ->assertSee('TAMBAH LAYANAN BARU')
            ->assertSee('TOTAL LAYANAN')
            ->assertSee('LAYANAN AKTIF')
            ->assertSee('NONAKTIF')
            ->assertSee('RATA-RATA DURASI')
            ->assertSee('Signature Royal Grooming')
            ->assertSee('BEST SELLER')
            ->assertSee('HAIRCUT &amp; STYLING', false)
            ->assertSee('60 MNT')
            ->assertSee('Rp 120.000')
            ->assertSee('Live di Online Widget');
    }

    public function test_sidebar_navigation_contains_all_required_sections_and_items(): void
    {
        $response = $this->get(route('admin.services.index'));

        $response->assertStatus(200)
            // Header
            ->assertSee('EXCELLENT')
            ->assertSee('ATELIER')
            // Navigasi Utama
            ->assertSee('NAVIGASI UTAMA')
            ->assertSee('Dashboard')
            ->assertSee('Kelola Booking')
            ->assertSee('Kelola Layanan')
            ->assertSee('Kelola Barber')
            ->assertSee('Jadwal Operasional')
            // Konten & Lokasi
            ->assertSee('KONTEN & LOKASI')
            ->assertSee('Kelola Landing Page')
            ->assertSee('Kelola Lokasi')
            ->assertSee('Laporan')
            // Hak Akses & Akun
            ->assertSee('HAK AKSES & AKUN')
            ->assertSee('Kelola Admin')
            ->assertSee('Pengaturan Akun')
            // Footer
            ->assertSee('BO')
            ->assertSee('Bramantyo Owner')
            ->assertSee('Online')
            ->assertSee('• Master')
            ->assertSee('Keluar')
            ->assertSee('LOGOUT');
    }

    public function test_admin_can_store_new_service(): void
    {
        $response = $this->post(route('admin.services.store'), [
            'name' => 'Beard Sculpting & Hot Towel Shave',
            'category' => Service::CAT_BEARD,
            'price' => 65000,
            'duration_minutes' => 35,
            'description' => 'Pembentukan jenggot dan kumis presisi dengan pisau silet steril.',
        ]);

        $response->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'name' => 'Beard Sculpting & Hot Towel Shave',
            'category' => Service::CAT_BEARD,
            'price' => 65000,
        ]);
    }

    public function test_admin_can_toggle_widget_visibility_via_api(): void
    {
        $this->assertTrue($this->service->show_in_widget);

        $response = $this->postJson(route('admin.services.toggle-widget', $this->service));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'show_in_widget' => false,
            ]);

        $this->assertFalse($this->service->fresh()->show_in_widget);
    }

    public function test_admin_can_toggle_active_status_via_api(): void
    {
        $this->assertTrue($this->service->is_active);

        $response = $this->postJson(route('admin.services.toggle-active', $this->service));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_active' => false,
            ]);

        $this->assertFalse($this->service->fresh()->is_active);
    }

    public function test_admin_can_update_service(): void
    {
        $response = $this->put(route('admin.services.update', $this->service), [
            'name' => 'Signature Royal Grooming VIP',
            'price' => 150000,
            'duration_minutes' => 75,
            'category' => Service::CAT_HAIRCUT,
        ]);

        $response->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseHas('services', [
            'id' => $this->service->id,
            'name' => 'Signature Royal Grooming VIP',
            'price' => 150000,
        ]);
    }

    public function test_admin_can_destroy_service(): void
    {
        $response = $this->delete(route('admin.services.destroy', $this->service));

        $response->assertRedirect(route('admin.services.index'));

        $this->assertDatabaseMissing('services', [
            'id' => $this->service->id,
        ]);
    }
}
