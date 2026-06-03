@extends('layouts.app')
@section('title', 'Edit User: ' . $user->name)

@section('content')
<div style="max-width:640px;">
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <a href="{{ route('users.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--txt-2);font-size:0.85rem;text-decoration:none;margin-bottom:0.75rem;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color=''">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar User
        </a>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--txt-1);">Edit User: {{ $user->name }}</h1>
    </div>

    <div class="glass-card animate-in animate-delay-1" style="padding:2rem;margin-bottom:1.25rem;">
        <h2 style="font-size:1rem;font-weight:700;color:var(--txt-1);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--border-s);">Informasi Akun</h2>
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                @error('name') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Role *</label>
                <select name="role" class="form-input" required {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    <option value="mahasiswa"  {{ old('role', $user->role) === 'mahasiswa'  ? 'selected' : '' }}>🎓 Mahasiswa</option>
                    <option value="laboran"    {{ old('role', $user->role) === 'laboran'    ? 'selected' : '' }}>🔬 Laboran</option>
                    <option value="kepala_lab" {{ old('role', $user->role) === 'kepala_lab' ? 'selected' : '' }}>🏛️ Kepala Lab</option>
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <p style="font-size:0.7rem;color:var(--txt-3);margin-top:0.25rem;">Tidak dapat mengubah role akun sendiri.</p>
                @endif
                @error('role') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <h3 style="font-size:0.875rem;font-weight:600;color:var(--txt-2);margin:1.25rem 0 0.75rem;">Ganti Password (opsional)</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <div>
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                    @error('password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="••••••••">
                </div>
            </div>

            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Perbarui
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
