@extends('layouts.app')
@section('title', 'Dashboard Laboran')

@section('content')
<div>
    <!-- Welcome -->
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Dashboard Laboran 🔬</h1>
        <p style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Kelola alat dan permintaan peminjaman laboratorium.</p>
    </div>

    <!-- Stats -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card animate-in animate-delay-1">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Total Alat</p>
            <p style="font-size:2rem;font-weight:800;color:#4f46e5;margin-top:0.25rem;">{{ $stats['total_equipment'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-2">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Maintenance</p>
            <p style="font-size:2rem;font-weight:800;color:#d97706;margin-top:0.25rem;">{{ $stats['maintenance_equipment'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-3" style="border-left:3px solid #ef4444;">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Permintaan Masuk</p>
            <p style="font-size:2rem;font-weight:800;color:#ef4444;margin-top:0.25rem;">{{ $stats['pending_requests'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-4">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Sedang Dipinjam</p>
            <p style="font-size:2rem;font-weight:800;color:#059669;margin-top:0.25rem;">{{ $stats['active_borrowings'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-4" style="border-left:3px solid #06b6d4;">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Siap Serah Terima</p>
            <p style="font-size:2rem;font-weight:800;color:#06b6d4;margin-top:0.25rem;">{{ $stats['ready_for_pickup'] }}</p>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;">
        <!-- Pending Requests -->
        <div class="glass-card animate-in animate-delay-2">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">⏳ Permintaan Menunggu</h3>
                <span class="badge badge-red">{{ $stats['pending_requests'] }}</span>
            </div>
            @if($pendingRequests->count() > 0)
                <div style="padding:0.5rem;">
                    @foreach($pendingRequests as $req)
                    <a href="{{ route('borrowings.show', $req) }}" style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div>
                            <p style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $req->equipment->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;">{{ $req->user->name }} &bull; {{ $req->start_date }}</p>
                        </div>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            @else
                <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                    Tidak ada permintaan menunggu.
                </div>
            @endif
        </div>

        <!-- Ready for Handover (umum + khusus approved Kepala Lab) -->
        <div class="glass-card animate-in animate-delay-3">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">📦 Siap Serah Terima</h3>
                <span class="badge badge-cyan">{{ $stats['ready_for_pickup'] }}</span>
            </div>
            @if($readyForHandover->count() > 0)
                <div style="padding:0.5rem;">
                    @foreach($readyForHandover as $rh)
                    <a href="{{ route('borrowings.show', $rh) }}" style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div>
                            <p style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $rh->equipment->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;">{{ $rh->user->name }} &bull; {{ $rh->start_date }}</p>
                        </div>
                        {{-- Badge: alat khusus (sudah disetujui kepala lab) vs umum --}}
                        @if($rh->status === 'approved_by_kepala_lab')
                            <span class="badge badge-indigo" style="font-size:0.65rem;">Khusus &check;</span>
                        @else
                            <span class="badge badge-cyan" style="font-size:0.65rem;">Umum &check;</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            @else
                <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                    Tidak ada alat yang menunggu serah terima.
                </div>
            @endif
        </div>

        <!-- Active Borrowings -->
        <div class="glass-card animate-in animate-delay-4">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">&#x26A1; Peminjaman Aktif</h3>
                <span class="badge badge-emerald">{{ $stats['active_borrowings'] }}</span>
            </div>
            @if($activeBorrowings->count() > 0)
                <div style="padding:0.5rem;">
                    @foreach($activeBorrowings as $ab)
                    <a href="{{ route('borrowings.show', $ab) }}" style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div>
                            <p style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $ab->equipment->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;">{{ $ab->user->name }} &bull; Kembali: {{ $ab->end_date }}</p>
                        </div>
                        @php
                            // Cek overdue berbasis updated_at (waktu alat di-handover/aktif),
                            // konsisten dengan logika MarkOverdueBorrowings command.
                            $isOverdue = !$ab->updated_at->isToday()
                                || now()->format('H:i') > $ab->end_date;
                        @endphp
                        @if($isOverdue)
                            <span class="badge badge-red">Terlambat</span>
                        @else
                            <span class="badge badge-emerald">Aktif</span>
                        @endif
                    </a>
                    @endforeach
                </div>
            @else
                <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                    Tidak ada peminjaman aktif.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
