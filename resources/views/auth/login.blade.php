<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login — Sistem Manajemen Laboratorium">
    <title>LabManager — Masuk</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">
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

        /* ── Login Card ── */
        .login-wrap {
            width: 100%;
            max-width: 420px;
        }

        .logo-block {
            text-align: center;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 14px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 1rem;
        }
        .logo-block h1 { font-size: 1.5rem; font-weight: 800; color: #f1f5f9; }
        .logo-block p  { color: #64748b; font-size: 0.85rem; margin-top: 0.25rem; }

        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem;
            padding: 2rem;
        }

        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.375rem;
        }
        .form-input {
            width: 100%;
            padding: 0.625rem 0.875rem;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 0.5rem;
            color: #f1f5f9;
            font-size: 0.875rem;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input::placeholder { color: #475569; }
        .form-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129,140,248,0.2);
        }
        .form-error {
            color: #f87171;
            font-size: 0.75rem;
            margin-top: 0.375rem;
            display: flex; align-items: center; gap: 0.3rem;
        }

        .remember-row {
            display: flex; align-items: center;
            margin-bottom: 1.5rem;
        }
        .remember-row input {
            width: 16px; height: 16px;
            accent-color: #6366f1; cursor: pointer;
        }
        .remember-row label {
            margin-left: 0.5rem;
            font-size: 0.8rem; color: #94a3b8; cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; border: none; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            box-shadow: 0 4px 16px rgba(79,70,229,0.4);
            transform: translateY(-1px);
        }

        /* ── Register Button ── */
        .divider {
            display: flex; align-items: center; gap: 0.75rem;
            margin: 1.25rem 0;
        }
        .divider hr {
            flex: 1; border: none;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .divider span {
            color: #475569; font-size: 0.72rem; white-space: nowrap;
        }
        .btn-register {
            width: 100%;
            padding: 0.7rem;
            background: transparent;
            color: #818cf8;
            border: 1px solid rgba(99,102,241,0.35);
            border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-register:hover {
            background: rgba(99,102,241,0.1);
            border-color: rgba(99,102,241,0.6);
            color: #a5b4fc;
        }

        /* ── Alert ── */
        .alert-success {
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.25);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1.25rem;
            color: #34d399; font-size: 0.82rem;
            display: flex; align-items: flex-start; gap: 0.5rem;
        }

        /* ── Password Toggle ── */
        .pw-wrap { position: relative; }
        .pw-wrap .form-input { padding-right: 2.75rem; }
        .pw-toggle {
            position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: #475569; padding: 0.25rem;
            display: flex; align-items: center; justify-content: center;
            transition: color 0.2s;
        }
        .pw-toggle:hover { color: #818cf8; }

        /* ── Demo box ── */
        .demo-box {
            margin-top: 1.5rem; text-align: center;
        }
        .demo-box p { color: #475569; font-size: 0.75rem; }
        .demo-box code {
            background: rgba(255,255,255,0.08);
            padding: 0.15rem 0.4rem;
            border-radius: 3px; color: #94a3b8;
        }

        /* ════════════════════════════════
           Modal Register
        ════════════════════════════════ */
        .modal-backdrop {
            position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(4px);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            opacity: 0; visibility: hidden;
            transition: opacity 0.3s cubic-bezier(0.22,1,0.36,1),
                        visibility 0.3s cubic-bezier(0.22,1,0.36,1);
        }
        .modal-backdrop.open {
            opacity: 1; visibility: visible;
        }
        .modal {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1.25rem;
            width: 100%; max-width: 460px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 2rem;
            box-shadow: 0 32px 80px rgba(0,0,0,0.6);
            transform: scale(0.93) translateY(12px);
            transition: transform 0.35s cubic-bezier(0.22,1,0.36,1);
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .modal-backdrop.open .modal {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex; align-items: flex-start; justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .modal-header h2 { color: #f1f5f9; font-size: 1.2rem; font-weight: 800; }
        .modal-header p  { color: #64748b; font-size: 0.78rem; margin-top: 0.25rem; }
        .modal-close {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 0.5rem;
            color: #94a3b8; cursor: pointer;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s; flex-shrink: 0; margin-left: 1rem;
        }
        .modal-close:hover { background: rgba(255,255,255,0.12); color: #f1f5f9; }

        .role-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: rgba(99,102,241,0.12);
            border: 1px solid rgba(99,102,241,0.25);
            color: #818cf8;
            font-size: 0.72rem; font-weight: 600;
            padding: 0.3rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: 1rem;
        }
        .alert-info {
            background: rgba(99,102,241,0.08);
            border: 1px solid rgba(99,102,241,0.18);
            border-radius: 0.5rem;
            padding: 0.65rem 0.875rem;
            margin-bottom: 1.1rem;
            color: #a5b4fc; font-size: 0.77rem;
            line-height: 1.5;
        }

        .phone-wrap { display: flex; gap: 0.5rem; }
        .phone-prefix {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.5rem;
            color: #94a3b8; font-size: 0.85rem;
            padding: 0.625rem 0.75rem;
            white-space: nowrap; display: flex; align-items: center; gap: 0.35rem; flex-shrink: 0;
        }
        .hint { color: #475569; font-size: 0.68rem; margin-top: 0.3rem; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }

        .btn-submit-green {
            width: 100%; padding: 0.75rem;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff; border: none; border-radius: 0.5rem;
            font-size: 0.875rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.4rem;
            margin-top: 0.5rem;
        }
        .btn-submit-green:hover {
            background: linear-gradient(135deg, #047857, #059669);
            box-shadow: 0 4px 16px rgba(16,185,129,0.4);
            transform: translateY(-1px);
        }

        .modal-footer-note {
            color: #334155; font-size: 0.67rem;
            text-align: center; margin-top: 1rem; line-height: 1.6;
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════
     LOGIN CARD
══════════════════════════════════════════ --}}
<div class="login-wrap">

    {{-- Logo --}}
    <div class="logo-block">
        <div class="logo-icon" style="background:none;box-shadow:none;">
            <img src="{{ asset('images/logo.png') }}" alt="LabManager Logo" style="width:48px;height:48px;border-radius:12px;">
        </div>
        <h1>LabManager</h1>
        <p>Sistem Manajemen Peminjaman Lab</p>
    </div>

    <div class="card">

        {{-- Status setelah registrasi --}}
        @if(session('status'))
            <div class="alert-success">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="form-input" required autofocus autocomplete="username"
                    placeholder="nama@email.com">
                @error('email')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.375rem;">
                    <label for="password" class="form-label" style="margin-bottom:0;">Password</label>
                    <a href="{{ route('password.request') }}"
                       style="font-size:0.75rem;color:#818cf8;text-decoration:none;font-weight:500;transition:color 0.2s;"
                       onmouseover="this.style.color='#a5b4fc'" onmouseout="this.style.color='#818cf8'"
                    >Lupa Password?</a>
                </div>
                <div class="pw-wrap">
                    <input id="password" type="password" name="password"
                        class="form-input" required autocomplete="current-password"
                        placeholder="••••••••">
                    <button type="button" class="pw-toggle" onclick="togglePassword('password', this)" tabindex="-1" aria-label="Tampilkan password">
                        <svg id="eye-password" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg id="eye-off-password" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="remember-row">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        <div class="divider">
            <hr><span>atau belum punya akun?</span><hr>
        </div>

        {{-- Tombol buka modal registrasi --}}
        <button type="button" class="btn-register" onclick="openRegisterModal()">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            Daftar Akun Baru
        </button>
    </div>

    <div class="demo-box">
        <p>Demo Accounts:</p>
        <div style="display:grid;gap:0.3rem;margin-top:0.5rem;">
            <p style="font-size:0.7rem;">📚 mahasiswa@lab.test &nbsp;|&nbsp; 🔬 laboran@lab.test &nbsp;|&nbsp; 🏛️ kepalalab@lab.test</p>
            <p style="font-size:0.7rem;">Password: <code>password</code></p>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL REGISTRASI
══════════════════════════════════════════ --}}
<div id="registerModal" class="modal-backdrop" onclick="handleBackdropClick(event)">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">

        <div class="modal-header">
            <div>
                <h2 id="modalTitle">Daftar Akun Baru ✨</h2>
                <p>Buat akun untuk mulai meminjam alat laboratorium.</p>
            </div>
            <button class="modal-close" onclick="closeRegisterModal()" aria-label="Tutup">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Role badge --}}
        <div class="role-badge">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            Role: <strong>Mahasiswa</strong> (default)
        </div>

        <div class="alert-info">
            <strong>ℹ️ Info:</strong> Akun yang didaftarkan secara otomatis mendapat role <strong>Mahasiswa</strong> dan dapat meminjam alat laboratorium.
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="reg_name" class="form-label">Nama Lengkap *</label>
                <input id="reg_name" type="text" name="name" value="{{ old('name') }}"
                    class="form-input" required placeholder="Nama lengkap Anda">
                @error('name')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="reg_email" class="form-label">Email *</label>
                <input id="reg_email" type="email" name="email" value="{{ old('email') }}"
                    class="form-input" required placeholder="nama@email.com">
                @error('email')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="reg_phone" class="form-label">Nomor Telepon *</label>
                <input id="reg_phone" type="tel" name="phone" value="{{ old('phone') }}"
                    class="form-input" required
                    placeholder="Contoh: 081234567890"
                    maxlength="20" inputmode="tel">
                <p class="hint">Masukkan nomor HP aktif (08xx atau 628xx)</p>
                @error('phone')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="reg_password" class="form-label">Password * (min. 8)</label>
                    <div class="pw-wrap">
                        <input id="reg_password" type="password" name="password"
                            class="form-input" required minlength="8" placeholder="••••••••">
                        <button type="button" class="pw-toggle" onclick="togglePassword('reg_password', this)" tabindex="-1" aria-label="Tampilkan password">
                            <svg id="eye-reg_password" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye-off-reg_password" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error" style="font-size:0.68rem;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="reg_password_confirmation" class="form-label">Konfirmasi *</label>
                    <div class="pw-wrap">
                        <input id="reg_password_confirmation" type="password" name="password_confirmation"
                            class="form-input" required placeholder="••••••••">
                        <button type="button" class="pw-toggle" onclick="togglePassword('reg_password_confirmation', this)" tabindex="-1" aria-label="Tampilkan konfirmasi password">
                            <svg id="eye-reg_password_confirmation" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eye-off-reg_password_confirmation" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit-green">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Buat Akun Mahasiswa
            </button>
        </form>

        <p class="modal-footer-note">
            Dengan mendaftar, Anda menyetujui tata tertib penggunaan<br>alat laboratorium yang berlaku.
        </p>
    </div>
</div>

<script>
    const modal = document.getElementById('registerModal');

    function openRegisterModal() {
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeRegisterModal() {
        modal.classList.remove('open');
        document.body.style.overflow = '';
    }

    // Tutup jika klik backdrop (bukan modal itu sendiri)
    function handleBackdropClick(e) {
        if (e.target === modal) closeRegisterModal();
    }

    // Tutup dengan tombol Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeRegisterModal();
    });

    // Buka modal otomatis jika ada error validasi registrasi
    @if($errors->has('name') || $errors->has('phone') || ($errors->has('email') && old('name')) || ($errors->has('password') && old('name')))
        openRegisterModal();
    @endif

    // Auto-format nomor telepon (opsional formatting)
    document.getElementById('reg_phone').addEventListener('input', function(e) {
        let v = e.target.value.replace(/\D/g, '');
        // Biarkan user input bebas (08xx atau 628xx)
        let f = '';
        if (v.length > 0)  f = v.slice(0, 4);
        if (v.length > 4)  f += '-' + v.slice(4, 8);
        if (v.length > 8)  f += '-' + v.slice(8, 13);
        e.target.value = f;
    });

    // Toggle tampil/sembunyikan password
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const eyeOn  = document.getElementById('eye-' + fieldId);
        const eyeOff = document.getElementById('eye-off-' + fieldId);
        if (input.type === 'password') {
            input.type = 'text';
            eyeOn.style.display  = 'none';
            eyeOff.style.display = 'block';
            btn.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            input.type = 'password';
            eyeOn.style.display  = 'block';
            eyeOff.style.display = 'none';
            btn.setAttribute('aria-label', 'Tampilkan password');
        }
    }
</script>

</body>
</html>
