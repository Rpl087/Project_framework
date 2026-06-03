@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
<div style="max-width:640px;">
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--txt-1);">Edit Profil</h1>
        <p style="color:var(--txt-2);font-size:0.875rem;margin-top:0.25rem;">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    {{-- Info Profil --}}
    <div class="glass-card animate-in animate-delay-1" style="padding:2rem;margin-bottom:1.25rem;">
        <h2 style="font-size:1rem;font-weight:700;color:var(--txt-1);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border-s);">Informasi Akun</h2>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('PUT')
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                @error('name') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.5rem;">
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1rem;">
                <label class="form-label">Role</label>
                <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" class="form-input" disabled style="opacity:0.6;cursor:not-allowed;">
                <p style="font-size:0.7rem;color:var(--txt-3);margin-top:0.25rem;">Role tidak dapat diubah sendiri.</p>
            </div>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Ganti Password --}}
    <div class="glass-card animate-in animate-delay-2" style="padding:2rem;">
        <h2 style="font-size:1rem;font-weight:700;color:var(--txt-1);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border-s);">Ganti Password</h2>
        <form method="POST" action="{{ route('profile.password') }}">
            @csrf @method('PUT')
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Password Saat Ini *</label>
                <input type="password" name="current_password" class="form-input" required placeholder="••••••••">
                @error('current_password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Password Baru * (min. 8 karakter)</label>
                <input type="password" name="password" class="form-input" required placeholder="••••••••" minlength="8">
                @error('password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.5rem;">
                <label class="form-label">Konfirmasi Password Baru *</label>
                <input type="password" name="password_confirmation" class="form-input" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-warning">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection
