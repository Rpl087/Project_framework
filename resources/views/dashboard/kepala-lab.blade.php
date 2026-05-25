@extends('layouts.app')
@section('title', 'Dashboard Kepala Lab')

@section('content')
<div>
    <!-- Welcome -->
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Dashboard Kepala Lab 🏛️</h1>
        <p style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Persetujuan alat khusus dan ringkasan laboratorium.</p>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card animate-in animate-delay-1" style="border-left:3px solid #ef4444;">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Menunggu Persetujuan</p>
            <p style="font-size:2rem;font-weight:800;color:#ef4444;margin-top:0.25rem;">{{ $stats['pending_approvals'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-2">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Total Alat</p>
            <p style="font-size:2rem;font-weight:800;color:#4f46e5;margin-top:0.25rem;">{{ $stats['total_equipment'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-3">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Sedang Dipinjam</p>
            <p style="font-size:2rem;font-weight:800;color:#059669;margin-top:0.25rem;">{{ $stats['active_borrowings'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-4">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Selesai Bulan Ini</p>
            <p style="font-size:2rem;font-weight:800;color:#0f172a;margin-top:0.25rem;">{{ $stats['completed_this_month'] }}</p>
        </div>
    </div>

    <!-- Pending Approvals (Khusus Equipment) -->
    <div class="glass-card animate-in animate-delay-2">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">🔐 Menunggu Persetujuan Anda (Alat Khusus)</h3>
            <span class="badge badge-amber">{{ $stats['pending_approvals'] }}</span>
        </div>
        @if($pendingApprovals->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Kategori</th>
                        <th>Waktu Pinjam</th>
                        <th>Tujuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApprovals as $ap)
                    <tr>
                        <td style="font-weight:600;">{{ $ap->user->name }}</td>
                        <td>{{ $ap->equipment->name }}</td>
                        <td><span class="badge badge-indigo">{{ ucfirst($ap->equipment->category) }}</span></td>
                        <td>{{ $ap->start_date }}</td>
                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $ap->purpose }}</td>
                        <td>
                            <a href="{{ route('borrowings.show', $ap) }}" class="btn btn-primary btn-sm">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding:3rem;text-align:center;">
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#a7f3d0" stroke-width="1.5" style="margin:0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="color:#94a3b8;font-size:0.9rem;">Tidak ada persetujuan yang menunggu. Semua beres! ✅</p>
            </div>
        @endif
    </div>
</div>
@endsection
