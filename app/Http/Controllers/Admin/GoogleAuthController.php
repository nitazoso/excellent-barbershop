<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Show the admin login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->role !== null && Auth::user()->is_active) {
            return redirect()->route('admin.dashboard');
        }

        return view('pages.admin.login');
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): SymfonyRedirectResponse|RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and authenticate if admin/owner.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable) {
            return redirect()->route('admin.login')->with('error', 'Gagal masuk dengan Google. Silakan coba lagi.');
        }

        $email = $googleUser->getEmail();

        if (! $email) {
            return redirect()->route('admin.login')->with('error', 'Akun tidak memiliki akses admin.');
        }

        $user = User::query()
            ->where('email', $email)
            ->whereNotNull('role')
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return redirect()->route('admin.login')->with('error', 'Akun tidak memiliki akses admin.');
        }

        $updates = [];

        if (empty($user->name) && $googleUser->getName()) {
            $updates['name'] = $googleUser->getName();
        }

        if (empty($user->google_id)) {
            $updates['google_id'] = $googleUser->getId();
        }

        if (empty($user->avatar)) {
            $updates['avatar'] = $googleUser->getAvatar();
        }

        if (! empty($updates)) {
            $user->update($updates);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
