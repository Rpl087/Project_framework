@extends(auth()->user()->isMahasiswa() ? 'layouts.mahasiswa' : 'layouts.app')
@section('title', 'Ajukan Peminjaman')

@section('content')
<div style="max-width:640px;">
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <a href="{{ route('catalog') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:#64748b;font-size:0.85rem;text-decoration:none;margin-bottom:0.75rem;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#64748b'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Katalog
        </a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Ajukan Peminjaman Alat</h1>
        <p style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Isi form berikut untuk mengajukan peminjaman.</p>
    </div>

    <div class="glass-card animate-in animate-delay-1" style="padding:2rem;">
        <form method="POST" action="{{ route('borrowings.store') }}">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Pilih Alat *</label>
                <select name="equipment_id" class="form-input" required id="equipmentSelect">
                    <option value="">-- Pilih Alat --</option>
                    @foreach($equipments as $eq)
                        <option value="{{ $eq->id }}" {{ (old('equipment_id') == $eq->id || request('equipment') == $eq->id) ? 'selected' : '' }}
                            data-category="{{ $eq->category }}"
                            data-stock="{{ $eq->available_stock }}">
                            {{ $eq->name }} (Stok: {{ $eq->available_stock }}) — {{ ucfirst($eq->category) }}
                        </option>
                    @endforeach
                </select>
                @error('equipment_id') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror

                <!-- Category info box -->
                <div id="categoryInfo" style="display:none;margin-top:0.75rem;padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.8rem;"></div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:1.25rem;">
                <div>
                    <label class="form-label">Waktu Mulai Pinjam *</label>
                    <input type="time" name="start_date" value="{{ old('start_date') }}" class="form-input"
                        required min="08:00" max="19:55" step="300" id="startTime">
                    <div style="font-size:0.72rem;color:#64748b;margin-top:0.3rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.4rem;padding:0.4rem 0.6rem;">
                        🕐 Jam operasional: <strong>08:00 – 19:55</strong><br>
                        ⚠️ Waktu harus <strong>kelipatan 5 menit</strong> (08:00, 08:05, 08:30, …)<br>
                        <span id="minTimeHint" style="color:#6366f1;font-weight:600;"></span>
                    </div>
                    @error('start_date') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Waktu Pengembalian *</label>
                    <input type="time" name="end_date" value="{{ old('end_date') }}" class="form-input"
                        required min="08:05" max="20:00" step="300" id="endTime">
                    <div style="font-size:0.72rem;color:#64748b;margin-top:0.3rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:0.4rem;padding:0.4rem 0.6rem;">
                        ⏰ Maksimal pukul <strong>20:00</strong><br>
                        ⚠️ Waktu harus <strong>kelipatan 5 menit</strong> (09:00, 12:30, 14:15, …)
                    </div>
                    @error('end_date') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="form-label">Tujuan Peminjaman *</label>
                <textarea name="purpose" class="form-input" rows="4" required
                    placeholder="Jelaskan tujuan peminjaman alat ini (min. 10 karakter)..."
                    minlength="10" maxlength="1000" id="purposeText"
                    oninput="updatePurposeCounter()">{{ old('purpose') }}</textarea>
                <div style="display:flex;justify-content:flex-end;margin-top:0.25rem;">
                    <span id="purposeCounter" style="font-size:0.72rem;color:#94a3b8;">0/1000</span>
                </div>
                @error('purpose') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <!-- Info Box -->
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:0.75rem;padding:1rem;margin-bottom:1.5rem;">
                <div style="display:flex;gap:0.5rem;align-items:flex-start;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#3b82f6" stroke-width="2" style="flex-shrink:0;margin-top:2px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div style="font-size:0.8rem;color:#1e40af;">
                        <p style="font-weight:600;margin-bottom:0.25rem;">Informasi Alur Peminjaman:</p>
                        <ul style="padding-left:1rem;list-style-type:disc;">
                            <li>Alat <strong>Umum</strong>: Persetujuan Laboran → Siap diambil</li>
                            <li>Alat <strong>Khusus</strong>: Persetujuan Laboran → Persetujuan Kepala Lab → Siap diambil</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Kirim Pengajuan
                </button>
                <a href="{{ route('catalog') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ── Kategori Info ──
    document.getElementById('equipmentSelect').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const info = document.getElementById('categoryInfo');

        if (this.value) {
            const category = selected.dataset.category;
            info.style.display = 'block';
            if (category === 'khusus') {
                info.style.background = '#fef3c7';
                info.style.border = '1px solid #fde68a';
                info.style.color = '#92400e';
                info.innerHTML = '⚠️ Alat <strong>Khusus</strong> — memerlukan persetujuan Laboran <em>dan</em> Kepala Lab.';
            } else {
                info.style.background = '#d1fae5';
                info.style.border = '1px solid #a7f3d0';
                info.style.color = '#065f46';
                info.innerHTML = '✅ Alat <strong>Umum</strong> — hanya memerlukan persetujuan Laboran.';
            }
        } else {
            info.style.display = 'none';
        }
    });

    if (document.getElementById('equipmentSelect').value) {
        document.getElementById('equipmentSelect').dispatchEvent(new Event('change'));
    }

    // ── Fix Waktu Realtime (kelipatan 5 menit berikutnya) ──
    function roundUpTo5(h, m) {
        const remainder = m % 5;
        if (remainder === 0) return { h, m };
        const newM = m + (5 - remainder);
        if (newM >= 60) return { h: h + 1, m: 0 };
        return { h, m: newM };
    }

    function padZ(n) { return String(n).padStart(2, '0'); }

    function setMinTime() {
        const now = new Date();
        const { h, m } = roundUpTo5(now.getHours(), now.getMinutes());
        const minTime = padZ(h) + ':' + padZ(m);

        const startInput = document.getElementById('startTime');
        const hint = document.getElementById('minTimeHint');

        // Hanya batasi dari jam 08:00
        const effectiveMin = minTime > '08:00' ? minTime : '08:00';
        startInput.min = effectiveMin;

        if (minTime >= '08:00' && minTime < '20:00') {
            hint.textContent = '⏰ Minimal mulai: ' + effectiveMin + ' WIB (waktu sekarang)';
        } else {
            hint.textContent = '';
        }
    }

    // Update min waktu mulai setiap 30 detik
    setMinTime();
    setInterval(setMinTime, 30000);

    // Saat start_date berubah, update min end_date
    document.getElementById('startTime').addEventListener('change', function() {
        const val = this.value;
        const endInput = document.getElementById('endTime');
        if (val) {
            const [h, m] = val.split(':').map(Number);
            const next = roundUpTo5(h, m + 5);
            endInput.min = padZ(next.h) + ':' + padZ(next.m);
        }
    });

    // ── Counter Tujuan Peminjaman ──
    function updatePurposeCounter() {
        const ta = document.getElementById('purposeText');
        const counter = document.getElementById('purposeCounter');
        if (!ta || !counter) return;
        const len = ta.value.length;
        counter.textContent = len + '/1000';
        counter.style.color = len >= 950 ? '#ef4444' : '#94a3b8';
    }

    // Init counter on load
    updatePurposeCounter();
</script>
@endpush
@endsection
