<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Lupa Password — LabManager">
    <title>LabManager — Lupa Password</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            padding: 1.5rem;
        }

        /* ── Floating Particles Background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 40% at 20% 30%, rgba(99,102,241,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 80% 70%, rgba(139,92,246,0.07) 0%, transparent 60%);
            pointer-events: none;
        }

        .wrap {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        /* ── Logo Block ── */
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
            border-radius: 1rem;
            padding: 2rem;
        }

        .card-title { color: #f1f5f9; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.4rem; }
        .card-desc  { color: #64748b; font-size: 0.82rem; line-height: 1.6; margin-bottom: 1.5rem; }

        /* ── Form ── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: #94a3b8; margin-bottom: 0.375rem;
        }
        .form-input {
            width: 100%; padding: 0.625rem 0.875rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.5rem; color: #f1f5f9;
            font-size: 0.875rem; outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input::placeholder { color: #475569; }
        .form-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129,140,248,0.2);
        }
        .form-error {
            color: #f87171; font-size: 0.75rem;
            margin-top: 0.375rem;
            display: flex; align-items: center; gap: 0.3rem;
        }

        /* ── Buttons ── */
        .btn-submit {
            width: 100%; padding: 0.75rem;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; border: none; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 16px rgba(79,70,229,0.4);
            transform: translateY(-1px);
        }
        .btn-back {
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
            width: 100%; padding: 0.65rem;
            background: transparent; color: #64748b;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 0.5rem;
            font-size: 0.82rem; font-weight: 500;
            cursor: pointer; text-decoration: none;
            margin-top: 0.75rem;
            transition: all 0.2s;
        }
        .btn-back:hover { color: #94a3b8; border-color: rgba(255,255,255,0.15); }

        /* ── Alert Success ── */
        .alert-success {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 0.75rem; padding: 1rem 1.25rem;
            margin-bottom: 1.25rem;
            color: #34d399; font-size: 0.82rem; line-height: 1.6;
            display: flex; align-items: flex-start; gap: 0.625rem;
        }
        .alert-success-icon { flex-shrink: 0; margin-top: 1px; }

        /* ── Info box ── */
        .info-box {
            background: rgba(99,102,241,0.08);
            border: 1px solid rgba(99,102,241,0.2);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            color: #a5b4fc; font-size: 0.77rem; line-height: 1.6;
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

        <h2 class="card-title">🔑 Lupa Password?</h2>
        <p class="card-desc">Masukkan alamat email yang terdaftar. Kami akan mengirimkan link untuk mereset password Anda.</p>

        {{-- Pesan sukses setelah email terkirim --}}
        @if(session('status'))
            <div class="alert-success">
                <svg class="alert-success-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <strong>Email terkirim!</strong><br>
                    {{ session('status') }}<br>
                    <span style="font-size:0.72rem;color:#6ee7b7;margin-top:0.25rem;display:block;">
                        Jika menggunakan development mode, cek <code style="background:rgba(0,0,0,0.3);padding:0.1rem 0.3rem;border-radius:3px;">storage/logs/laravel.log</code> untuk link reset.
                    </span>
                </div>
            </div>
        @endif

        @if(!session('status'))
            <div class="info-box">
                <strong>💡 Info:</strong> Link reset password berlaku selama <strong>60 menit</strong>.
                Jika tidak menerima email, cek folder spam atau klik "Kirim Ulang".
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input
                    id="email" type="email" name="email"
                    value="{{ old('email') }}"
                    class="form-input" required autofocus
                    placeholder="nama@email.com"
                >
                @error('email')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="btn-submit" id="btn-send">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Kirim Link Reset Password
            </button>
        </form>

        <a href="{{ route('login') }}" class="btn-back">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Halaman Login
        </a>

    </div>
</div>

</body>
</html>
