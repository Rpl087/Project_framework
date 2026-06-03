@extends('layouts.app')
@section('title', 'Kelola Alat Laboratorium')

@section('content')
<div>
    <div class="animate-in" style="display:flex;flex-wrap:wrap;gap:1rem;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
        <div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Kelola Alat Laboratorium 🔧</h1>
            <p style="color:#64748b;font-size:0.9rem;margin-top:0.25rem;">Tambah, edit, dan kelola stok alat lab.</p>
        </div>
        <a href="{{ route('equipments.create') }}" class="btn btn-primary">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Alat
        </a>
    </div>


    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.25rem;">
        @forelse($equipments as $index => $eq)
        @php $imgFile = $imageMap[$eq->name] ?? null; @endphp
        <div class="stat-card animate-in animate-delay-{{ min($index + 1, 4) }}" style="padding:0;overflow:hidden;">

            {{-- Product Image --}}
            <div style="position:relative;height:180px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);overflow:hidden;">
                @if($imgFile && file_exists(public_path('images/equipments/' . $imgFile)))
                    <img src="{{ asset('images/equipments/' . $imgFile) }}"
                         alt="{{ $eq->name }}"
                         style="width:100%;height:100%;object-fit:contain;padding:1rem;transition:transform 0.3s ease;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'">
                @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                        <svg width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="{{ $eq->category === 'khusus' ? '#4f46e5' : '#2563eb' }}" stroke-width="1.5" opacity="0.3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                @endif

                {{-- Category Badge --}}
                <div style="position:absolute;top:0.75rem;right:0.75rem;">
                    @if($eq->category === 'khusus')
                        <span class="badge badge-indigo">Khusus</span>
                    @else
                        <span class="badge badge-blue">Umum</span>
                    @endif
                </div>

                {{-- Status Badge (top-left) --}}
                <div style="position:absolute;top:0.75rem;left:0.75rem;">
                    @if($eq->status === 'good')
                        <span class="badge badge-emerald">Baik</span>
                    @else
                        <span class="badge badge-amber">Maintenance</span>
                    @endif
                </div>
            </div>

            {{-- Card Content --}}
            <div style="padding:1rem;">
                <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:0.25rem;">{{ $eq->name }}</h3>
                @if($eq->description)
                    <p style="font-size:0.8rem;color:#64748b;margin-bottom:0.75rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $eq->description }}</p>
                @endif

                <div style="display:flex;align-items:center;justify-content:space-between;padding-top:0.75rem;border-top:1px solid #f1f5f9;">
                    <div>
                        <span style="font-size:0.75rem;color:#94a3b8;">Stok Tersedia</span>
                        <p style="font-size:1.25rem;font-weight:800;color:{{ $eq->available_stock > 0 ? '#059669' : '#ef4444' }};">
                            {{ $eq->available_stock }}<span style="font-size:0.8rem;font-weight:500;color:#94a3b8;">/{{ $eq->total_stock }}</span>
                        </p>
                    </div>
                    <div style="display:flex;gap:0.4rem;">
                        <a href="{{ route('equipments.edit', $eq) }}" class="btn btn-outline btn-sm">Edit</a>
                        <form method="POST" action="{{ route('equipments.destroy', $eq) }}" onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;padding:3rem;">
            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="margin:0 auto 1rem;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p style="color:#94a3b8;font-size:0.9rem;">Belum ada data alat.</p>
            <a href="{{ route('equipments.create') }}" class="btn btn-primary" style="margin-top:1rem;">Tambah Alat Pertama</a>
        </div>
        @endforelse
    </div>

    @if($equipments->hasPages())
    <div style="margin-top:1.5rem;">
        {{ $equipments->links() }}
    </div>
    @endif
</div>
@endsection
