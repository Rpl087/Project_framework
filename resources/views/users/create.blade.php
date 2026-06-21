@extends('layouts.app')
@section('title', 'Tambah ' . ucfirst($targetRole) . ' Baru')

@section('content')
<div style="max-width:640px;">
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <a href="{{ route('users.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--txt-2);font-size:0.85rem;text-decoration:none;margin-bottom:0.75rem;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color=''">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar {{ ucfirst($targetRole) }}
        </a>
        <h1 style="font-size:1.5rem;font-weight:800;color:var(--txt-1);">Tambah {{ ucfirst($targetRole) }} Baru</h1>
        <p style="color:var(--txt-2);font-size:0.875rem;margin-top:0.25rem;">
            @if($targetRole === 'mahasiswa')
                Tambahkan akun mahasiswa yang dapat meminjam alat laboratorium.
            @else
                Tambahkan akun laboran yang bertugas mengelola alat laboratorium.
            @endif
        </p>
    </div>

    <div class="glass-card animate-in animate-delay-1" style="padding:2rem;">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            {{-- Role tersembunyi, otomatis sesuai hak akses --}}
            <input type="hidden" name="role" value="{{ $targetRole }}">

            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="Nama lengkap {{ $targetRole }}">
                @error('name') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-input" required placeholder="email@domain.com">
                @error('email') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Nomor Telepon</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="Contoh: 081234567890" maxlength="20">
                @error('phone') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
                <div>
                    <label class="form-label">Password * (min. 8 karakter)</label>
                    <input type="password" name="password" class="form-input" required minlength="8" placeholder="••••••••">
                    @error('password') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Konfirmasi Password *</label>
                    <input type="password" name="password_confirmation" class="form-input" required placeholder="••••••••">
                </div>
            </div>

            {{-- Info role yang akan ditambahkan --}}
            <div style="margin-bottom:1.5rem;padding:0.75rem 1rem;background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);border-radius:0.5rem;">
                <p style="font-size:0.8rem;color:var(--txt-2);">
                    <strong>Role:</strong>
                    @if($targetRole === 'mahasiswa')
                        🎓 <strong>Mahasiswa</strong> — Dapat mengajukan peminjaman alat laboratorium.
                    @else
                        🔬 <strong>Laboran</strong> — Bertugas mengelola alat dan memproses peminjaman.
                    @endif
                </p>
            </div>

            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
