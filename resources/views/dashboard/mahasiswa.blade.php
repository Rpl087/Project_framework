@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa')

@section('content')
<div>
    <!-- Welcome -->
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
        <p style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Berikut ringkasan peminjaman Anda.</p>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card animate-in animate-delay-1">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Total Peminjaman</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;margin-top:0.25rem;">{{ $stats['total_borrowings'] }}</p>
                </div>
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#4f46e5" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card animate-in animate-delay-2">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Sedang Dipinjam</p>
                    <p style="font-size:2rem;font-weight:800;color:#059669;margin-top:0.25rem;">{{ $stats['active_borrowings'] }}</p>
                </div>
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card animate-in animate-delay-3">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Menunggu</p>
                    <p style="font-size:2rem;font-weight:800;color:#d97706;margin-top:0.25rem;">{{ $stats['pending_borrowings'] }}</p>
                </div>
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#fef3c7,#fde68a);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card animate-in animate-delay-4">
            <div style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Selesai</p>
                    <p style="font-size:2rem;font-weight:800;color:#0f172a;margin-top:0.25rem;">{{ $stats['completed_borrowings'] }}</p>
                </div>
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);display:flex;align-items:center;justify-content:center;">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#475569" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:2rem;" class="animate-in animate-delay-2">
        <a href="{{ route('catalog') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Lihat Katalog Alat
        </a>
        <a href="{{ route('borrowings.create') }}" class="btn btn-success">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Peminjaman Baru
        </a>
    </div>

    <!-- Recent Borrowings -->
    <div class="glass-card animate-in animate-delay-3">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid #f1f5f9;">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">Peminjaman Terbaru</h3>
        </div>
        @if($recentBorrowings->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Waktu Pinjam</th>
                        <th>Waktu Kembali</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBorrowings as $b)
                    <tr>
                        <td style="font-weight:600;">{{ $b->equipment->name }}</td>
                        <td>{{ $b->start_date }}</td>
                        <td>{{ $b->end_date }}</td>
                        <td><span class="badge badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                        <td>
                            <a href="{{ route('borrowings.show', $b) }}" class="btn btn-outline btn-sm">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:3rem;text-align:center;">
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p style="color:#94a3b8;font-size:0.9rem;">Belum ada data peminjaman.</p>
                <a href="{{ route('catalog') }}" class="btn btn-primary" style="margin-top:1rem;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari Alat di Katalog
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
