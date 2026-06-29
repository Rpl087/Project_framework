@extends(auth()->user()->isMahasiswa() ? 'layouts.mahasiswa' : 'layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('no_hero', true)

@section('content')

{{-- Page Header --}}
<div class="animate-in" style="margin-bottom:1.5rem;">
    <a href="{{ route('catalog') }}"
       style="display:inline-flex;align-items:center;gap:0.4rem;color:var(--u-txt2,#64748b);font-size:0.82rem;text-decoration:none;margin-bottom:0.75rem;font-weight:500;transition:color 0.18s;"
       onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color=''">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Katalog
    </a>
    <h1 style="font-size:1.5rem;font-weight:800;color:var(--u-txt1,#0f172a);line-height:1.2;">Ajukan Peminjaman Alat</h1>
    <p style="color:var(--u-txt2,#64748b);font-size:0.875rem;margin-top:0.25rem;">Isi form berikut untuk mengajukan peminjaman alat laboratorium.</p>
</div>

{{-- Main Layout: 2 kolom di desktop, 1 kolom di mobile --}}
<div style="display:grid;grid-template-columns:1fr 340px;gap:1.5rem;align-items:start;" id="createFormGrid">

    {{--  Kolom Kiri: Form Utama  --}}
    <div class="glass-card animate-in animate-delay-1" style="padding:0;overflow:hidden;">

        {{-- Card Header --}}
        <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--u-border,#e2e8f0);background:linear-gradient(135deg,rgba(99,102,241,0.06),rgba(139,92,246,0.04));">
            <div style="display:flex;align-items:center;gap:0.625rem;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#4f46e5,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p style="font-size:0.9rem;font-weight:700;color:var(--u-txt1,#0f172a);margin:0;">Form Pengajuan</p>
                    <p style="font-size:0.72rem;color:var(--u-txt2,#64748b);margin:0;">Semua field bertanda * wajib diisi</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('borrowings.store') }}" style="padding:1.5rem;">
            @csrf

            {{-- Pilih Alat --}}
            <div style="margin-bottom:1.5rem;">
                <label class="form-label" style="font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:0.35rem;margin-bottom:0.5rem;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Pilih Alat *
                </label>
                <select name="equipment_id" id="equipmentSelect"
                    style="width:100%;padding:0.7rem 1rem;border:1.5px solid var(--u-border,#e2e8f0);border-radius:0.625rem;font-size:0.875rem;background:#fff;color:var(--u-txt1,#0f172a);transition:all 0.2s;appearance:none;cursor:pointer;background-image:url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E\");background-repeat:no-repeat;background-position:right 0.875rem center;background-size:16px;padding-right:2.5rem;"
                    required
                    onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                    onblur="this.style.borderColor='';this.style.boxShadow=''">
                    <option value="">— Pilih Alat —</option>
                    @foreach($equipments as $eq)
                        <option value="{{ $eq->id }}"
                            {{ (old('equipment_id') == $eq->id || request('equipment') == $eq->id) ? 'selected' : '' }}
                            data-category="{{ $eq->category }}"
                            data-stock="{{ $eq->available_stock }}">
                            {{ $eq->name }} &nbsp;·&nbsp; Stok: {{ $eq->available_stock }} &nbsp;·&nbsp; {{ ucfirst($eq->category) }}
                        </option>
                    @endforeach
                </select>
                @error('equipment_id')
                    <p style="color:#ef4444;font-size:0.75rem;margin-top:0.375rem;display:flex;align-items:center;gap:0.25rem;">
                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </p>
                @enderror

                {{-- Category Badge --}}
                <div id="categoryInfo" style="display:none;margin-top:0.625rem;padding:0.625rem 0.875rem;border-radius:0.5rem;font-size:0.8rem;"></div>
            </div>

            {{-- Grid Waktu --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;" id="timeGrid">

                {{-- Jam Mulai --}}
                <div>
                    <label class="form-label" style="font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:0.35rem;margin-bottom:0.5rem;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Jam Mulai *
                    </label>
                    <div style="position:relative;">
                        <input type="time" name="start_date" id="startTime"
                            value="{{ old('start_date') }}"
                            required min="08:00" max="19:55" step="300"
                            style="width:100%;padding:0.7rem 1rem;padding-left:0.875rem;border:1.5px solid var(--u-border,#e2e8f0);border-radius:0.625rem;font-size:0.9rem;background:#fff;color:var(--u-txt1,#0f172a);transition:all 0.2s;font-weight:600;"
                            onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                            onblur="this.style.borderColor='';this.style.boxShadow=''">
                    </div>
                    <p id="minTimeHint" style="font-size:0.7rem;color:#6366f1;font-weight:600;margin-top:0.3rem;min-height:1rem;"></p>
                    @error('start_date')
                        <p style="color:#ef4444;font-size:0.72rem;margin-top:0.2rem;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jam Selesai --}}
                <div>
                    <label class="form-label" style="font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:0.35rem;margin-bottom:0.5rem;">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Jam Selesai *
                    </label>
                    <div style="position:relative;">
                        <input type="time" name="end_date" id="endTime"
                            value="{{ old('end_date') }}"
                            required min="08:05" max="20:00" step="300"
                            style="width:100%;padding:0.7rem 1rem;border:1.5px solid var(--u-border,#e2e8f0);border-radius:0.625rem;font-size:0.9rem;background:#fff;color:var(--u-txt1,#0f172a);transition:all 0.2s;font-weight:600;"
                            onfocus="this.style.borderColor='#059669';this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.15)'"
                            onblur="this.style.borderColor='';this.style.boxShadow=''">
                    </div>
                    @error('end_date')
                        <p style="color:#ef4444;font-size:0.72rem;margin-top:0.3rem;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Aturan Waktu --}}
            <div style="margin-bottom:1.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.625rem;padding:0.75rem 1rem;display:flex;flex-wrap:wrap;gap:1rem;">
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <span style="color:#6366f1;">🕐</span>
                    <span>Operasional: <strong>08:00 – 20:00</strong></span>
                </div>
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <span>📌</span>
                    <span>Kelipatan <strong>5 menit</strong></span>
                </div>
                <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.72rem;color:#475569;">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Tanggal: <strong>{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</strong></span>
                </div>
            </div>

            {{-- Tujuan Peminjaman --}}
            <div style="margin-bottom:1.5rem;">
                <label class="form-label" style="font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:0.35rem;margin-bottom:0.5rem;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Tujuan Peminjaman *
                </label>
                <textarea name="purpose" id="purposeText"
                    rows="5" required minlength="10" maxlength="1000"
                    placeholder="Jelaskan tujuan peminjaman alat ini secara singkat dan jelas (min. 10 karakter)..."
                    oninput="updatePurposeCounter()"
                    style="width:100%;padding:0.75rem 1rem;border:1.5px solid var(--u-border,#e2e8f0);border-radius:0.625rem;font-size:0.875rem;background:#fff;color:var(--u-txt1,#0f172a);resize:vertical;min-height:120px;transition:all 0.2s;line-height:1.6;font-family:inherit;"
                    onfocus="this.style.borderColor='#6366f1';this.style.boxShadow='0 0 0 3px rgba(99,102,241,0.15)'"
                    onblur="this.style.borderColor='';this.style.boxShadow=''">{{ old('purpose') }}</textarea>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.375rem;">
                    <span style="font-size:0.7rem;color:#94a3b8;">Minimal 10 karakter</span>
                    <span id="purposeCounter" style="font-size:0.72rem;color:#94a3b8;font-variant-numeric:tabular-nums;">0/1000</span>
                </div>
                @error('purpose')
                    <p style="color:#ef4444;font-size:0.75rem;margin-top:0.375rem;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Flash Error --}}
            @if(session('error'))
            <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:0.625rem;padding:0.75rem 1rem;margin-bottom:1.25rem;font-size:0.82rem;color:#991b1b;display:flex;gap:0.5rem;align-items:flex-start;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                {{ session('error') }}
            </div>
            @endif

            {{-- Action Buttons --}}
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;padding-top:0.5rem;border-top:1px solid var(--u-border,#e2e8f0);">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;min-width:160px;">
                    <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Pengajuan
                </button>
                <a href="{{ route('catalog') }}" class="btn btn-outline" style="justify-content:center;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>

    {{--  Kolom Kanan: Sidebar Info  --}}
    <div style="display:flex;flex-direction:column;gap:1rem;" id="createFormSidebar">

        {{-- Info Alur Peminjaman --}}
        <div class="glass-card animate-in animate-delay-2" style="padding:1.25rem;overflow:hidden;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;padding-bottom:0.75rem;border-bottom:1px solid var(--u-border,#e2e8f0);">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#3b82f6,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p style="font-size:0.85rem;font-weight:700;color:var(--u-txt1,#0f172a);margin:0;">Alur Peminjaman</p>
            </div>

            {{-- Alat Umum --}}
            <div style="margin-bottom:1rem;">
                <div style="display:flex;align-items:center;gap:0.4rem;margin-bottom:0.5rem;">
                    <span style="display:inline-flex;align-items:center;padding:0.15rem 0.5rem;background:#dbeafe;color:#1e40af;border-radius:9999px;font-size:0.65rem;font-weight:700;">UMUM</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.35rem;padding-left:0.25rem;">
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#374151;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.6rem;font-weight:700;color:#4f46e5;">1</div>
                        Pengajuan Mahasiswa
                    </div>
                    <div style="width:1px;height:12px;background:#e2e8f0;margin-left:10px;"></div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#374151;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.6rem;font-weight:700;color:#4f46e5;">2</div>
                        Persetujuan Laboran
                    </div>
                    <div style="width:1px;height:12px;background:#e2e8f0;margin-left:10px;"></div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#059669;font-weight:600;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">✅</div>
                        Siap Diambil
                    </div>
                </div>
            </div>

            {{-- Alat Khusus --}}
            <div style="padding-top:1rem;border-top:1px solid var(--u-border,#e2e8f0);">
                <div style="display:flex;align-items:center;gap:0.4rem;margin-bottom:0.5rem;">
                    <span style="display:inline-flex;align-items:center;padding:0.15rem 0.5rem;background:#fef3c7;color:#92400e;border-radius:9999px;font-size:0.65rem;font-weight:700;">KHUSUS</span>
                </div>
                <div style="display:flex;flex-direction:column;gap:0.35rem;padding-left:0.25rem;">
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#374151;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.6rem;font-weight:700;color:#4f46e5;">1</div>
                        Pengajuan Mahasiswa
                    </div>
                    <div style="width:1px;height:12px;background:#e2e8f0;margin-left:10px;"></div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#374151;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.6rem;font-weight:700;color:#4f46e5;">2</div>
                        Persetujuan Laboran
                    </div>
                    <div style="width:1px;height:12px;background:#e2e8f0;margin-left:10px;"></div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#374151;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.6rem;font-weight:700;color:#4f46e5;">3</div>
                        Persetujuan Kepala Lab
                    </div>
                    <div style="width:1px;height:12px;background:#e2e8f0;margin-left:10px;"></div>
                    <div style="display:flex;align-items:center;gap:0.5rem;font-size:0.75rem;color:#059669;font-weight:600;">
                        <div style="width:20px;height:20px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">✅</div>
                        Siap Diambil
                    </div>
                </div>
            </div>
        </div>

        {{-- Aturan Peminjaman --}}
        <div class="glass-card animate-in animate-delay-3" style="padding:1.25rem;">
            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.875rem;">
                <div style="width:28px;height:28px;border-radius:7px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <p style="font-size:0.85rem;font-weight:700;color:var(--u-txt1,#0f172a);margin:0;">Perhatian</p>
            </div>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:0.5rem;">
                <li style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.75rem;color:#475569;">
                    <span style="color:#f59e0b;flex-shrink:0;margin-top:1px;">⚠️</span>
                    Peminjaman hanya berlaku pada <strong>hari yang sama</strong>
                </li>
                <li style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.75rem;color:#475569;">
                    <span style="color:#f59e0b;flex-shrink:0;margin-top:1px;">⚠️</span>
                    Jam operasional <strong>08:00 – 20:00 WIB</strong>
                </li>
                <li style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.75rem;color:#475569;">
                    <span style="color:#f59e0b;flex-shrink:0;margin-top:1px;">⚠️</span>
                    Waktu harus dalam <strong>kelipatan 5 menit</strong>
                </li>
                <li style="display:flex;align-items:flex-start;gap:0.5rem;font-size:0.75rem;color:#475569;">
                    <span style="color:#ef4444;flex-shrink:0;margin-top:1px;">❌</span>
                    Keterlambatan pengembalian akan dicatat
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Responsive CSS --}}
<style>
    @media (max-width: 768px) {
        #createFormGrid {
            grid-template-columns: 1fr !important;
        }
        #createFormSidebar {
            order: -1;
        }
        #timeGrid {
            grid-template-columns: 1fr !important;
        }
    }
    @media (max-width: 900px) and (min-width: 769px) {
        #createFormGrid {
            grid-template-columns: 1fr 280px !important;
        }
    }
</style>

@push('scripts')
<script>
    //  Kategori Info 
    document.getElementById('equipmentSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const info = document.getElementById('categoryInfo');

        if (this.value) {
            const category = selected.dataset.category;
            info.style.display = 'block';
            if (category === 'khusus') {
                info.style.cssText = 'display:block;margin-top:0.625rem;padding:0.625rem 0.875rem;border-radius:0.5rem;font-size:0.8rem;background:#fef3c7;border:1px solid #fde68a;color:#92400e;';
                info.innerHTML = '⚠️ Alat <strong>Khusus</strong> — memerlukan persetujuan Laboran <em>dan</em> Kepala Lab.';
            } else {
                info.style.cssText = 'display:block;margin-top:0.625rem;padding:0.625rem 0.875rem;border-radius:0.5rem;font-size:0.8rem;background:#d1fae5;border:1px solid #a7f3d0;color:#065f46;';
                info.innerHTML = '✅ Alat <strong>Umum</strong> — hanya memerlukan persetujuan Laboran.';
            }
        } else {
            info.style.display = 'none';
        }
    });

    if (document.getElementById('equipmentSelect').value) {
        document.getElementById('equipmentSelect').dispatchEvent(new Event('change'));
    }

    //  Waktu Realtime 
    function roundUpTo5(h, m) {
        const r = m % 5;
        if (r === 0) return { h, m };
        const nm = m + (5 - r);
        return nm >= 60 ? { h: h + 1, m: 0 } : { h, m: nm };
    }
    function padZ(n) { return String(n).padStart(2, '0'); }

    function setMinTime() {
        const now = new Date();
        const { h, m } = roundUpTo5(now.getHours(), now.getMinutes());
        const minTime = padZ(h) + ':' + padZ(m);
        const startInput = document.getElementById('startTime');
        const hint = document.getElementById('minTimeHint');
        const effectiveMin = minTime > '08:00' ? minTime : '08:00';
        startInput.min = effectiveMin;
        hint.textContent = (minTime >= '08:00' && minTime < '20:00')
            ? '⏰ Minimal mulai: ' + effectiveMin + ' WIB'
            : '';
    }

    setMinTime();
    setInterval(setMinTime, 30000);

    document.getElementById('startTime').addEventListener('change', function() {
        if (this.value) {
            const [h, m] = this.value.split(':').map(Number);
            const next = roundUpTo5(h, m + 5);
            document.getElementById('endTime').min = padZ(next.h) + ':' + padZ(next.m);
        }
    });

    //  Counter Tujuan 
    function updatePurposeCounter() {
        const ta = document.getElementById('purposeText');
        const counter = document.getElementById('purposeCounter');
        if (!ta || !counter) return;
        const len = ta.value.length;
        counter.textContent = len + '/1000';
        counter.style.color = len >= 950 ? '#ef4444' : len >= 10 ? '#059669' : '#94a3b8';
    }
    updatePurposeCounter();
</script>
@endpush
@endsection
