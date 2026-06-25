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
        <div class="stat-card animate-in animate-delay-1" style="{{ $stats['pending_approvals'] > 0 ? 'border-left:3px solid #ef4444;' : '' }}">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Menunggu Persetujuan</p>
            <p style="font-size:2rem;font-weight:800;color:#ef4444;margin-top:0.25rem;">{{ $stats['pending_approvals'] }}</p>
        </div>
        <div class="stat-card animate-in animate-delay-2" style="border-left:3px solid #06b6d4;">
            <p style="font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;">Sudah Disetujui</p>
            <p style="font-size:2rem;font-weight:800;color:#06b6d4;margin-top:0.25rem;">{{ $stats['approved_count'] }}</p>
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

    <!-- Pending Approvals — tabel dengan tombol Setujui & Tolak langsung -->
    <div class="glass-card animate-in animate-delay-2" style="{{ $pendingApprovals->count() > 0 ? 'border:1px solid #fde68a;' : '' }}">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);display:flex;align-items:center;justify-content:space-between;{{ $pendingApprovals->count() > 0 ? 'background:linear-gradient(90deg,#fefce8,transparent);' : '' }}">
            <div>
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">🔐 Permintaan Peminjaman — Menunggu Persetujuan Anda</h3>
                <p style="font-size:0.78rem;color:#64748b;margin-top:0.2rem;">Alat khusus yang sudah disetujui Laboran dan perlu persetujuan Kepala Lab.</p>
            </div>
            <span class="badge badge-amber" style="font-size:0.9rem;padding:0.3rem 0.9rem;">{{ $stats['pending_approvals'] }}</span>
        </div>
        @if($pendingApprovals->count() > 0)
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Waktu Pinjam</th>
                            <th>Waktu Kembali</th>
                            <th>Tujuan</th>
                            <th style="text-align:center;">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingApprovals as $i => $ap)
                        <tr>
                            <td style="color:#94a3b8;font-size:0.8rem;">{{ $i + 1 }}</td>
                            <td>
                                <p style="font-weight:600;color:#1e293b;">{{ $ap->user->name }}</p>
                                <p style="font-size:0.72rem;color:#94a3b8;">{{ $ap->user->email }}</p>
                            </td>
                            <td>
                                <p style="font-weight:600;">{{ $ap->equipment->name }}</p>
                                <span class="badge badge-indigo" style="font-size:0.65rem;">Khusus</span>
                            </td>
                            <td>{{ $ap->start_date }}</td>
                            <td>{{ $ap->end_date }}</td>
                            <td style="max-width:180px;">
                                <p style="font-size:0.8rem;color:#475569;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $ap->purpose }}">{{ $ap->purpose }}</p>
                            </td>
                            <td>
                                <div style="display:flex;gap:0.4rem;justify-content:center;flex-wrap:wrap;">
                                    {{-- Tombol Setujui Langsung --}}
                                    <form method="POST" action="{{ route('borrowings.approve-kepala-lab', $ap) }}" style="display:inline;">
                                        @csrf
                                        <button type="button" class="btn btn-success btn-sm"
                                            data-confirm="Setujui peminjaman {{ addslashes($ap->equipment->name) }} oleh {{ addslashes($ap->user->name) }}?"
                                            data-confirm-title="Setujui Peminjaman"
                                            data-confirm-type="success"
                                            data-confirm-icon="✅"
                                            data-confirm-label="Ya, Setujui"
                                            title="Setujui — status langsung Siap Diambil">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Setujui
                                        </button>
                                    </form>

                                    {{-- Tombol Tolak Langsung --}}
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="openRejectModal({{ $ap->id }}, '{{ addslashes($ap->equipment->name) }}')"
                                        title="Tolak peminjaman ini">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Tolak
                                    </button>

                                    {{-- Link detail --}}
                                    <a href="{{ route('borrowings.show', $ap) }}" class="btn btn-outline btn-sm" title="Lihat detail peminjaman">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="padding:3rem;text-align:center;">
                <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#a7f3d0" stroke-width="1.5" style="margin:0 auto 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="color:#94a3b8;font-size:0.9rem;">Tidak ada persetujuan yang menunggu. Semua beres! ✅</p>
            </div>
        @endif
    </div>

    <!-- Laporan Ringkasan — link cepat -->
    <div style="margin-top:1.5rem;">
        <div class="glass-card animate-in animate-delay-3">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);">
                <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">📊 Akses Cepat</h3>
            </div>
            <div style="padding:1.25rem 1.5rem;display:flex;flex-wrap:wrap;gap:0.75rem;">
                <a href="{{ route('borrowings.index') }}" class="btn btn-outline">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Semua Peminjaman
                </a>
                <a href="{{ route('borrowings.index', ['status' => 'approved_by_laboran']) }}" class="btn btn-outline" style="border-color:#fde68a;color:#92400e;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Menunggu Persetujuan
                </a>
                <a href="{{ route('borrowings.index', ['status' => 'active']) }}" class="btn btn-outline" style="border-color:#a7f3d0;color:#065f46;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Aktif Dipinjam
                </a>
                <a href="{{ route('borrowings.index', ['status' => 'overdue']) }}" class="btn btn-outline" style="border-color:#fca5a5;color:#991b1b;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Terlambat Dikembalikan
                </a>
                <a href="{{ route('borrowings.export-pdf', request()->query()) }}" class="btn btn-outline" style="background:#fee2e2;border-color:#fca5a5;color:#991b1b;" data-no-loading>
                    📄 Export PDF
                </a>
                <a href="{{ route('borrowings.export-csv', request()->query()) }}" class="btn btn-outline" style="background:#d1fae5;border-color:#a7f3d0;color:#065f46;" data-no-loading>
                    📊 Export CSV
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tolak dari Dashboard Kepala Lab --}}
<div id="rejectModalKL" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:1.5rem;">
    <div style="background:#fff;border-radius:1rem;padding:2rem;width:100%;max-width:440px;box-shadow:0 32px 80px rgba(0,0,0,0.3);">
        <h3 style="font-size:1.1rem;font-weight:700;color:#0f172a;margin-bottom:0.25rem;">❌ Tolak Peminjaman</h3>
        <p id="rejectModalDesc" style="font-size:0.85rem;color:#64748b;margin-bottom:1.25rem;"></p>
        <form id="rejectFormKL" method="POST">
            @csrf
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.375rem;">Alasan Penolakan *</label>
                <textarea name="reject_reason" required minlength="5" maxlength="255" rows="3"
                    style="width:100%;padding:0.625rem;border:1px solid #d1d5db;border-radius:0.5rem;font-size:0.875rem;outline:none;resize:vertical;"
                    placeholder="Jelaskan alasan penolakan..." id="rejectReasonKL"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:0.25rem;">
                    <span style="font-size:0.72rem;color:#94a3b8;">Min. 5 karakter</span>
                    <span id="rejectCharCountKL" style="font-size:0.72rem;color:#94a3b8;">0/255</span>
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn btn-danger" style="flex:1;">Konfirmasi Tolak</button>
                <button type="button" onclick="closeRejectModal()" class="btn btn-outline" style="flex:1;">Batal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const rejectModalKL = document.getElementById('rejectModalKL');
    const rejectFormKL  = document.getElementById('rejectFormKL');
    const rejectDesc    = document.getElementById('rejectModalDesc');
    const rejectArea    = document.getElementById('rejectReasonKL');
    const rejectCount   = document.getElementById('rejectCharCountKL');

    function openRejectModal(id, equipmentName) {
        rejectFormKL.action = '/borrowings/' + id + '/reject';
        rejectDesc.textContent = 'Tolak peminjaman alat: ' + equipmentName;
        rejectArea.value = '';
        rejectCount.textContent = '0/255';
        rejectModalKL.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        rejectModalKL.style.display = 'none';
        document.body.style.overflow = '';
    }

    rejectArea.addEventListener('input', function() {
        const len = this.value.length;
        rejectCount.textContent = len + '/255';
        rejectCount.style.color = len >= 230 ? '#ef4444' : '#94a3b8';
    });

    // Tutup jika klik backdrop
    rejectModalKL.addEventListener('click', function(e) {
        if (e.target === rejectModalKL) closeRejectModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeRejectModal();
    });
</script>
@endpush
@endsection
