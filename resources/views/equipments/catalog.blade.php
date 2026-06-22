@extends('layouts.mahasiswa')
@section('title', 'Katalog Alat Laboratorium')
@section('hero_sub', 'Temukan alat yang Anda butuhkan dan ajukan peminjaman.')
@section('no_hero')

@section('content')
<div>
    {{-- Page Header --}}
    <div class="u-animate" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.4rem;font-weight:800;color:#0f172a;">Katalog Alat Laboratorium 🧪</h1>
            <p style="color:#64748b;font-size:0.875rem;margin-top:0.2rem;">Pilih alat yang tersedia untuk dipinjam.</p>
        </div>
        @if($equipments->count() > 0)
        <a href="{{ route('borrowings.create') }}" class="u-btn u-btn-primary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Ajukan Peminjaman
        </a>
        @endif
    </div>

    {{-- Search & Filter --}}
    <div class="u-card u-animate u-delay-1" style="margin-bottom:1.5rem;">
        <div class="u-card-body">
            <form method="GET" action="{{ route('catalog') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:flex-end;">
                <div style="flex:1;min-width:200px;">
                    <label class="u-form-label">🔍 Cari Alat</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="u-form-input" placeholder="Nama alat...">
                </div>
                <div style="min-width:180px;">
                    <label class="u-form-label">Kategori</label>
                    <select name="category" class="u-form-input">
                        <option value="">Semua Kategori</option>
                        <option value="umum"   {{ request('category') === 'umum'   ? 'selected' : '' }}>🔵 Alat Umum</option>
                        <option value="khusus" {{ request('category') === 'khusus' ? 'selected' : '' }}>⭐ Alat Khusus</option>
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;">
                    <button type="submit" class="u-btn u-btn-primary u-btn-sm">Filter</button>
                    <a href="{{ route('catalog') }}" class="u-btn u-btn-outline u-btn-sm">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Equipment Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;">
        @forelse($equipments as $index => $eq)
        @php $imgUrl = \App\View\Composers\EquipmentImageComposer::getImageUrl($eq, $imageMap); @endphp

        <div class="u-card u-animate u-delay-{{ min($index + 1, 4) }}" style="transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 40px rgba(99,102,241,0.14)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">

            {{-- Image Area --}}
            <div style="position:relative;height:175px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);overflow:hidden;">
                @if($imgUrl)
                    <img src="{{ $imgUrl }}" alt="{{ $eq->name }}"
                         style="width:100%;height:100%;object-fit:contain;padding:1rem;transition:transform 0.3s;"
                         onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <svg width="60" height="60" fill="none" viewBox="0 0 24 24" stroke="{{ $eq->category === 'khusus' ? '#6366f1' : '#3b82f6' }}" stroke-width="1.2" opacity="0.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                @endif

                {{-- Category Badge --}}
                <div style="position:absolute;top:0.625rem;right:0.625rem;">
                    @if($eq->category === 'khusus')
                        <span class="u-badge u-badge-indigo">⭐ Khusus</span>
                    @else
                        <span class="u-badge u-badge-blue">🔵 Umum</span>
                    @endif
                </div>

                @if($eq->status !== 'good')
                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;">
                        <span class="u-badge u-badge-red" style="font-size:0.8rem;">🔧 Maintenance</span>
                    </div>
                @endif
            </div>

            {{-- Content --}}
            <div style="padding:1rem 1.125rem 1.125rem;">
                <h3 style="font-size:0.975rem;font-weight:700;color:#1e293b;margin-bottom:0.25rem;">{{ $eq->name }}</h3>
                @if($eq->description)
                    <p style="font-size:0.78rem;color:#64748b;margin-bottom:0.75rem;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $eq->description }}</p>
                @endif

                {{-- Category Info --}}
                @if($eq->category === 'khusus')
                    <div style="background:#f5f3ff;border:1px solid #e0e7ff;border-radius:0.5rem;padding:0.4rem 0.625rem;margin-bottom:0.75rem;font-size:0.72rem;color:#6d28d9;">
                        ⚠️ Butuh persetujuan Laboran + Kepala Lab
                    </div>
                @endif

                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:0.75rem;border-top:1px solid #f1f5f9;">
                    <div>
                        <span style="font-size:0.7rem;color:#94a3b8;font-weight:600;text-transform:uppercase;">Stok</span>
                        <p style="font-size:1.2rem;font-weight:900;color:{{ $eq->available_stock > 0 ? '#059669' : '#ef4444' }};">
                            {{ $eq->available_stock }}
                            <span style="font-size:0.75rem;font-weight:500;color:#94a3b8;">/{{ $eq->total_stock }}</span>
                        </p>
                    </div>
                    @if($eq->available_stock > 0 && $eq->status === 'good')
                        <a href="{{ route('borrowings.create', ['equipment' => $eq->id]) }}" class="u-btn u-btn-success u-btn-sm">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Pinjam
                        </a>
                    @else
                        <span class="u-badge u-badge-red">Tidak Tersedia</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:4rem 1rem;">
                <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                </div>
                <p style="color:#64748b;font-size:0.95rem;font-weight:600;">Tidak ada alat yang tersedia saat ini.</p>
                <p style="color:#94a3b8;font-size:0.82rem;margin-top:0.3rem;">Coba ubah filter pencarian.</p>
            </div>
        @endforelse
    </div>

    @if($equipments->hasPages())
    <div style="margin-top:1.5rem;">{{ $equipments->links() }}</div>
    @endif
</div>
@endsection
