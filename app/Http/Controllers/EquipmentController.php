<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of equipment (Laboran).
     */
    public function index()
    {
        $equipments = Equipment::latest()->paginate(10);
        return view('equipments.index', compact('equipments'));
    }

    /**
     * Show the form for creating a new equipment.
     */
    public function create()
    {
        return view('equipments.create');
    }

    /**
     * Store a newly created equipment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_stock' => 'required|integer|min:1',
            'category'    => 'required|in:umum,khusus',
            'status'      => 'required|in:good,maintenance',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['available_stock'] = $validated['total_stock'];

        // FITUR-6: Handle upload gambar ke public/images/equipments/
        if ($request->hasFile('image')) {
            $filename = time() . '_' . preg_replace('/\s+/', '_', $validated['name']) . '.' . $request->image->extension();
            $request->image->move(public_path('images/equipments'), $filename);
            $validated['image'] = $filename;
        }

        Equipment::create($validated);

        return redirect()->route('equipments.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing equipment.
     */
    public function edit(Equipment $equipment)
    {
        return view('equipments.edit', compact('equipment'));
    }

    /**
     * Update the specified equipment.
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_stock' => 'required|integer|min:0',
            'category'    => 'required|in:umum,khusus',
            'status'      => 'required|in:good,maintenance',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Adjust available stock if total_stock changed
        $stockDiff = $validated['total_stock'] - $equipment->total_stock;
        $validated['available_stock'] = max(0, $equipment->available_stock + $stockDiff);

        // FITUR-6: Handle upload gambar baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada dan bukan default
            if ($equipment->image && file_exists(public_path('images/equipments/' . $equipment->image))) {
                unlink(public_path('images/equipments/' . $equipment->image));
            }
            $filename = time() . '_' . preg_replace('/\s+/', '_', $validated['name']) . '.' . $request->image->extension();
            $request->image->move(public_path('images/equipments'), $filename);
            $validated['image'] = $filename;
        }

        $equipment->update($validated);

        return redirect()->route('equipments.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy(Equipment $equipment)
    {
        if ($equipment->borrowings()->whereIn('status', [
            'pending',
            'approved_by_laboran',
            'approved_by_kepala_lab',
            'ready_for_pickup',
            'active',
            'overdue',
            'issue_reported',
        ])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus alat yang sedang dipinjam atau dalam proses persetujuan.');
        }

        // FITUR-6: Hapus file gambar jika ada
        if ($equipment->image && file_exists(public_path('images/equipments/' . $equipment->image))) {
            unlink(public_path('images/equipments/' . $equipment->image));
        }

        $equipment->delete();

        return redirect()->route('equipments.index')
            ->with('success', 'Alat berhasil dihapus.');
    }

    /**
     * Display equipment catalog for Mahasiswa (read-only).
     * PERBAIKAN: Menambahkan filter pencarian dan kategori.
     */
    public function catalog(Request $request)
    {
        $query = Equipment::available()->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $equipments = $query->paginate(12)->withQueryString();
        return view('equipments.catalog', compact('equipments'));
    }
}
