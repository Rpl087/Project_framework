@extends('layouts.app')
@section('title', 'Manajemen ' . ucfirst($targetRole))

@section('content')
<div>
    <div class="animate-in" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
        <div>
            @if($targetRole === 'mahasiswa')
                <h1 style="font-size:1.5rem;font-weight:800;color:var(--txt-1);">Manajemen Mahasiswa 🎓</h1>
                <p style="color:var(--txt-2);font-size:0.875rem;margin-top:0.25rem;">Kelola akun mahasiswa yang dapat meminjam alat laboratorium.</p>
            @else
                <h1 style="font-size:1.5rem;font-weight:800;color:var(--txt-1);">Manajemen Laboran 🔬</h1>
                <p style="color:var(--txt-2);font-size:0.875rem;margin-top:0.25rem;">Kelola akun laboran yang bertugas di laboratorium.</p>
            @endif
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah {{ ucfirst($targetRole) }}
        </a>
    </div>

    {{-- Search --}}
    <div class="glass-card animate-in animate-delay-1" style="padding:1.25rem;margin-bottom:1.25rem;">
        <form method="GET" action="{{ route('users.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:200px;">
                <label class="form-label">Cari {{ ucfirst($targetRole) }}</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nama atau email...">
            </div>
            <div style="display:flex;gap:0.5rem;">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="glass-card animate-in animate-delay-2">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Role</th>
                    <th>Peminjaman</th>
                    <th>Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td style="color:var(--txt-3);font-size:0.8rem;">{{ $u->id }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.625rem;">
                                <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.75rem;flex-shrink:0;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span style="font-weight:600;color:var(--txt-1);">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td style="color:var(--txt-2);">{{ $u->email }}</td>
                        <td style="color:var(--txt-2);font-size:0.85rem;">
                            @if($u->phone)
                                <a href="tel:{{ $u->phone }}" style="color:#4f46e5;text-decoration:none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ $u->phone }}</a>
                            @else
                                <span style="color:var(--txt-3);">—</span>
                            @endif
                        </td>
                        <td>
                            @if($u->role === 'mahasiswa')
                                <span class="badge badge-blue">🎓 Mahasiswa</span>
                            @elseif($u->role === 'laboran')
                                <span class="badge badge-emerald">🔬 Laboran</span>
                            @else
                                <span class="badge badge-indigo">🏛️ Kepala Lab</span>
                            @endif
                        </td>
                        <td>
                            <span style="color:var(--txt-2);font-size:0.85rem;">{{ $u->borrowings_count }} total</span>
                            @if($u->active_borrowings_count > 0)
                                <span class="badge badge-amber" style="margin-left:0.25rem;">{{ $u->active_borrowings_count }} aktif</span>
                            @endif
                        </td>
                        <td style="color:var(--txt-3);font-size:0.8rem;">{{ $u->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:0.375rem;">
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-outline btn-sm">Edit</a>
                                <form method="POST" action="{{ route('users.destroy', $u) }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-confirm="Yakin ingin menghapus user {{ addslashes($u->name) }}?"
                                        data-confirm-title="Hapus User"
                                        data-confirm-type="danger"
                                        data-confirm-icon="🗑️"
                                        data-confirm-label="Ya, Hapus"
                                        {{ $u->active_borrowings_count > 0 ? 'disabled title=\'User masih punya peminjaman aktif\'' : '' }}>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;padding:2rem;color:var(--txt-3);">Tidak ada {{ $targetRole }} ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:1rem 1.5rem;">{{ $users->links() }}</div>
    </div>
</div>
@endsection
