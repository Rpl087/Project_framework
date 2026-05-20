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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_stock' => 'required|integer|min:0',
            'category' => 'required|in:umum,khusus',
            'status' => 'required|in:good,maintenance',
        ]);

        $validated['available_stock'] = $validated['total_stock'];

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_stock' => 'required|integer|min:0',
            'category' => 'required|in:umum,khusus',
            'status' => 'required|in:good,maintenance',
        ]);

        // Adjust available stock if total_stock changed
        $stockDiff = $validated['total_stock'] - $equipment->total_stock;
        $validated['available_stock'] = max(0, $equipment->available_stock + $stockDiff);

        $equipment->update($validated);

        return redirect()->route('equipments.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    /**
     * Remove the specified equipment.
     */
    public function destroy(Equipment $equipment)
    {
        // Check if equipment has active borrowings
        if ($equipment->borrowings()->whereIn('status', ['pending', 'approved_by_laboran', 'ready_for_pickup', 'active'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus alat yang sedang dipinjam.');
        }

        $equipment->delete();

        return redirect()->route('equipments.index')
            ->with('success', 'Alat berhasil dihapus.');
    }

    /**
     * Display equipment catalog for Mahasiswa (read-only).
     */
    public function catalog()
    {
        $equipments = Equipment::available()->latest()->paginate(12);
        return view('equipments.catalog', compact('equipments'));
    }
}
