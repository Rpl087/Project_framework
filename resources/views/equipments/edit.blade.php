@extends('layouts.app')
@section('title', 'Edit Alat')

@section('content')
<div style="max-width:640px;">
    <div class="animate-in" style="margin-bottom:1.5rem;">
        <a href="{{ route('equipments.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:#64748b;font-size:0.85rem;text-decoration:none;margin-bottom:0.75rem;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#64748b'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Alat
        </a>
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;">Edit Alat: {{ $equipment->name }}</h1>
    </div>

    <div class="glass-card animate-in animate-delay-1" style="padding:2rem;">
        <form method="POST" action="{{ route('equipments.update', $equipment) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Nama Alat *</label>
                <input type="text" name="name" value="{{ old('name', $equipment->name) }}" class="form-input" required>
                @error('name') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:1.25rem;">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-input" rows="3">{{ old('description', $equipment->description) }}</textarea>
                @error('description') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
                <div>
                    <label class="form-label">Total Stok *</label>
                    <input type="number" name="total_stock" value="{{ old('total_stock', $equipment->total_stock) }}" class="form-input" min="0" required>
                    <p style="font-size:0.7rem;color:#94a3b8;margin-top:0.25rem;">Stok tersedia saat ini: {{ $equipment->available_stock }}</p>
                    @error('total_stock') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Kategori *</label>
                    <select name="category" class="form-input" required>
                        <option value="umum" {{ old('category', $equipment->category) === 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="khusus" {{ old('category', $equipment->category) === 'khusus' ? 'selected' : '' }}>Khusus</option>
                    </select>
                    @error('category') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="form-label">Status *</label>
                <select name="status" class="form-input" required>
                    <option value="good" {{ old('status', $equipment->status) === 'good' ? 'selected' : '' }}>Baik (Good)</option>
                    <option value="maintenance" {{ old('status', $equipment->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div style="margin-bottom:1.5rem;">
                <label class="form-label">Gambar Alat</label>
                @if($equipment->image && file_exists(public_path('images/equipments/' . $equipment->image)))
                    <div style="margin-bottom:0.5rem;">
                        <img src="{{ asset('images/equipments/' . $equipment->image) }}" alt="Gambar saat ini" style="width:80px;height:60px;object-fit:cover;border-radius:0.375rem;border:1px solid var(--border-s);">
                        <p style="font-size:0.7rem;color:var(--txt-3);margin-top:0.25rem;">Gambar saat ini</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-input" accept="image/*" style="padding:0.5rem;">
                <p style="font-size:0.7rem;color:var(--txt-3);margin-top:0.25rem;">Format: JPG, PNG, GIF, WebP. Maks 2MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                @error('image') <p style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</p> @enderror
            </div>

            <div style="display:flex;gap:0.75rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perbarui
                </button>
                <a href="{{ route('equipments.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
