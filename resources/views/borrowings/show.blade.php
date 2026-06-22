@extends(auth()->user()->isMahasiswa() ? 'layouts.mahasiswa' : 'layouts.app')
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
                @if(!auth()->user()->isMahasiswa() && $borrowing->user->phone)
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Telepon</span>
                    <a href="tel:{{ $borrowing->user->phone }}" style="color:#4f46e5;font-weight:600;text-decoration:none;">{{ $borrowing->user->phone }}</a>
                </div>
                @endif
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
                <div>
                    <span style="font-size:0.75rem;color:#94a3b8;">Status Alat</span>
                    <span class="badge badge-{{ $borrowing->equipment->status === 'good' ? 'emerald' : 'amber' }}">
                        {{ $borrowing->equipment->status === 'good' ? 'Baik' : 'Maintenance' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Purpose -->
    <div class="glass-card animate-in animate-delay-2" style="padding:1.5rem;margin-bottom:1.5rem;">
        <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.75rem;">Tujuan Peminjaman</h3>
        <p style="color:#334155;line-height:1.6;">{{ $borrowing->purpose }}</p>
    </div>

    {{-- Reject Reason --}}
    @if($borrowing->status === 'rejected' && $borrowing->reject_reason)
        @if($borrowing->reject_reason === 'Dibatalkan oleh peminjam.')
        <div style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem;" class="animate-in animate-delay-2">
            <h3 style="font-size:0.8rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Dibatalkan oleh Peminjam</h3>
            <p style="color:#64748b;">Pengajuan peminjaman ini dibatalkan oleh Anda sendiri.</p>
        </div>
        @else
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem;" class="animate-in animate-delay-2">
            <h3 style="font-size:0.8rem;font-weight:700;color:#991b1b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">Alasan Penolakan</h3>
            <p style="color:#991b1b;">{{ $borrowing->reject_reason }}</p>
        </div>
        @endif
    @endif

    <!-- Return Condition -->
    @if($borrowing->return_condition)
    @php $isRusak = str_starts_with($borrowing->return_condition, 'Rusak'); @endphp
    <div style="background:{{ $isRusak ? '#fee2e2' : '#d1fae5' }};border:1px solid {{ $isRusak ? '#fca5a5' : '#a7f3d0' }};border-radius:0.75rem;padding:1.25rem;margin-bottom:1.5rem;" class="animate-in animate-delay-2">
        <h3 style="font-size:0.8rem;font-weight:700;color:{{ $isRusak ? '#991b1b' : '#065f46' }};text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem;">
            {{ $isRusak ? '⚠️ Kondisi Pengembalian — Rusak' : '✅ Kondisi Pengembalian — Baik' }}
        </h3>
        <p style="color:{{ $isRusak ? '#991b1b' : '#065f46' }};">{{ $borrowing->return_condition }}</p>
    </div>
    @endif

    <!-- Action Buttons — Laboran & Kepala Lab -->
    @if(!auth()->user()->isMahasiswa())
    <div class="glass-card animate-in animate-delay-3" style="padding:1.5rem;margin-bottom:1.5rem;">
        <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">Aksi</h3>
        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">

            {{-- Laboran: Approve pending --}}
            @if(auth()->user()->isLaboran() && $borrowing->status === 'pending')
                <form method="POST" action="{{ route('borrowings.approve-laboran', $borrowing) }}" id="formApproveLaboran">
                    @csrf
                    <button type="button" class="btn btn-success"
                        onclick="showConfirm({
                            title: 'Setujui Peminjaman',
                            subtitle: 'Alat: {{ addslashes($borrowing->equipment->name) }}',
                            message: 'Peminjam: {{ addslashes($borrowing->user->name) }}\n\nSetujui permintaan ini? Status akan diteruskan ke Kepala Lab jika alat khusus, atau langsung Siap Diambil jika alat umum.',
                            icon: '✅',
                            type: 'success',
                            confirmLabel: 'Ya, Setujui',
                            onConfirm: () => document.getElementById(\'formApproveLaboran\').submit()
                        })">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui
                    </button>
                </form>
            @endif

            {{-- Kepala Lab: Approve approved_by_laboran --}}
            @if(auth()->user()->isKepalaLab() && $borrowing->status === 'approved_by_laboran')
                <form method="POST" action="{{ route('borrowings.approve-kepala-lab', $borrowing) }}" id="formApproveKL">
                    @csrf
                    <button type="button" class="btn btn-success"
                        onclick="showConfirm({
                            title: 'Setujui & Siap Diambil',
                            subtitle: 'Alat: {{ addslashes($borrowing->equipment->name) }}',
                            message: 'Peminjam: {{ addslashes($borrowing->user->name) }}\n\nMenyetujui permintaan alat khusus ini akan langsung mengubah status menjadi Siap Diambil. Laboran dapat melakukan serah terima.',
                            icon: '🏛️',
                            type: 'success',
                            confirmLabel: 'Ya, Setujui',
                            onConfirm: () => document.getElementById(\'formApproveKL\').submit()
                        })">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui & Siap Diambil
                    </button>
                </form>
            @endif

            {{-- Laboran: Handover --}}
            @if(auth()->user()->isLaboran() && in_array($borrowing->status, ['ready_for_pickup', 'approved_by_kepala_lab']))
                <form method="POST" action="{{ route('borrowings.handover', $borrowing) }}" id="formHandover">
                    @csrf
                    <button type="button" class="btn btn-primary"
                        onclick="showConfirm({
                            title: 'Serah Terima Alat',
                            subtitle: 'Alat: {{ addslashes($borrowing->equipment->name) }}',
                            message: 'Serahkan alat kepada {{ addslashes($borrowing->user->name) }}? Status peminjaman akan berubah menjadi Aktif Dipinjam.',
                            icon: '📦',
                            type: 'info',
                            confirmLabel: 'Serahkan Alat',
                            onConfirm: () => document.getElementById(\'formHandover\').submit()
                        })">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                        Serah Terima
                    </button>
                </form>
            @endif

            {{-- Laboran: Process Return --}}
            @if(auth()->user()->isLaboran() && in_array($borrowing->status, ['active', 'overdue']))
                <div style="flex:1;min-width:300px;background:{{ $borrowing->status === 'overdue' ? '#fff7ed' : '#f8fafc' }};border:1px solid {{ $borrowing->status === 'overdue' ? '#fed7aa' : '#e2e8f0' }};border-radius:0.75rem;padding:1.25rem;">
                    <p style="font-size:0.85rem;font-weight:700;color:{{ $borrowing->status === 'overdue' ? '#9a3412' : '#374151' }};margin-bottom:1rem;">
                        {{ $borrowing->status === 'overdue' ? '⚠️ Proses Pengembalian (Terlambat)' : '📦 Proses Pengembalian' }}
                    </p>
                    <form method="POST" action="{{ route('borrowings.return', $borrowing) }}" id="returnForm">
                        @csrf
                        <div style="margin-bottom:0.75rem;">
                            <label class="form-label">Keadaan Alat *</label>
                            <select name="condition_type" class="form-input" required id="conditionType" onchange="toggleConditionDetail()">
                                <option value="">— Pilih Keadaan —</option>
                                <option value="baik">✅ Baik / Tidak Ada Kerusakan</option>
                                <option value="rusak">⚠️ Rusak / Ada Kerusakan</option>
                            </select>
                            @error('condition_type') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div id="conditionDetailWrap" style="display:none;margin-bottom:0.75rem;">
                            <label class="form-label">Deskripsi Kerusakan</label>
                            <textarea name="condition_detail" class="form-input" rows="2" id="conditionDetail"
                                placeholder="Jelaskan kerusakan yang ditemukan..." maxlength="500"></textarea>
                            <p style="font-size:0.7rem;color:#94a3b8;margin-top:0.2rem;">Opsional — namun disarankan untuk dicatat.</p>
                            @error('condition_detail') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <button type="button" class="btn {{ $borrowing->status === 'overdue' ? 'btn-danger' : 'btn-warning' }}"
                            onclick="confirmReturn()">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                            </svg>
                            {{ $borrowing->status === 'overdue' ? 'Proses Kembali (Terlambat)' : 'Proses Kembali' }}
                        </button>
                    </form>
                </div>
            @endif

            {{-- Reject --}}
            @if(
                (auth()->user()->isLaboran() && $borrowing->status === 'pending') ||
                (auth()->user()->isKepalaLab() && in_array($borrowing->status, ['pending', 'approved_by_laboran', 'approved_by_kepala_lab']))
            )
                <div style="flex:1;min-width:280px;">
                    <form method="POST" action="{{ route('borrowings.reject', $borrowing) }}" id="formReject">
                        @csrf
                        <div style="margin-bottom:0.75rem;">
                            <label class="form-label">Alasan Penolakan</label>
                            <div style="position:relative;">
                                <input type="text" name="reject_reason" class="form-input" required
                                    placeholder="Jelaskan alasan penolakan..." minlength="5" maxlength="255"
                                    id="rejectReasonInput" oninput="updateRejectCounter()">
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-top:0.25rem;">
                                <span style="font-size:0.72rem;color:#94a3b8;">Min. 5 karakter</span>
                                <span id="rejectCharCounter" style="font-size:0.72rem;color:#94a3b8;">0/255</span>
                            </div>
                            @error('reject_reason') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <button type="button" class="btn btn-danger" onclick="submitReject()">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak
                        </button>
                    </form>
                </div>
            @endif

            {{-- Laboran: Resolve Issue --}}
            @if(auth()->user()->isLaboran() && $borrowing->status === 'issue_reported')
                <div style="flex:1;min-width:300px;background:#fff7ed;border:1px solid #fed7aa;border-radius:0.75rem;padding:1.25rem;">
                    <p style="font-size:0.85rem;font-weight:700;color:#9a3412;margin-bottom:1rem;">⚠️ Ada Laporan Masalah — Pilih Tindakan</p>
                    <form method="POST" action="{{ route('borrowings.resolve-issue', $borrowing) }}" id="formResolve">
                        @csrf
                        <input type="hidden" name="resolve_action" id="resolveActionInput" value="">
                        <div style="margin-bottom:0.75rem;">
                            <label class="form-label">Catatan Penanganan</label>
                            <input type="text" name="resolve_description" class="form-input" required id="resolveDesc" placeholder="Jelaskan penanganan yang dilakukan..." minlength="5">
                            @error('resolve_description') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            <button type="button" class="btn btn-success" onclick="submitResolve('continue')">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Masalah Selesai, Lanjutkan
                            </button>
                            <button type="button" class="btn btn-warning" onclick="submitResolve('complete')">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                                Selesaikan & Kembalikan
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if(!in_array($borrowing->status, ['pending', 'approved_by_laboran', 'approved_by_kepala_lab', 'ready_for_pickup', 'active', 'overdue', 'issue_reported']))
                <p style="color:#94a3b8;font-size:0.85rem;">Tidak ada aksi yang diperlukan.</p>
            @endif
        </div>
    </div>
    @endif

    {{-- Aksi Mahasiswa --}}
    @if(auth()->user()->isMahasiswa() && $borrowing->user_id === auth()->id())
        @if($borrowing->status === 'active')
        <div class="glass-card animate-in animate-delay-3" style="padding:1.5rem;margin-bottom:1.5rem;">
            <h3 style="font-size:0.8rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">Aksi Saya</h3>
            <form method="POST" action="{{ route('borrowings.report-issue', $borrowing) }}" style="max-width:480px;" id="formReportIssue">
                @csrf
                <div style="margin-bottom:0.75rem;">
                    <label class="form-label">Deskripsi Masalah</label>
                    <input type="text" name="issue_description" class="form-input" required id="issueDesc" placeholder="Jelaskan masalah yang ditemukan pada alat..." minlength="10">
                    @error('issue_description') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                <button type="button" class="btn btn-warning" onclick="submitReportIssue()">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Laporkan Masalah
                </button>
            </form>
        </div>
        @endif

        @if($borrowing->status === 'pending')
        <div class="glass-card animate-in animate-delay-3" style="padding:1.5rem;margin-bottom:1.5rem;background:#fff7ed;border:1px solid #fed7aa;">
            <h3 style="font-size:0.8rem;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">⚠️ Batalkan Pengajuan</h3>
            <p style="font-size:0.85rem;color:#9a3412;margin-bottom:1rem;">Pengajuan masih menunggu persetujuan. Anda dapat membatalkannya jika sudah tidak diperlukan.</p>
            <form method="POST" action="{{ route('borrowings.cancel', $borrowing) }}" id="formCancel">
                @csrf
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="showConfirm({
                        title: 'Batalkan Pengajuan',
                        subtitle: 'Alat: {{ addslashes($borrowing->equipment->name) }}',
                        message: 'Yakin ingin membatalkan pengajuan peminjaman ini? Stok alat akan dikembalikan.',
                        icon: '🚫',
                        type: 'danger',
                        confirmLabel: 'Ya, Batalkan',
                        onConfirm: () => document.getElementById(\'formCancel\').submit()
                    })">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batalkan Pengajuan
                </button>
            </form>
        </div>
        @endif
    @endif

    <!-- Activity Log -->
    <div class="glass-card animate-in animate-delay-3">
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border-s);">
            <h3 style="font-size:1rem;font-weight:700;color:#0f172a;">📋 Riwayat Aktivitas</h3>
        </div>
        <div style="padding:1rem 1.5rem;">
            @if($borrowing->logs->count() > 0)
                <div style="position:relative;padding-left:1.5rem;">
                    <div style="position:absolute;left:5px;top:8px;bottom:8px;width:2px;background:#e2e8f0;"></div>
                    @foreach($borrowing->logs->sortByDesc('created_at') as $log)
                    <div style="position:relative;margin-bottom:1.25rem;">
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

@push('scripts')
<script>
    // Counter alasan penolakan
    function updateRejectCounter() {
        const input   = document.getElementById('rejectReasonInput');
        const counter = document.getElementById('rejectCharCounter');
        if (!input || !counter) return;
        const len = input.value.length;
        counter.textContent  = len + '/255';
        counter.style.color  = len >= 230 ? '#ef4444' : '#94a3b8';
    }

    // Toggle detail kerusakan
    function toggleConditionDetail() {
        const type = document.getElementById('conditionType');
        const wrap = document.getElementById('conditionDetailWrap');
        const det  = document.getElementById('conditionDetail');
        if (!type || !wrap) return;
        if (type.value === 'rusak') {
            wrap.style.display = 'block';
        } else {
            wrap.style.display = 'none';
            if (det) det.value = '';
        }
    }

    // Konfirmasi pengembalian (via custom modal)
    function confirmReturn() {
        const type = document.getElementById('conditionType');
        if (!type || !type.value) {
            showConfirm({
                title: 'Pilih Keadaan Alat',
                message: 'Harap pilih keadaan alat (Baik / Rusak) sebelum memproses pengembalian.',
                icon: '⚠️',
                type: 'warning',
                confirmLabel: 'OK',
                onConfirm: () => {}
            });
            return;
        }
        const isRusak = type.value === 'rusak';
        showConfirm({
            title  : isRusak ? 'Proses Pengembalian — Rusak' : 'Proses Pengembalian — Baik',
            subtitle: 'Alat: {{ $borrowing->equipment->name }}',
            message: isRusak
                ? 'Alat ditandai RUSAK. Alat akan otomatis masuk status Maintenance setelah pengembalian diproses. Lanjutkan?'
                : 'Proses pengembalian alat dalam kondisi baik. Lanjutkan?',
            icon  : isRusak ? '⚠️' : '✅',
            type  : isRusak ? 'warning' : 'success',
            confirmLabel: 'Ya, Proses Kembali',
            onConfirm: () => document.getElementById('returnForm').submit()
        });
    }

    // Tolak dengan cek input dulu
    function submitReject() {
        const input = document.getElementById('rejectReasonInput');
        if (!input || input.value.trim().length < 5) {
            showConfirm({
                title: 'Alasan Terlalu Pendek',
                message: 'Alasan penolakan minimal 5 karakter. Harap isi terlebih dahulu.',
                icon: '⚠️',
                type: 'warning',
                confirmLabel: 'OK',
                onConfirm: () => { if(input) input.focus(); }
            });
            return;
        }
        showConfirm({
            title  : 'Tolak Peminjaman',
            subtitle: 'Alat: {{ $borrowing->equipment->name }}',
            message: 'Anda akan menolak permintaan peminjaman dari {{ $borrowing->user->name }}.\n\nAlasan: "' + input.value + '"',
            icon   : '❌',
            type   : 'danger',
            confirmLabel: 'Ya, Tolak',
            onConfirm: () => document.getElementById('formReject').submit()
        });
    }

    // Resolve issue
    function submitResolve(action) {
        const desc = document.getElementById('resolveDesc');
        if (!desc || desc.value.trim().length < 5) {
            showConfirm({
                title: 'Catatan Diperlukan',
                message: 'Harap isi catatan penanganan (min. 5 karakter) sebelum menyelesaikan masalah.',
                icon: '⚠️',
                type: 'warning',
                confirmLabel: 'OK',
                onConfirm: () => { if(desc) desc.focus(); }
            });
            return;
        }
        const isContinue = action === 'continue';
        showConfirm({
            title  : isContinue ? 'Masalah Selesai — Lanjutkan Pinjam' : 'Selesaikan & Kembalikan Alat',
            subtitle: 'Alat: {{ $borrowing->equipment->name }}',
            message: isContinue
                ? 'Tandai masalah sebagai selesai dan lanjutkan peminjaman? Status akan kembali ke Aktif.'
                : 'Selesaikan peminjaman dan proses pengembalian alat sekarang?',
            icon  : isContinue ? '✅' : '📦',
            type  : isContinue ? 'success' : 'warning',
            confirmLabel: isContinue ? 'Ya, Lanjutkan Pinjam' : 'Ya, Kembalikan',
            onConfirm: () => {
                document.getElementById('resolveActionInput').value = action;
                document.getElementById('formResolve').submit();
            }
        });
    }

    // Report issue mahasiswa
    function submitReportIssue() {
        const desc = document.getElementById('issueDesc');
        if (!desc || desc.value.trim().length < 10) {
            showConfirm({
                title: 'Deskripsi Terlalu Pendek',
                message: 'Harap isi deskripsi masalah (min. 10 karakter).',
                icon: '⚠️',
                type: 'warning',
                confirmLabel: 'OK',
                onConfirm: () => { if(desc) desc.focus(); }
            });
            return;
        }
        showConfirm({
            title  : 'Laporkan Masalah',
            subtitle: 'Alat: {{ $borrowing->equipment->name }}',
            message: 'Laporkan masalah pada alat ini ke Laboran? Masalah akan segera ditangani.',
            icon   : '🔔',
            type   : 'warning',
            confirmLabel: 'Ya, Laporkan',
            onConfirm: () => document.getElementById('formReportIssue').submit()
        });
    }
</script>
@endpush
@endsection
