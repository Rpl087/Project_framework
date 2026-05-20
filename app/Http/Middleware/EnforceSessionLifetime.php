<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class EnforceSessionLifetime
{
    /**
     * Enforce the dynamic session lifetime that was stored at login.
     *
     * Cara kerja:
     *  - Saat login, controller menyimpan '_session_lifetime' ke dalam sesi.
     *  - Middleware ini membacanya di setiap request dan:
     *      1. Memperbarui config runtime agar cookie berikutnya punya TTL yang benar.
     *      2. Memeriksa kapan sesi terakhir aktif; jika sudah melebihi lifetime,
     *         sesi dihapus (logout otomatis).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->has('_session_lifetime')) {
            $lifetime = (int) $request->session()->get('_session_lifetime');

            // Re-apply runtime config so subsequent cookie writes use the right TTL
            Config::set('session.lifetime', $lifetime);

            // Check last activity — force logout if expired
            $lastActivity = $request->session()->get('_last_activity', time());
            $elapsed      = (time() - $lastActivity) / 60; // in minutes

            if ($elapsed >= $lifetime) {
                $request->session()->flush();
                auth()->logout();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
            }

            // Update last activity timestamp
            $request->session()->put('_last_activity', time());
        }

        return $next($request);
    }
}
