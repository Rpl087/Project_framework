@extends('layouts.mahasiswa')
@section('title', 'Beranda')
@section('hero_sub', 'Kelola peminjaman alat laboratorium Anda dengan mudah.')

@section('hero_stats')
    <div class="u-hero-stat">
        <div class="u-hero-stat-num">{{ $stats['active_borrowings'] }}</div>
        <div class="u-hero-stat-label">Sedang Dipinjam</div>
    </div>
    <div class="u-hero-stat">
        <div class="u-hero-stat-num">{{ $stats['pending_borrowings'] }}</div>
        <div class="u-hero-stat-label">Dalam Proses</div>
    </div>
    <div class="u-hero-stat">
        <div class="u-hero-stat-num">{{ $stats['total_borrowings'] }}</div>
        <div class="u-hero-stat-label">Total Pinjaman</div>
    </div>
    <div class="u-hero-stat">
        <div class="u-hero-stat-num">{{ $stats['completed_borrowings'] }}</div>
        <div class="u-hero-stat-label">Selesai</div>
    </div>
@endsection

@section('content')

    {{-- Quick Actions --}}
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;margin-bottom:2rem;" class="u-animate">
        <a href="{{ route('catalog') }}" class="u-btn u-btn-primary">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            Lihat Katalog Alat
        </a>
        <a href="{{ route('borrowings.create') }}" class="u-btn u-btn-success">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Peminjaman Baru
        </a>
    </div>

    {{-- Recent Borrowings Table --}}
    <div class="u-card u-animate u-delay-2">
        <div class="u-card-header">
            <span class="u-card-title">📋 Peminjaman Terbaru</span>
            <a href="{{ route('borrowings.index') }}" class="u-btn u-btn-outline u-btn-sm">Lihat Semua</a>
        </div>
        @if($recentBorrowings->count() > 0)
            <div style="overflow-x:auto;">
                <table class="u-table">
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
                            <td style="font-weight:600;color:#1e293b;">{{ $b->equipment->name }}</td>
                            <td>{{ $b->start_date }}</td>
                            <td>{{ $b->end_date }}</td>
                            <td>
                                @php
                                    $badgeMap = [
                                        'pending'                => 'u-badge-amber',
                                        'approved_by_laboran'    => 'u-badge-blue',
                                        'approved_by_kepala_lab' => 'u-badge-cyan',
                                        'ready_for_pickup'       => 'u-badge-cyan',
                                        'active'                 => 'u-badge-emerald',
                                        'completed'              => 'u-badge-gray',
                                        'rejected'               => 'u-badge-red',
                                        'overdue'                => 'u-badge-red',
                                        'issue_reported'         => 'u-badge-purple',
                                    ];
                                    $badgeClass = $badgeMap[$b->status] ?? 'u-badge-gray';
                                @endphp
                                <span class="u-badge {{ $badgeClass }}">{{ $b->status_label }}</span>
                            </td>
                            <td>
                                <a href="{{ route('borrowings.show', $b) }}" class="u-btn u-btn-outline u-btn-sm">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding:3.5rem;text-align:center;">
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p style="color:#64748b;font-size:0.95rem;font-weight:600;margin-bottom:0.5rem;">Belum ada peminjaman</p>
                <p style="color:#94a3b8;font-size:0.85rem;margin-bottom:1.5rem;">Mulai dengan meminjam alat dari katalog kami.</p>
                <a href="{{ route('catalog') }}" class="u-btn u-btn-primary">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari Alat di Katalog
                </a>
            </div>
        @endif
    </div>

@endsection
