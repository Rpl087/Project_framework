<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationController extends Controller
{
    /**
     * Tampilkan halaman notice "Silakan verifikasi email Anda".
     */
    public function notice(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.verify-email');
    }

    /**
     * Proses klik link verifikasi dari email.
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'))->with(
                'status',
                'Email Anda sudah terverifikasi sebelumnya.'
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(route('dashboard'))->with(
            'status',
            '✅ Email berhasil diverifikasi! Selamat datang di LabManager.'
        );
    }

    /**
     * Kirim ulang email verifikasi.
     */
    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Link verifikasi telah dikirim ulang ke email Anda.');
    }
}
