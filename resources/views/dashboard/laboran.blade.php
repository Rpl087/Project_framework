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
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;">
        <div class="stat-card animate-in animate-delay-1">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Total Alat</p>
            <p style="font-size:2rem;font-weight:800;color:#4f46e5;margin-top:0.25rem;">{{ $stats['total_equipment'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-2" style="{{ $stats['maintenance_equipment'] > 0 ? 'border-left:3px solid #f59e0b;' : '' }}">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Maintenance</p>
            <p style="font-size:2rem;font-weight:800;color:#d97706;margin-top:0.25rem;">{{ $stats['maintenance_equipment'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-2" style="{{ $stats['pending_requests'] > 0 ? 'border-left:3px solid #ef4444;' : '' }}">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Permintaan Masuk</p>
            <p style="font-size:2rem;font-weight:800;color:#ef4444;margin-top:0.25rem;">{{ $stats['pending_requests'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-3">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Sedang Dipinjam</p>
            <p style="font-size:2rem;font-weight:800;color:#059669;margin-top:0.25rem;">{{ $stats['active_borrowings'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-3" style="{{ $stats['overdue_borrowings'] > 0 ? 'border-left:3px solid #ef4444;' : '' }}">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Terlambat</p>
            <p style="font-size:2rem;font-weight:800;color:#ef4444;margin-top:0.25rem;">{{ $stats['overdue_borrowings'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-4" style="{{ $stats['issue_reported'] > 0 ? 'border-left:3px solid #f97316;' : '' }}">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Ada Masalah</p>
            <p style="font-size:2rem;font-weight:800;color:#ea580c;margin-top:0.25rem;">{{ $stats['issue_reported'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-4" style="border-left:3px solid #06b6d4;">
            <p style="font-size:0.72rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Siap Serah Terima</p>
            <p style="font-size:2rem;font-weight:800;color:#06b6d4;margin-top:0.25rem;">{{ $stats['ready_for_pickup'] }}</p>
        </div>
    </div>

    <!-- Baris 1: Permintaan Menunggu + Siap Serah Terima -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;margin-bottom:1.5rem;">
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
                            <p style="font-size:0.75rem;color:#94a3b8;">{{ $req->user->name }} &bull; {{ $req->start_date }} – {{ $req->end_date }}</p>
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

        <!-- Ready for Handover -->
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
                        <span class="badge badge-cyan" style="font-size:0.65rem;">Siap ✓</span>
                    </a>
                    @endforeach
                </div>
            @else
                <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                    Tidak ada alat yang menunggu serah terima.
                </div>
            @endif
        </div>
    </div>

    <!-- Baris 2: Peminjaman Aktif (termasuk terlambat) -->
    <div style="margin-bottom:1.5rem;">
        <div class="glass-card animate-in animate-delay-4">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">&#x26A1; Peminjaman Aktif</h3>
                <div style="display:flex;gap:0.5rem;align-items:center;">
                    <span class="badge badge-emerald">{{ $stats['active_borrowings'] }} aktif</span>
                    @if($stats['overdue_borrowings'] > 0)
                        <span class="badge badge-red">{{ $stats['overdue_borrowings'] }} terlambat</span>
                    @endif
                </div>
            </div>
            @if($activeBorrowings->count() > 0)
                <div style="padding:0.5rem;">
                    @foreach($activeBorrowings as $ab)
                    <a href="{{ route('borrowings.show', $ab) }}" style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div>
                            <p style="font-size:0.875rem;font-weight:600;color:#1e293b;">{{ $ab->equipment->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;">{{ $ab->user->name }} &bull; Kembali: {{ $ab->end_date }}</p>
                        </div>
                        @if($ab->status === 'overdue')
                            <span class="badge badge-red">🕐 Terlambat</span>
                        @else
                            @php
                                $isOverdue = !$ab->updated_at->isToday()
                                    || now()->format('H:i') > $ab->end_date;
                            @endphp
                            @if($isOverdue)
                                <span class="badge badge-red">🕐 Terlambat</span>
                            @else
                                <span class="badge badge-emerald">Aktif</span>
                            @endif
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

    <!-- Baris 3: Issues + Maintenance -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;">

        <!-- Issues Box -->
        <div class="glass-card animate-in animate-delay-4" style="{{ $issueReportedBorrowings->count() > 0 ? 'border:1px solid #fed7aa;' : '' }}">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;{{ $issueReportedBorrowings->count() > 0 ? 'background:linear-gradient(90deg,#fff7ed,transparent);' : '' }}">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">⚠️ Laporan Masalah (Issues)</h3>
                @if($stats['issue_reported'] > 0)
                    <span class="badge badge-rose">{{ $stats['issue_reported'] }}</span>
                @else
                    <span class="badge badge-gray">0</span>
                @endif
            </div>
            @if($issueReportedBorrowings->count() > 0)
                <div style="padding:0.5rem;">
                    @foreach($issueReportedBorrowings as $issue)
                    <a href="{{ route('borrowings.show', $issue) }}" style="display:flex;align-items:flex-start;gap:0.75rem;padding:0.75rem 1rem;border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.15s;" onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='transparent'">
                        <div style="width:36px;height:36px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <p style="font-size:0.875rem;font-weight:600;color:#9a3412;">{{ $issue->equipment->name }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;margin-top:0.1rem;">Peminjam: {{ $issue->user->name }}</p>
                            <p style="font-size:0.7rem;color:#f97316;font-weight:600;margin-top:0.2rem;">🔧 Klik untuk tangani masalah</p>
                        </div>
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2" style="flex-shrink:0;margin-top:4px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            @else
                <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#a7f3d0" stroke-width="1.5" style="margin:0 auto 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak ada laporan masalah aktif.
                </div>
            @endif
        </div>

        <!-- Maintenance Equipment -->
        <div class="glass-card animate-in animate-delay-4" style="{{ $maintenanceEquipments->count() > 0 ? 'border:1px solid #fde68a;' : '' }}">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;{{ $maintenanceEquipments->count() > 0 ? 'background:linear-gradient(90deg,#fffbeb,transparent);' : '' }}">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">🔧 Alat dalam Maintenance</h3>
                @if($stats['maintenance_equipment'] > 0)
                    <span class="badge badge-amber">{{ $stats['maintenance_equipment'] }}</span>
                @else
                    <span class="badge badge-gray">0</span>
                @endif
            </div>
            @if($maintenanceEquipments->count() > 0)
                <div style="padding:0.5rem;">
                    @foreach($maintenanceEquipments as $mt)
                    <a href="{{ route('equipments.edit', $mt) }}" style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.15s;" onmouseover="this.style.background='#fffbeb'" onmouseout="this.style.background='transparent'">
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <div style="width:36px;height:36px;border-radius:50%;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:0.875rem;font-weight:600;color:#92400e;">{{ $mt->name }}</p>
                                <p style="font-size:0.72rem;color:#94a3b8;">Stok: {{ $mt->available_stock }}/{{ $mt->total_stock }} &bull; {{ ucfirst($mt->category) }}</p>
                            </div>
                        </div>
                        <span style="font-size:0.7rem;color:#d97706;font-weight:600;">Edit →</span>
                    </a>
                    @endforeach
                </div>
            @else
                <div style="padding:2rem;text-align:center;color:#94a3b8;font-size:0.85rem;">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#a7f3d0" stroke-width="1.5" style="margin:0 auto 0.5rem;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Semua alat dalam kondisi baik.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
