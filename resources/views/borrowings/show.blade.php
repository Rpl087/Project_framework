@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('content')
<div style="max-width:800px;">
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <a href="{{ route('borrowings.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:#64748b;font-size:0.85rem;text-decoration:none;margin-bottom:0.75rem;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#64748b'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar
        </a>
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1rem;">
            <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Detail Peminjaman #{{ $borrowing->id }}</h1>
            <span class="badge badge-{{ $borrowing->status_color }}" style="font-size:0.85rem;">{{ $borrowing->status_label }}</span>
        </div>
    </div>

    <!-- Info Cards -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.25rem;margin-bottom:1.5rem;">
        <!-- Borrower Info -->
        <div class="glass-card animate-in animate-delay-1" style="padding:1.5rem;">
            <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">Informasi Peminjam</h3>
            <div style="display:grid;gap:0.75rem;">
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Nama</span>
                    <p style="font-weight:600;color:#1e293b;">{{ $borrowing->user->name }}</p>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Email</span>
                    <p style="color:#475569;">{{ $borrowing->user->email }}</p>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Diajukan</span>
                    <p style="color:#475569;">{{ $borrowing->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Equipment Info -->
        <div class="glass-card animate-in animate-delay-2" style="padding:1.5rem;">
            <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">Informasi Alat</h3>
            <div style="display:grid;gap:0.75rem;">
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Nama Alat</span>
                    <p style="font-weight:600;color:#1e293b;">{{ $borrowing->equipment->name }}</p>
                </div>
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Kategori</span>
                    <span class="badge badge-{{ $borrowing->equipment->category === 'khusus' ? 'indigo' : 'blue' }}">{{ ucfirst($borrowing->equipment->category) }}</span>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;">
                    <div>
                        <span style="font-size:0.75rem;color:#94a3b8;">Waktu Pinjam</span>
                        <p style="font-weight:600;color:#1e293b;">{{ $borrowing->start_date }}</p>
                    </div>
                    <div>
                        <span style="font-size:0.75rem;color:#94a3b8;">Waktu Kembali</span>
                        <p style="font-weight:600;color:#1e293b;">{{ $borrowing->end_date }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Purpose -->
    <div class="glass-card animate-in animate-delay-2" style="padding:1.5rem;margin-bottom:1.5rem;">
        <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.75rem;">Tujuan Peminjaman</h3>
        <p style="color:#334155;line-height:1.6;">{{ $borrowing->purpose }}</p>
    </div>

    <!-- Reject Reason -->
    @if($borrowing->status === 'rejected' && $borrowing->reject_reason)
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem;" class="animate-in animate-delay-2">
        <h3 style="font-size:0.8rem;font-weight:700;color:#991b1b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Alasan Penolakan</h3>
        <p style="color:#991b1b;">{{ $borrowing->reject_reason }}</p>
    </div>
    @endif

    <!-- Return Condition -->
    @if($borrowing->return_condition)
    <div style="background:#d1fae5;border:1px solid #a7f3d0;border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem;" class="animate-in animate-delay-2">
        <h3 style="font-size:0.8rem;font-weight:700;color:#065f46;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Kondisi Pengembalian</h3>
        <p style="color:#065f46;">{{ $borrowing->return_condition }}</p>
    </div>
    @endif

    <!-- Action Buttons -->
    @if(!auth()->user()->isMahasiswa())
    <div class="glass-card animate-in animate-delay-3" style="padding:1.5rem;margin-bottom:1.5rem;">
        <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">Aksi</h3>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">

            {{-- Laboran: Approve pending --}}
            @if(auth()->user()->isLaboran() && $borrowing->status === 'pending')
                <form method="POST" action="{{ route('borrowings.approve-laboran', $borrowing) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Setujui peminjaman ini?')">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui
                    </button>
                </form>
            @endif

            {{-- Kepala Lab: Approve approved_by_laboran --}}
            @if(auth()->user()->isKepalaLab() && $borrowing->status === 'approved_by_laboran')
                <form method="POST" action="{{ route('borrowings.approve-kepala-lab', $borrowing) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Setujui peminjaman alat khusus ini?')">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui (Kepala Lab)
                    </button>
                </form>
            @endif

            {{-- Laboran: Handover --}}
            {{-- FIX KRITIS-1: Mencakup KEDUA status siap serah terima:
                 - ready_for_pickup  : alat umum (disetujui Laboran)
                 - approved_by_kepala_lab : alat khusus (disetujui Kepala Lab) --}}
            @if(auth()->user()->isLaboran() && in_array($borrowing->status, ['ready_for_pickup', 'approved_by_kepala_lab']))
                <form method="POST" action="{{ route('borrowings.handover', $borrowing) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Serahkan alat kepada peminjam?')">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        Serah Terima
                    </button>
                </form>
            @endif

            {{-- Laboran: Process Return --}}
            @if(auth()->user()->isLaboran() && $borrowing->status === 'active')
                <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" style="flex:1;min-width:260px;">
                    @csrf
                    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:flex-end;">
                        <div style="flex:1;">
                            <label class="form-label">Kondisi Pengembalian</label>
                            <input type="text" name="return_condition" class="form-input" required placeholder="Contoh: Baik, tidak ada kerusakan" minlength="5">
                        </div>
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Proses pengembalian alat?')">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            Proses Kembali
                        </button>
                    </div>
                    @error('return_condition') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </form>
            @endif

            {{-- Reject (Laboran/Kepala Lab) --}}
            {{-- FIX SEDANG-1: Kepala Lab juga bisa menolak peminjaman 'pending'
                 sebagai mekanisme koreksi admin, sesuai kapabilitas BorrowingController::reject() --}}
            @if(
                (auth()->user()->isLaboran() && $borrowing->status === 'pending') ||
                (auth()->user()->isKepalaLab() && in_array($borrowing->status, ['pending', 'approved_by_laboran', 'approved_by_kepala_lab']))
            )
                <form method="POST" action="{{ route('borrowings.reject', $borrowing) }}" style="flex:1;min-width:260px;">
                    @csrf
                    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:flex-end;">
                        <div style="flex:1;">
                            <label class="form-label">Alasan Penolakan</label>
                            <input type="text" name="reject_reason" class="form-input" required placeholder="Jelaskan alasan penolakan..." minlength="5">
                        </div>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak peminjaman ini?')">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak
                        </button>
                    </div>
                    @error('reject_reason') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </form>
            @endif

            @if(!in_array($borrowing->status, ['pending', 'approved_by_laboran', 'approved_by_kepala_lab', 'ready_for_pickup', 'active']))
                <p style="color:#94a3b8;font-size:0.85rem;">Tidak ada aksi yang diperlukan.</p>
            @endif
        </div>
    </div>
    @endif

    <!-- Activity Log -->
    <div class="glass-card animate-in animate-delay-3">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">📋 Riwayat Aktivitas</h3>
        </div>
        <div style="padding:1rem 1.5rem;">
            @if($borrowing->logs->count() > 0)
                <div style="position:relative;padding-left:1.5rem;">
                    <!-- Timeline line -->
                    <div style="position:absolute;left:5px;top:8px;bottom:8px;width:2px;background:#e2e8f0;"></div>

                    @foreach($borrowing->logs->sortByDesc('created_at') as $log)
                    <div style="position:relative;margin-bottom:1.25rem;">
                        <!-- Timeline dot -->
                        <div style="position:absolute;left:-1.5rem;top:4px;width:12px;height:12px;border-radius:50%;background:#6366f1;border:2px solid #fff;box-shadow:0 0 0 2px #e0e7ff;"></div>
                        <div>
                            <p style="font-size:0.875rem;color:#334155;">{{ $log->action_description }}</p>
                            <p style="font-size:0.75rem;color:#94a3b8;margin-top:0.25rem;">
                                {{ $log->user->name }} • {{ $log->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p style="color:#94a3b8;font-size:0.85rem;text-align:center;padding:1rem;">Belum ada log aktivitas.</p>
            @endif
        </div>
    </div>
</div>
@endsection
