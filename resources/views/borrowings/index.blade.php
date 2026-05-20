@extends('layouts.app')
@section('title', 'Daftar Peminjaman')

@section('content')
<div>
    <div class="animate-in" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Daftar Peminjaman</h1>
            <p style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">
                @if(auth()->user()->isMahasiswa())
                    Riwayat peminjaman Anda.
                @else
                    Semua data peminjaman.
                @endif
            </p>
        </div>
        @if(auth()->user()->isMahasiswa())
        <a href="{{ route('borrowings.create') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Baru
        </a>
        @endif
    </div>

    <div class="glass-card animate-in animate-delay-1">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    @if(!auth()->user()->isMahasiswa())
                        <th>Peminjam</th>
                    @endif
                    <th>Alat</th>
                    <th>Kategori</th>
                    <th>Waktu Pinjam</th>
                    <th>Waktu Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrowings as $b)
                <tr>
                    <td>{{ $loop->iteration + ($borrowings->currentPage() - 1) * $borrowings->perPage() }}</td>
                    @if(!auth()->user()->isMahasiswa())
                        <td style="font-weight:600;">{{ $b->user->name }}</td>
                    @endif
                    <td style="font-weight:600;">{{ $b->equipment->name }}</td>
                    <td>
                        @if($b->equipment->category === 'khusus')
                            <span class="badge badge-indigo">Khusus</span>
                        @else
                            <span class="badge badge-blue">Umum</span>
                        @endif
                    </td>
                    <td>{{ $b->start_date }}</td>
                    <td>{{ $b->end_date }}</td>
                    <td><span class="badge badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                    <td>
                        <a href="{{ route('borrowings.show', $b) }}" class="btn btn-outline btn-sm">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:#94a3b8;">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 0.75rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Belum ada data peminjaman.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($borrowings->hasPages())
        <div style="padding:1rem 1.5rem;border-top:1px solid #f1f5f9;">
            {{ $borrowings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
