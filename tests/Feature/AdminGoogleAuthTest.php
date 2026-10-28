<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Tests\TestCase;

class AdminGoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to mock Socialite Google driver.
     */
    protected function mockGoogleUser(string $email, string $id = 'google-123456', ?string $avatar = 'https://lh3.googleusercontent.com/avatar.jpg'): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($id);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getName')->andReturn('Google Admin');
        $socialiteUser->shouldReceive('getAvatar')->andReturn($avatar);

        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_admin_login_page_renders_with_google_button_and_noindex(): void
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk dengan Google');
        $response->assertSee('<meta name="robots" content="noindex, nofollow">', false);
        $response->assertDontSee('name="password"', false);
    }

    public function test_redirect_to_google_initiates_oauth(): void
    {
        $redirectResponse = new SymfonyRedirectResponse('https://accounts.google.com/o/oauth2/auth');
        $provider = Mockery::mock(GoogleProvider::class);
        $provider->shouldReceive('redirect')->andReturn($redirectResponse);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('admin.google.redirect'));

        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    public function test_email_admin_can_login_via_google_and_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
            'is_active' => true,
            'google_id' => null,
            'avatar' => null,
        ]);

        $this->mockGoogleUser(
            email: 'admin@example.com',
            id: 'google-id-999',
            avatar: 'https://lh3.googleusercontent.com/photo.jpg'
        );

        $response = $this->get(route('admin.google.callback'));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        $admin->refresh();
        $this->assertSame('google-id-999', $admin->google_id);
        $this->assertSame('https://lh3.googleusercontent.com/photo.jpg', $admin->avatar);
    }

    public function test_non_admin_google_email_is_rejected_and_does_not_create_user(): void
    {
        $this->mockGoogleUser(email: 'stranger@gmail.com', id: 'stranger-id-1');

        $initialUserCount = User::count();

        $response = $this->get(route('admin.google.callback'));

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionHas('error', 'Akun tidak memiliki akses admin.');
        $this->assertGuest();

        $this->assertSame($initialUserCount, User::count());
        $this->assertDatabaseMissing('users', ['email' => 'stranger@gmail.com']);
    }

    public function test_direct_access_to_dashboard_without_login_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }

    public function test_logged_in_non_admin_user_cannot_open_dashboard(): void
    {
        $regularUser = User::factory()->create([
            'email' => 'user@example.com',
            'role' => null,
            'is_active' => true,
        ]);

        $response = $this->actingAs($regularUser)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_logout_invalidates_session_and_cannot_access_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($admin);
        $this->assertAuthenticatedAs($admin);

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();

        // Following request to dashboard should be redirected to login
        $followUp = $this->get(route('admin.dashboard'));
        $followUp->assertRedirect(route('admin.login'));
    }

    public function test_landing_page_does_not_contain_admin_login_link(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee(route('admin.login'));
        $response->assertDontSee('/ctrl-excellent');
        $response->assertDontSee('/admin/login');
    }

    public function test_admin_seeder_reads_admin_emails_from_config_and_is_idempotent(): void
    {
        config(['services.admin.emails' => 'first@admin.com, second@admin.com']);

        // Run once
        $this->seed(AdminSeeder::class);

        $this->assertDatabaseHas('users', [
            'email' => 'first@admin.com',
            'role' => 'owner',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'second@admin.com',
            'role' => 'owner',
            'is_active' => true,
        ]);

        $this->assertSame(2, User::whereIn('email', ['first@admin.com', 'second@admin.com'])->count());

        // Run twice to ensure idempotence
        $this->seed(AdminSeeder::class);
        $this->assertSame(2, User::whereIn('email', ['first@admin.com', 'second@admin.com'])->count());
    }
}
