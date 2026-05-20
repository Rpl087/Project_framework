<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * Session lifetime:
     *  - Tanpa "Ingat saya" : 180 menit (3 jam)
     *  - Dengan  "Ingat saya": 1440 menit (1 hari)
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // Set session lifetime dynamically based on "remember me"
        $lifetime = $request->boolean('remember') ? 1440 : 180;
        Config::set('session.lifetime', $lifetime);

        // Re-apply lifetime to the current session cookie
        $request->session()->put('_session_lifetime', $lifetime);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
