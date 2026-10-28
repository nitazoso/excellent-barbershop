<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to mock Socialite Google driver.
     */
    protected function mockGoogleUser(string $email, string $id = 'google-id-123', ?string $name = 'Nama Google', ?string $avatar = 'https://lh3.googleusercontent.com/avatar.jpg'): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($id);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getName')->andReturn($name);
        $socialiteUser->shouldReceive('getAvatar')->andReturn($avatar);

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_owner_can_view_admin_list(): void
    {
        $owner = User::factory()->owner()->create(['email' => 'owner@example.com']);

        $response = $this->actingAs($owner)->get(route('admin.admins.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Admin');
        $response->assertSee('Tambah Admin Baru');
        $response->assertSee('owner@example.com');
    }

    public function test_regular_admin_can_view_list_but_cannot_add_admin(): void
    {
        $admin = User::factory()->admin()->create(['email' => 'admin@example.com']);

        $response = $this->actingAs($admin)->get(route('admin.admins.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Admin');
        $response->assertDontSee('TAMBAH ADMIN');

        // Direct POST attempt by regular admin should be rejected with 403
        $storeResponse = $this->actingAs($admin)->post(route('admin.admins.store'), [
            'email' => 'newcandidate@example.com',
        ]);

        $storeResponse->assertStatus(403);
    }

    public function test_point_2_owner_adds_new_email_and_that_person_can_login_with_google_and_enter_dashboard(): void
    {
        $owner = User::factory()->owner()->create([
            'name' => 'Owner Utama',
            'email' => 'owner@example.com',
        ]);

        // Step 1: Owner menambah email admin baru
        $storeResponse = $this->actingAs($owner)->post(route('admin.admins.store'), [
            'email' => 'newadmin@gmail.com',
        ]);

        $storeResponse->assertRedirect(route('admin.admins.index'));
        $storeResponse->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@gmail.com',
            'role' => 'admin',
            'is_active' => true,
            'created_by' => $owner->id,
        ]);

        // Logout owner
        $this->post(route('admin.logout'));
        $this->assertGuest();

        // Step 2: Orang tersebut login pertama kali dengan Google
        $this->mockGoogleUser(
            email: 'newadmin@gmail.com',
            id: 'google-newadmin-99',
            name: 'Budi Santoso',
            avatar: 'https://lh3.googleusercontent.com/budi.jpg'
        );

        $callbackResponse = $this->get(route('admin.google.callback'));

        $callbackResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();

        $newAdminUser = User::where('email', 'newadmin@gmail.com')->first();
        $this->assertNotNull($newAdminUser);
        $this->assertSame('Budi Santoso', $newAdminUser->name);
        $this->assertSame('google-newadmin-99', $newAdminUser->google_id);
        $this->assertSame('https://lh3.googleusercontent.com/budi.jpg', $newAdminUser->avatar);

        // Step 3: Masuk ke dashboard
        $dashboardResponse = $this->get(route('admin.dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Dashboard Admin');
        $dashboardResponse->assertSee('Budi Santoso');
    }

    public function test_point_3_unregistered_email_is_rejected_and_does_not_create_user(): void
    {
        $this->mockGoogleUser(
            email: 'intruder@gmail.com',
            id: 'intruder-id-1',
            name: 'Intruder',
            avatar: null
        );

        $countBefore = User::count();

        $response = $this->get(route('admin.google.callback'));

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error', 'Akun tidak memiliki akses admin.');
        $this->assertGuest();

        $this->assertSame($countBefore, User::count());
        $this->assertDatabaseMissing('users', ['email' => 'intruder@gmail.com']);
    }

    public function test_point_4_deactivated_admin_is_immediately_blocked_even_with_active_session(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'activeadmin@example.com',
            'is_active' => true,
        ]);

        // Sesi aktif: admin berhasil membuka dashboard
        $this->actingAs($admin);
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Admin dinonaktifkan di database (misalnya oleh owner)
        $admin->update(['is_active' => false]);

        // Request berikutnya menggunakan sesi yang sedang aktif langsung terblokir 403
        $blockedResponse = $this->get(route('admin.dashboard'));
        $blockedResponse->assertStatus(403);

        // Session admin langsung di-logout
        $this->assertGuest();
    }

    public function test_point_5_admin_cannot_deactivate_themselves(): void
    {
        $owner = User::factory()->owner()->create(['email' => 'owner@example.com']);

        // Owner mencoba menonaktifkan dirinya sendiri via direct PATCH request
        $response = $this->actingAs($owner)->patch(route('admin.admins.toggle-status', $owner));

        $response->assertStatus(403);
        $owner->refresh();
        $this->assertTrue($owner->is_active);
    }

    public function test_point_5_owner_cannot_be_deactivated_by_regular_admin(): void
    {
        $owner = User::factory()->owner()->create(['email' => 'owner@example.com']);
        $admin = User::factory()->admin()->create(['email' => 'regular@example.com']);

        // Admin biasa mencoba menonaktifkan owner via direct PATCH request
        $response = $this->actingAs($admin)->patch(route('admin.admins.toggle-status', $owner));

        $response->assertStatus(403);
        $owner->refresh();
        $this->assertTrue($owner->is_active);
    }

    public function test_point_5_system_cannot_have_zero_active_admins(): void
    {
        // Hanya 1 admin aktif dalam sistem
        $owner = User::factory()->owner()->create(['email' => 'lastowner@example.com']);
        $admin = User::factory()->admin()->create(['email' => 'lastadmin@example.com', 'is_active' => false]);

        // Mencoba menonaktifkan akun owner (yang merupakan satu-satunya akun aktif)
        $response = $this->actingAs($owner)->patch(route('admin.admins.toggle-status', $owner));

        $response->assertStatus(403);
    }

    public function test_owner_can_successfully_toggle_regular_admin_status(): void
    {
        $owner = User::factory()->owner()->create(['email' => 'owner@example.com']);
        $admin = User::factory()->admin()->create(['email' => 'target@example.com', 'is_active' => true]);

        // Nonaktifkan
        $response1 = $this->actingAs($owner)->patch(route('admin.admins.toggle-status', $admin));
        $response1->assertRedirect(route('admin.admins.index'));
        $response1->assertSessionHas('success');
        $admin->refresh();
        $this->assertFalse($admin->is_active);

        // Aktifkan kembali
        $response2 = $this->actingAs($owner)->patch(route('admin.admins.toggle-status', $admin));
        $response2->assertRedirect(route('admin.admins.index'));
        $response2->assertSessionHas('success');
        $admin->refresh();
        $this->assertTrue($admin->is_active);
    }

    public function test_store_admin_validates_email_format_and_uniqueness(): void
    {
        $owner = User::factory()->owner()->create(['email' => 'owner@example.com']);

        // Email kosong
        $resp1 = $this->actingAs($owner)->post(route('admin.admins.store'), ['email' => '']);
        $resp1->assertSessionHasErrors('email');

        // Email tidak valid
        $resp2 = $this->actingAs($owner)->post(route('admin.admins.store'), ['email' => 'bukan-email']);
        $resp2->assertSessionHasErrors('email');

        // Email duplikat
        $resp3 = $this->actingAs($owner)->post(route('admin.admins.store'), ['email' => 'owner@example.com']);
        $resp3->assertSessionHasErrors('email');
    }
}
