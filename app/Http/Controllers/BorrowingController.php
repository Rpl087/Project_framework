<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingLog;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display borrowings list (filtered by role).
     */
    public function index()
    {
        $user = auth()->user();

        $query = Borrowing::with(['user', 'equipment'])->latest();

        if ($user->isMahasiswa()) {
            $query->where('user_id', $user->id);
        }

        $borrowings = $query->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show borrowing form (Mahasiswa).
     */
    public function create()
    {
        $equipments = Equipment::available()->get();
        return view('borrowings.create', compact('equipments'));
    }

    /**
     * Store a new borrowing request with race condition prevention.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipments,id',
            'start_date' => 'required|date_format:H:i',
            'end_date' => 'required|date_format:H:i|after:start_date',
            'purpose' => 'required|string|min:10',
        ]);

        try {
            $borrowing = DB::transaction(function () use ($validated) {
                // Lock the equipment row to prevent race conditions
                $equipment = Equipment::lockForUpdate()->find($validated['equipment_id']);

                if (!$equipment || $equipment->available_stock <= 0) {
                    throw new \Exception('Stok alat tidak tersedia.');
                }

                if ($equipment->status !== 'good') {
                    throw new \Exception('Alat sedang dalam maintenance.');
                }

                // Decrement available stock
                $equipment->decrement('available_stock');

                // Create borrowing record
                $borrowing = Borrowing::create([
                    'user_id' => auth()->id(),
                    'equipment_id' => $equipment->id,
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date'],
                    'purpose' => $validated['purpose'],
                    'status' => 'pending',
                ]);

                // Create audit log
                BorrowingLog::create([
                    'borrowing_id' => $borrowing->id,
                    'user_id' => auth()->id(),
                    'action_description' => 'Mengajukan peminjaman alat: ' . $equipment->name,
                ]);

                return $borrowing;
            });

            return redirect()->route('borrowings.show', $borrowing)
                ->with('success', 'Pengajuan peminjaman berhasil dikirim.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show borrowing detail.
     */
    public function show(Borrowing $borrowing)
    {
        $user = auth()->user();

        // Mahasiswa can only view their own borrowings
        if ($user->isMahasiswa() && $borrowing->user_id !== $user->id) {
            abort(403);
        }

        $borrowing->load(['user', 'equipment', 'logs.user']);

        return view('borrowings.show', compact('borrowing'));
    }

    /**
     * Laboran approves a pending request.
     * - Alat 'umum' → status: ready_for_pickup
     * - Alat 'khusus' → status: approved_by_laboran (needs Kepala Lab approval)
     */
    public function approveLaboran(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini tidak dalam status menunggu persetujuan.');
        }

        $equipment = $borrowing->equipment;

        if ($equipment->category === 'umum') {
            $borrowing->update(['status' => 'ready_for_pickup']);
            $statusMsg = 'Disetujui dan siap diambil.';
        } else {
            $borrowing->update(['status' => 'approved_by_laboran']);
            $statusMsg = 'Disetujui Laboran, menunggu persetujuan Kepala Lab.';
        }

        BorrowingLog::create([
            'borrowing_id' => $borrowing->id,
            'user_id' => auth()->id(),
            'action_description' => 'Laboran menyetujui peminjaman. ' . $statusMsg,
        ]);

        return back()->with('success', $statusMsg);
    }

    /**
     * Kepala Lab approves request (khusus equipment only).
     */
    public function approveKepalaLab(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'approved_by_laboran') {
            return back()->with('error', 'Peminjaman ini tidak dalam status menunggu persetujuan Kepala Lab.');
        }

        $borrowing->update(['status' => 'ready_for_pickup']);

        BorrowingLog::create([
            'borrowing_id' => $borrowing->id,
            'user_id' => auth()->id(),
            'action_description' => 'Kepala Lab menyetujui peminjaman. Status: siap diambil.',
        ]);

        return back()->with('success', 'Peminjaman disetujui dan siap diambil.');
    }

    /**
     * Reject a borrowing (Laboran or Kepala Lab).
     */
    public function reject(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:5',
        ]);

        if (!in_array($borrowing->status, ['pending', 'approved_by_laboran'])) {
            return back()->with('error', 'Peminjaman ini tidak dapat ditolak.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            // Return stock
            $borrowing->equipment->increment('available_stock');

            $borrowing->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason,
            ]);

            BorrowingLog::create([
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'action_description' => 'Peminjaman ditolak. Alasan: ' . $request->reject_reason,
            ]);
        });

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Laboran hands over equipment to student.
     */
    public function handover(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'ready_for_pickup') {
            return back()->with('error', 'Peminjaman belum siap untuk diserahkan.');
        }

        $borrowing->update(['status' => 'active']);

        BorrowingLog::create([
            'borrowing_id' => $borrowing->id,
            'user_id' => auth()->id(),
            'action_description' => 'Alat diserahkan kepada peminjam. Status: aktif.',
        ]);

        return back()->with('success', 'Alat berhasil diserahkan.');
    }

    /**
     * Laboran processes equipment return.
     */
    public function processReturn(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'return_condition' => 'required|string|min:5',
        ]);

        if ($borrowing->status !== 'active') {
            return back()->with('error', 'Peminjaman ini tidak dalam status aktif.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            // Return stock
            $borrowing->equipment->increment('available_stock');

            $borrowing->update([
                'status' => 'completed',
                'return_condition' => $request->return_condition,
            ]);

            BorrowingLog::create([
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'action_description' => 'Alat dikembalikan. Kondisi: ' . $request->return_condition,
            ]);
        });

        return back()->with('success', 'Pengembalian berhasil diproses.');
    }
}
