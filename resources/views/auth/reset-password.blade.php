<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Reset Password — LabManager">
    <title>LabManager — Reset Password</title>
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
                radial-gradient(ellipse 60% 40% at 20% 30%, rgba(99,102,241,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 50% at 80% 70%, rgba(139,92,246,0.07) 0%, transparent 60%);
            pointer-events: none;
        }

        .wrap { width: 100%; max-width: 420px; position: relative; z-index: 1; }

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

        .card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 1rem; padding: 2rem;
        }
        .card-title { color: #f1f5f9; font-size: 1.1rem; font-weight: 700; margin-bottom: 0.4rem; }
        .card-desc  { color: #64748b; font-size: 0.82rem; line-height: 1.6; margin-bottom: 1.5rem; }

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

        /*  Password strength indicator  */
        .strength-bar {
            height: 3px; border-radius: 2px;
            background: rgba(255,255,255,0.08);
            margin-top: 0.5rem; overflow: hidden;
        }
        .strength-fill {
            height: 100%; border-radius: 2px;
            width: 0%; transition: width 0.3s, background 0.3s;
        }
        .strength-label {
            font-size: 0.68rem; color: #475569;
            margin-top: 0.25rem; text-align: right;
        }

        /*  Input wrapper with toggle eye icon  */
        .input-wrap { position: relative; }
        .eye-btn {
            position: absolute; right: 0.75rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #475569; cursor: pointer; padding: 0;
            display: flex; align-items: center;
            transition: color 0.2s;
        }
        .eye-btn:hover { color: #94a3b8; }

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
            margin-top: 0.75rem; transition: all 0.2s;
        }
        .btn-back:hover { color: #94a3b8; border-color: rgba(255,255,255,0.15); }

        .email-badge {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: rgba(99,102,241,0.1);
            border: 1px solid rgba(99,102,241,0.2);
            color: #818cf8; font-size: 0.75rem; font-weight: 600;
            padding: 0.3rem 0.75rem; border-radius: 9999px;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>

<div class="wrap">

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

        <h2 class="card-title">🔐 Buat Password Baru</h2>
        <p class="card-desc">Masukkan password baru untuk akun Anda. Pastikan password cukup kuat.</p>

        @if($request->email)
            <div class="email-badge">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{ $request->email }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}" id="resetForm">
            @csrf

            {{-- Hidden fields --}}
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    id="email" type="email" name="email"
                    value="{{ old('email', $request->email) }}"
                    class="form-input" required autofocus autocomplete="username"
                    placeholder="nama@email.com"
                >
                @error('email')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password Baru * (min. 8 karakter)</label>
                <div class="input-wrap">
                    <input
                        id="password" type="password" name="password"
                        class="form-input" required autocomplete="new-password"
                        placeholder="••••••••" minlength="8"
                        oninput="checkStrength(this.value)"
                        style="padding-right:2.5rem;"
                    >
                    <button type="button" class="eye-btn" onclick="togglePass('password', this)" title="Tampilkan/Sembunyikan">
                        <svg id="eye-icon-pw" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                <p class="strength-label" id="strengthLabel"></p>
                @error('password')
                    <p class="form-error">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                <div class="input-wrap">
                    <input
                        id="password_confirmation" type="password" name="password_confirmation"
                        class="form-input" required autocomplete="new-password"
                        placeholder="••••••••"
                        style="padding-right:2.5rem;"
                    >
                    <button type="button" class="eye-btn" onclick="togglePass('password_confirmation', this)" title="Tampilkan/Sembunyikan">
                        <svg id="eye-icon-pc" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Simpan Password Baru
            </button>
        </form>

        <a href="{{ route('login') }}" class="btn-back">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Login
        </a>

    </div>
</div>

<script>
    // Toggle show/hide password
    function togglePass(id, btn) {
        const input = document.getElementById(id);
        const isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        btn.style.color = isText ? '#475569' : '#818cf8';
    }

    // Password strength checker
    function checkStrength(val) {
        const fill  = document.getElementById('strengthFill');
        const label = document.getElementById('strengthLabel');
        let score = 0;

        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { pct: '0%',   color: 'transparent', text: '' },
            { pct: '25%',  color: '#ef4444',      text: 'Sangat Lemah' },
            { pct: '50%',  color: '#f59e0b',      text: 'Lemah' },
            { pct: '70%',  color: '#eab308',      text: 'Sedang' },
            { pct: '88%',  color: '#22c55e',      text: 'Kuat' },
            { pct: '100%', color: '#10b981',      text: 'Sangat Kuat ✓' },
        ];

        const lv = levels[Math.min(score, 5)];
        fill.style.width      = val.length ? lv.pct   : '0%';
        fill.style.background = lv.color;
        label.textContent     = val.length ? lv.text  : '';
        label.style.color     = lv.color;
    }
</script>

</body>
</html>
