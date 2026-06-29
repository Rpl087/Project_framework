@extends(auth()->user()->isMahasiswa() ? 'layouts.mahasiswa' : 'layouts.app')
@section('title', 'Edit Profil')
@if(auth()->user()->isMahasiswa())
@section('no_hero', true)
@endif

@php $isMhs = auth()->user()->isMahasiswa(); @endphp

@section('content')

{{-- Page Header --}}
<div class="animate-in" style="margin-bottom:1.5rem;">
    <h1 style="font-size:1.5rem;font-weight:800;color:var({{ $isMhs ? '--u-txt1' : '--txt-1' }},#0f172a);line-height:1.2;">Edit Profil</h1>
    <p style="color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#64748b);font-size:0.875rem;margin-top:0.25rem;">Kelola informasi akun dan keamanan Anda.</p>
</div>

{{-- Flash Messages --}}
@if(session('success'))
<div style="background:#d1fae5;border:1px solid #a7f3d0;border-radius:0.75rem;padding:0.875rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.75rem;font-size:0.875rem;color:#065f46;animation:uFadeUp 0.3s ease;" class="animate-in">
    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:0.75rem;padding:0.875rem 1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.75rem;font-size:0.875rem;color:#991b1b;" class="animate-in">
    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
</div>
@endif

{{-- 2-Column Layout --}}
<div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start;" id="profileGrid">

    {{--  Kolom Kiri: Form --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Card: Informasi Akun --}}
        <div class="glass-card animate-in animate-delay-1" style="overflow:hidden;">
            {{-- Header --}}
            <div style="padding:1.125rem 1.5rem;border-bottom:1px solid var({{ $isMhs ? '--u-border' : '--border-s' }},#e2e8f0);background:linear-gradient(135deg,rgba(99,102,241,0.05),rgba(139,92,246,0.03));display:flex;align-items:center;gap:0.75rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#4f46e5,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.9rem;font-weight:700;color:var({{ $isMhs ? '--u-txt1' : '--txt-1' }},#0f172a);margin:0;">Informasi Akun</p>
                    <p style="font-size:0.7rem;color:var({{ $isMhs ? '--u-txt3' : '--txt-3' }},#94a3b8);margin:0;">Nama, email, dan nomor telepon</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" style="padding:1.5rem;">
                @csrf @method('PUT')

                {{-- Avatar Inisial --}}
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding:1rem;background:#f8fafc;border-radius:0.75rem;border:1px solid #e2e8f0;">
                    <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#8b5cf6);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;font-size:1.35rem;font-weight:800;border:3px solid rgba(99,102,241,0.25);box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p style="font-size:0.9rem;font-weight:700;color:#0f172a;margin:0;">{{ $user->name }}</p>
                        <p style="font-size:0.75rem;color:#64748b;margin:0.15rem 0 0;">{{ $user->email }}</p>
                        <span style="display:inline-flex;align-items:center;margin-top:0.3rem;padding:0.15rem 0.5rem;border-radius:9999px;font-size:0.65rem;font-weight:700;background:#e0e7ff;color:#3730a3;">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div style="margin-bottom:1.125rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">
                        Nama Lengkap <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="form-input"
                        placeholder="Masukkan nama lengkap"
                        style="width:100%;padding:0.7rem 1rem;border:1.5px solid {{ $errors->has('name') ? '#ef4444' : 'var(--u-border,#e2e8f0)' }};border-radius:0.625rem;font-size:0.875rem;background:#fff;color:#0f172a;transition:all 0.2s;"
                        onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                        onblur="this.style.borderColor='{{ $errors->has('name') ? '#ef4444' : '' }}';this.style.boxShadow=''">
                    @error('name')
                        <p style="color:#ef4444;font-size:0.72rem;margin-top:0.3rem;display:flex;align-items:center;gap:0.25rem;">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div style="margin-bottom:1.125rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">
                        Email <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="form-input"
                        placeholder="contoh@email.com"
                        style="width:100%;padding:0.7rem 1rem;border:1.5px solid {{ $errors->has('email') ? '#ef4444' : 'var(--u-border,#e2e8f0)' }};border-radius:0.625rem;font-size:0.875rem;background:#fff;color:#0f172a;transition:all 0.2s;"
                        onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                        onblur="this.style.borderColor='';this.style.boxShadow=''">
                    @error('email')
                        <p style="color:#ef4444;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                    @if(!$user->email_verified_at)
                        <p style="font-size:0.7rem;color:#f59e0b;margin-top:0.3rem;display:flex;align-items:center;gap:0.25rem;">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Email belum diverifikasi.
                        </p>
                    @endif
                </div>

                {{-- Nomor Telepon --}}
                <div style="margin-bottom:1.125rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">
                        Nomor Telepon <span style="font-weight:400;color:#94a3b8;">(opsional)</span>
                    </label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="form-input"
                        placeholder="Contoh: 081234567890"
                        maxlength="20"
                        style="width:100%;padding:0.7rem 1rem;border:1.5px solid var(--u-border,#e2e8f0);border-radius:0.625rem;font-size:0.875rem;background:#fff;color:#0f172a;transition:all 0.2s;"
                        onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                        onblur="this.style.borderColor='';this.style.boxShadow=''">
                    @error('phone')
                        <p style="color:#ef4444;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Role (disabled) --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">Role</label>
                    <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" disabled
                        style="width:100%;padding:0.7rem 1rem;border:1.5px solid #e2e8f0;border-radius:0.625rem;font-size:0.875rem;background:#f8fafc;color:#94a3b8;cursor:not-allowed;">
                    <p style="font-size:0.7rem;color:#94a3b8;margin-top:0.3rem;">Role tidak dapat diubah sendiri.</p>
                </div>

                {{-- Submit --}}
                <div style="padding-top:0.75rem;border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Card: Ganti Password --}}
        <div class="glass-card animate-in animate-delay-2" style="overflow:hidden;">
            {{-- Header --}}
            <div style="padding:1.125rem 1.5rem;border-bottom:1px solid var({{ $isMhs ? '--u-border' : '--border-s' }},#e2e8f0);background:linear-gradient(135deg,rgba(245,158,11,0.05),rgba(217,119,6,0.03));display:flex;align-items:center;gap:0.75rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#d97706,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <p style="font-size:0.9rem;font-weight:700;color:var({{ $isMhs ? '--u-txt1' : '--txt-1' }},#0f172a);margin:0;">Ganti Password</p>
                    <p style="font-size:0.7rem;color:var({{ $isMhs ? '--u-txt3' : '--txt-3' }},#94a3b8);margin:0;">Perbarui kata sandi akun Anda</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password') }}" style="padding:1.5rem;">
                @csrf @method('PUT')

                {{-- Password saat ini --}}
                <div style="margin-bottom:1.125rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">
                        Password Saat Ini <span style="color:#ef4444;">*</span>
                    </label>
                    <div style="position:relative;">
                        <input type="password" name="current_password" required
                            id="currPw" placeholder="••••••••"
                            style="width:100%;padding:0.7rem 1rem;padding-right:2.75rem;border:1.5px solid {{ $errors->has('current_password') ? '#ef4444' : 'var(--u-border,#e2e8f0)' }};border-radius:0.625rem;font-size:0.875rem;background:#fff;color:#0f172a;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                            onblur="this.style.borderColor='';this.style.boxShadow=''">
                        <button type="button" onclick="togglePw('currPw','eyeCurr')" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0.25rem;" tabindex="-1">
                            <svg id="eyeCurr" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    @error('current_password') <p style="color:#ef4444;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Password Baru --}}
                <div style="margin-bottom:1.125rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">
                        Password Baru <span style="color:#ef4444;">*</span>
                        <span style="font-weight:400;color:#94a3b8;">(min. 8 karakter)</span>
                    </label>
                    <div style="position:relative;">
                        <input type="password" name="password" required id="newPw"
                            placeholder="••••••••" minlength="8"
                            oninput="checkStrength(this.value)"
                            style="width:100%;padding:0.7rem 1rem;padding-right:2.75rem;border:1.5px solid {{ $errors->has('password') ? '#ef4444' : 'var(--u-border,#e2e8f0)' }};border-radius:0.625rem;font-size:0.875rem;background:#fff;color:#0f172a;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                            onblur="this.style.borderColor='';this.style.boxShadow=''">
                        <button type="button" onclick="togglePw('newPw','eyeNew')" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0.25rem;" tabindex="-1">
                            <svg id="eyeNew" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    {{-- Strength Bar --}}
                    <div style="margin-top:0.5rem;">
                        <div style="height:4px;background:#e2e8f0;border-radius:2px;overflow:hidden;">
                            <div id="strengthBar" style="height:100%;width:0%;border-radius:2px;transition:width 0.3s,background 0.3s;"></div>
                        </div>
                        <p id="strengthLabel" style="font-size:0.68rem;margin-top:0.25rem;color:#94a3b8;"></p>
                    </div>
                    @error('password') <p style="color:#ef4444;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-size:0.8rem;font-weight:700;color:var({{ $isMhs ? '--u-txt2' : '--txt-2' }},#475569);margin-bottom:0.4rem;">
                        Konfirmasi Password Baru <span style="color:#ef4444;">*</span>
                    </label>
                    <div style="position:relative;">
                        <input type="password" name="password_confirmation" required
                            id="confPw" placeholder="••••••••"
                            style="width:100%;padding:0.7rem 1rem;padding-right:2.75rem;border:1.5px solid var(--u-border,#e2e8f0);border-radius:0.625rem;font-size:0.875rem;background:#fff;color:#0f172a;transition:all 0.2s;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                            onblur="this.style.borderColor='';this.style.boxShadow=''">
                        <button type="button" onclick="togglePw('confPw','eyeConf')" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0.25rem;" tabindex="-1">
                            <svg id="eyeConf" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Submit --}}
                <div style="padding-top:0.75rem;border-top:1px solid #e2e8f0;">
                    <button type="submit" class="btn btn-warning" style="width:100%;justify-content:center;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{--  Kolom Kanan: Info Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:1rem;" id="profileSidebar">

        {{-- Info Akun --}}
        <div class="glass-card animate-in animate-delay-2" style="padding:1.25rem;overflow:hidden;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid #e2e8f0;">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4M12 8h.01"/></svg>
                </div>
                <p style="font-size:0.85rem;font-weight:700;color:#0f172a;margin:0;">Ringkasan Akun</p>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <div>
                    <p style="font-size:0.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Nama</p>
                    <p style="font-size:0.85rem;font-weight:600;color:#1e293b;margin:0;">{{ $user->name }}</p>
                </div>
                <div>
                    <p style="font-size:0.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Email</p>
                    <p style="font-size:0.82rem;color:#475569;margin:0;word-break:break-all;">{{ $user->email }}</p>
                </div>
                @if($user->phone)
                <div>
                    <p style="font-size:0.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Telepon</p>
                    <p style="font-size:0.82rem;color:#475569;margin:0;">{{ $user->phone }}</p>
                </div>
                @endif
                <div>
                    <p style="font-size:0.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Role</p>
                    <span style="display:inline-flex;align-items:center;padding:0.2rem 0.6rem;border-radius:9999px;font-size:0.7rem;font-weight:700;background:#e0e7ff;color:#3730a3;">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </div>
                <div>
                    <p style="font-size:0.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Status Email</p>
                    @if($user->email_verified_at)
                        <span style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;border-radius:9999px;font-size:0.7rem;font-weight:700;background:#d1fae5;color:#065f46;">
                            <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Terverifikasi
                        </span>
                    @else
                        <span style="display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.6rem;border-radius:9999px;font-size:0.7rem;font-weight:700;background:#fef3c7;color:#92400e;">
                            ⚠️ Belum Terverifikasi
                        </span>
                    @endif
                </div>
                <div>
                    <p style="font-size:0.68rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 0.2rem;">Bergabung</p>
                    <p style="font-size:0.82rem;color:#475569;margin:0;">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Tips Keamanan --}}
        <div class="glass-card animate-in animate-delay-3" style="padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.875rem;">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#059669,#10b981);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <p style="font-size:0.85rem;font-weight:700;color:#0f172a;margin:0;">Tips Keamanan</p>
            </div>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.5rem;">
                <li style="display:flex;align-items:flex-start;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <span style="color:#059669;flex-shrink:0;">✓</span>
                    Gunakan password minimal <strong>8 karakter</strong>
                </li>
                <li style="display:flex;align-items:flex-start;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <span style="color:#059669;flex-shrink:0;">✓</span>
                    Kombinasikan huruf, angka, dan simbol
                </li>
                <li style="display:flex;align-items:flex-start;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <span style="color:#059669;flex-shrink:0;">✓</span>
                    Jangan gunakan password yang sama di tempat lain
                </li>
                <li style="display:flex;align-items:flex-start;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <span style="color:#f59e0b;flex-shrink:0;">⚠</span>
                    Jangan bagikan password kepada siapapun
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Responsive CSS --}}
<style>
    @media (max-width: 768px) {
        #profileGrid {
            grid-template-columns: 1fr !important;
        }
        #profileSidebar {
            order: -1;
        }
    }
    @media (max-width: 900px) and (min-width: 769px) {
        #profileGrid {
            grid-template-columns: 1fr 260px !important;
        }
    }
</style>

@push('scripts')
<script>
    function togglePw(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (!input) return;
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        icon.innerHTML = isHidden
            ? '<path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>'
            : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
    }

    function checkStrength(val) {
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');
        if (!bar || !label) return;
        let score = 0;
        if (val.length >= 8)  score++;
        if (val.length >= 12) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const levels = [
            { w:'0%',  bg:'transparent', txt:'' },
            { w:'25%', bg:'#ef4444', txt:'Sangat Lemah' },
            { w:'40%', bg:'#f97316', txt:'Lemah' },
            { w:'60%', bg:'#f59e0b', txt:'Cukup' },
            { w:'80%', bg:'#22c55e', txt:'Kuat' },
            { w:'100%',bg:'#059669', txt:'Sangat Kuat' },
        ];
        const lv = levels[Math.min(score, 5)];
        bar.style.width      = lv.w;
        bar.style.background = lv.bg;
        label.textContent    = lv.txt;
        label.style.color    = lv.bg || '#94a3b8';
    }
</script>
@endpush
@endsection
