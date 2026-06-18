<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Verifikasi Email — LabManager">
    <title>LabManager — Verifikasi Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 1.5rem;
        }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background:
                radial-gradient(ellipse 60% 40% at 20% 30%, rgba(16,185,129,0.07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 80% 70%, rgba(99,102,241,0.07) 0%, transparent 60%);
            pointer-events: none;
        }

        .wrap { width: 100%; max-width: 460px; position: relative; z-index: 1; }

        /* ── Logo ── */
        .logo-block { text-align: center; margin-bottom: 2rem; }
        .logo-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px rgba(99,102,241,0.35);
        }
        .logo-block h1 { font-size: 1.5rem; font-weight: 800; color: #f1f5f9; }
        .logo-block p  { color: #64748b; font-size: 0.85rem; margin-top: 0.25rem; }

        /* ── Card ── */
        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem; padding: 2.5rem 2rem;
            text-align: center;
        }

        /* ── Email illustration icon ── */
        .email-illustration {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(5,150,105,0.2));
            border: 2px solid rgba(16,185,129,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse-green 2s ease-in-out infinite;
        }
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
            50% { box-shadow: 0 0 0 10px rgba(16,185,129,0.08); }
        }

        .card-title { color: #f1f5f9; font-size: 1.25rem; font-weight: 800; margin-bottom: 0.5rem; }
        .card-desc  { color: #64748b; font-size: 0.875rem; line-height: 1.7; margin-bottom: 1.75rem; }
        .card-desc strong { color: #94a3b8; }

        /* ── User email pill ── */
        .user-email {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.2);
            color: #818cf8; font-size: 0.8rem; font-weight: 600;
            padding: 0.4rem 1rem; border-radius: 9999px;
            margin-bottom: 1.5rem;
        }

        /* ── Steps ── */
        .steps {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .steps-title {
            font-size: 0.75rem; font-weight: 700; color: #64748b;
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 0.875rem;
        }
        .step {
            display: flex; align-items: flex-start; gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .step:last-child { margin-bottom: 0; }
        .step-num {
            width: 22px; height: 22px; flex-shrink: 0;
            background: rgba(99,102,241,0.15);
            border: 1px solid rgba(99,102,241,0.3);
            color: #818cf8; font-size: 0.7rem; font-weight: 700;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-top: 1px;
        }
        .step-text { font-size: 0.8rem; color: #94a3b8; line-height: 1.5; }
        .step-text code {
            background: rgba(255,255,255,0.08);
            padding: 0.1rem 0.35rem; border-radius: 3px;
            color: #c4b5fd; font-size: 0.72rem;
        }

        /* ── Buttons ── */
        .btn-resend {
            width: 100%; padding: 0.75rem;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; border: none; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .btn-resend:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 16px rgba(79,70,229,0.4);
            transform: translateY(-1px);
        }
        .btn-resend:disabled {
            opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none;
        }
        .btn-logout {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
            width: 100%; padding: 0.65rem;
            background: transparent; color: #64748b;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.5rem;
            font-size: 0.82rem; font-weight: 500;
            cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .btn-logout:hover { color: #94a3b8; border-color: rgba(255,255,255,0.15); }

        /* ── Alerts ── */
        .alert-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 0.75rem; padding: 0.875rem 1rem;
            margin-bottom: 1.25rem;
            color: #34d399; font-size: 0.82rem;
            display: flex; align-items: flex-start; gap: 0.5rem;
            text-align: left;
        }
        .alert-success svg { flex-shrink: 0; margin-top: 1px; }

        /* ── Countdown ── */
        .countdown {
            font-size: 0.75rem; color: #475569;
            margin-top: 0.5rem; text-align: center;
        }
        .countdown span { color: #818cf8; font-weight: 600; }

        /* ── Dev info ── */
        .dev-info {
            margin-top: 1.5rem;
            background: rgba(245,158,11,0.06);
            border: 1px solid rgba(245,158,11,0.15);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            text-align: left;
        }
        .dev-info p {
            font-size: 0.75rem; color: #92400e;
            color: #fbbf24; line-height: 1.6;
        }
        .dev-info code {
            background: rgba(0,0,0,0.25);
            padding: 0.1rem 0.35rem;
            border-radius: 3px; font-size: 0.7rem; color: #fde68a;
        }
    </style>
</head>
<body>

<div class="wrap">

    {{-- Logo --}}
    <div class="logo-block">
        <div class="logo-icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
        </div>
        <h1>LabManager</h1>
        <p>Sistem Manajemen Peminjaman Lab</p>
    </div>

    <div class="card">

        {{-- Illustration ── --}}
        <div class="email-illustration">
            <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="card-title">Verifikasi Email Anda</h1>
        <p class="card-desc">
            Terima kasih telah mendaftar di <strong>LabManager</strong>!
            Kami telah mengirimkan email verifikasi ke alamat berikut:
        </p>

        <div class="user-email">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            {{ auth()->user()->email }}
        </div>

        {{-- Status setelah resend --}}
        @if(session('status'))
            <div class="alert-success">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Steps ── --}}
        <div class="steps">
            <p class="steps-title">Cara Verifikasi</p>
            <div class="step">
                <div class="step-num">1</div>
                <p class="step-text">Buka inbox email Anda dan cari email dari <code>{{ config('mail.from.address') }}</code></p>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <p class="step-text">Klik tombol <strong style="color:#c4b5fd;">"Verifikasi Alamat Email"</strong> di dalam email tersebut</p>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <p class="step-text">Anda akan otomatis diarahkan ke dashboard dan bisa mulai meminjam alat</p>
            </div>
        </div>

        {{-- Resend button ── --}}
        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn-resend" id="resendBtn">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <p class="countdown" id="countdownMsg" style="display:none;">
            Tunggu <span id="countdownSec">60</span> detik sebelum kirim ulang lagi.
        </p>

        {{-- Logout ── --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar & Gunakan Akun Lain
            </button>
        </form>

        {{-- Dev info ── --}}
        <div class="dev-info">
            <p>
                ⚠️ <strong>Mode Development:</strong> Email ditulis ke log, bukan inbox nyata.<br>
                Buka <code>storage/logs/laravel.log</code> dan cari link yang mengandung <code>verify-email</code>.
            </p>
        </div>

    </div>
</div>

<script>
    // Countdown setelah klik resend agar tidak spam
    const resendBtn = document.getElementById('resendBtn');
    const countdownMsg = document.getElementById('countdownMsg');
    const countdownSec = document.getElementById('countdownSec');

    @if(session('status'))
        startCountdown();
    @endif

    document.querySelector('form[action="{{ route('verification.resend') }}"]')
        .addEventListener('submit', () => startCountdown());

    function startCountdown() {
        resendBtn.disabled = true;
        countdownMsg.style.display = 'block';
        let sec = 60;
        countdownSec.textContent = sec;
        const timer = setInterval(() => {
            sec--;
            countdownSec.textContent = sec;
            if (sec <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                countdownMsg.style.display = 'none';
            }
        }, 1000);
    }
</script>

</body>
</html>
