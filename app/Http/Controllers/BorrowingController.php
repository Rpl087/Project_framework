<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\BorrowingLog;
use App\Models\Equipment;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    /**
     * Display borrowings list (filtered by role).
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Borrowing::with(['user', 'equipment'])->latest();

        if ($user->isMahasiswa()) {
            $query->where('user_id', $user->id);
        }

        // FITUR-3: Filter & search
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', $search)
                                                  ->orWhere('email', 'like', $search))
                  ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', $search));
            });
        }

        $borrowings = $query->paginate(10)->withQueryString();

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
            'start_date'   => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // FIX SEDANG-3: Gunakan >= bukan > agar 20:00 tidak bisa dipilih
                    // sebagai waktu mulai (tidak mungkin menentukan end_date yang valid
                    // karena end_date harus after:start_date dan max 20:00).
                    if ($value < '08:00' || $value >= '20:00') {
                        $fail('Waktu mulai pinjam harus antara pukul 08:00 hingga sebelum 20:00.');
                    }
                },
            ],
            'end_date'     => [
                'required',
                'date_format:H:i',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    if ($value > '20:00') {
                        $fail('Waktu pengembalian tidak boleh melebihi pukul 20:00.');
                    }
                },
            ],
            'purpose'      => 'required|string|min:10',
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

                // FIX RINGAN-1: Stok dikurangi saat pending — bukan bug, ini desain.
                // Tujuan: mencegah double-booking (dua mahasiswa meminjam alat yang sama).
                // Stok dikembalikan jika: (a) permintaan ditolak via reject(), atau
                //                         (b) pengembalian diproses via processReturn().
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
            // FITUR-5: Kirim notifikasi ke mahasiswa
            Notification::send(
                $borrowing->user_id,
                'Peminjaman Disetujui ✅',
                "Peminjaman {$equipment->name} Anda telah disetujui dan siap diambil.",
                'success',
                route('borrowings.show', $borrowing->id)
            );
        } else {
            $borrowing->update(['status' => 'approved_by_laboran']);
            $statusMsg = 'Disetujui Laboran, menunggu persetujuan Kepala Lab.';
            Notification::send(
                $borrowing->user_id,
                'Peminjaman Disetujui Laboran 🔄',
                "Peminjaman {$equipment->name} Anda disetujui Laboran dan sedang menunggu persetujuan Kepala Lab.",
                'info',
                route('borrowings.show', $borrowing->id)
            );
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
     * Status menjadi approved_by_kepala_lab — Laboran masih perlu melakukan serah terima.
     */
    public function approveKepalaLab(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'approved_by_laboran') {
            return back()->with('error', 'Peminjaman ini tidak dalam status menunggu persetujuan Kepala Lab.');
        }

        $borrowing->update(['status' => 'approved_by_kepala_lab']);

        BorrowingLog::create([
            'borrowing_id'      => $borrowing->id,
            'user_id'           => auth()->id(),
            'action_description'=> 'Kepala Lab menyetujui peminjaman. Menunggu serah terima oleh Laboran.',
        ]);

        // FITUR-5: Notifikasi ke mahasiswa
        Notification::send(
            $borrowing->user_id,
            'Peminjaman Disetujui Kepala Lab ✅',
            "Peminjaman {$borrowing->equipment->name} Anda telah disetujui penuh. Silakan ambil alat ke Laboran.",
            'success',
            route('borrowings.show', $borrowing->id)
        );

        return back()->with('success', 'Peminjaman disetujui. Laboran dapat melakukan serah terima.');
    }

    /**
     * Reject a borrowing (Laboran or Kepala Lab).
     */
    public function reject(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'reject_reason' => 'required|string|min:5',
        ]);

        // BUG-1 FIX: Validasi role di backend — hanya Laboran yang bisa tolak 'pending',
        // Kepala Lab bisa tolak semua status yang masih bisa ditolak.
        $user = auth()->user();
        $allowedStatuses = $user->isLaboran()
            ? ['pending']
            : ['pending', 'approved_by_laboran', 'approved_by_kepala_lab'];

        if (!in_array($borrowing->status, $allowedStatuses)) {
            return back()->with('error', 'Anda tidak berwenang menolak peminjaman dengan status ini.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            // BUG-2 FIX: Gunakan min() agar available_stock tidak melebihi total_stock
            $equipment = $borrowing->equipment;
            $equipment->update([
                'available_stock' => min($equipment->available_stock + 1, $equipment->total_stock),
            ]);

            $borrowing->update([
                'status' => 'rejected',
                'reject_reason' => $request->reject_reason,
            ]);

            BorrowingLog::create([
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'action_description' => 'Peminjaman ditolak. Alasan: ' . $request->reject_reason,
            ]);

            // FITUR-5: Notifikasi ke mahasiswa
            Notification::send(
                $borrowing->user_id,
                'Peminjaman Ditolak ❌',
                "Peminjaman {$equipment->name} Anda ditolak. Alasan: {$request->reject_reason}",
                'danger',
                route('borrowings.show', $borrowing->id)
            );
        });

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Laboran hands over equipment to student.
     * Menerima status ready_for_pickup (alat umum) dan approved_by_kepala_lab (alat khusus).
     */
    public function handover(Borrowing $borrowing)
    {
        $readyStatuses = ['ready_for_pickup', 'approved_by_kepala_lab'];

        if (!in_array($borrowing->status, $readyStatuses)) {
            return back()->with('error', 'Peminjaman belum siap untuk diserahkan.');
        }

        $borrowing->update(['status' => 'active']);

        BorrowingLog::create([
            'borrowing_id'       => $borrowing->id,
            'user_id'            => auth()->id(),
            'action_description' => 'Alat diserahkan kepada peminjam. Status: aktif.',
        ]);

        // Notifikasi ke mahasiswa: alat sudah diserahterimakan
        Notification::send(
            $borrowing->user_id,
            'Alat Sudah Diserahkan 🎉',
            "Alat {$borrowing->equipment->name} telah diserahkan kepada Anda. Jaga dengan baik dan kembalikan sebelum {$borrowing->end_date}.",
            'success',
            route('borrowings.show', $borrowing->id)
        );

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

        // DESAIN-3 FIX: Izinkan peminjaman 'overdue' untuk diproses pengembaliannya
        if (!in_array($borrowing->status, ['active', 'overdue'])) {
            return back()->with('error', 'Peminjaman ini tidak dalam status aktif atau terlambat.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            // BUG-2 FIX: Gunakan min() agar available_stock tidak melebihi total_stock
            $equipment = $borrowing->equipment;
            $equipment->update([
                'available_stock' => min($equipment->available_stock + 1, $equipment->total_stock),
            ]);

            $wasOverdue = $borrowing->status === 'overdue';

            $borrowing->update([
                'status' => 'completed',
                'return_condition' => $request->return_condition,
            ]);

            $note = $wasOverdue ? ' [Pengembalian terlambat]' : '';
            BorrowingLog::create([
                'borrowing_id' => $borrowing->id,
                'user_id' => auth()->id(),
                'action_description' => 'Alat dikembalikan' . $note . '. Kondisi: ' . $request->return_condition,
            ]);

            // Notifikasi ke mahasiswa: peminjaman selesai
            $msg = $wasOverdue
                ? "Peminjaman {$borrowing->equipment->name} Anda telah selesai (terlambat). Kondisi: {$request->return_condition}."
                : "Peminjaman {$borrowing->equipment->name} Anda telah selesai. Terima kasih telah mengembalikan tepat waktu!";
            Notification::send(
                $borrowing->user_id,
                $wasOverdue ? 'Peminjaman Selesai (Terlambat) ⚠️' : 'Peminjaman Selesai ✅',
                $msg,
                $wasOverdue ? 'warning' : 'success',
                route('borrowings.show', $borrowing->id)
            );
        });

        return back()->with('success', 'Pengembalian berhasil diproses.');
    }

    /**
     * Mahasiswa membatalkan pengajuan peminjaman yang masih pending.
     * PERBAIKAN: Fitur baru yang sebelumnya tidak ada.
     */
    public function cancel(Borrowing $borrowing)
    {
        $user = auth()->user();

        // Hanya pemilik peminjaman yang boleh membatalkan
        if ($borrowing->user_id !== $user->id) {
            abort(403);
        }

        // Hanya bisa dibatalkan saat masih pending
        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Peminjaman hanya bisa dibatalkan saat masih menunggu persetujuan.');
        }

        DB::transaction(function () use ($borrowing, $user) {
            // Kembalikan stok alat
            $equipment = $borrowing->equipment;
            $equipment->update([
                'available_stock' => min($equipment->available_stock + 1, $equipment->total_stock),
            ]);

            $borrowing->update(['status' => 'rejected', 'reject_reason' => 'Dibatalkan oleh peminjam.']);

            BorrowingLog::create([
                'borrowing_id'       => $borrowing->id,
                'user_id'            => $user->id,
                'action_description' => 'Peminjaman dibatalkan oleh mahasiswa.',
            ]);
        });

        return redirect()->route('borrowings.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }

    /**
     * Mahasiswa melaporkan masalah pada alat yang sedang dipinjam.
     * DESAIN-2: Implementasi fitur issue_reported.
     */
    public function reportIssue(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'issue_description' => 'required|string|min:10',
        ]);

        $user = auth()->user();

        // Hanya peminjam yang boleh melaporkan masalah
        if ($borrowing->user_id !== $user->id) {
            abort(403);
        }

        if ($borrowing->status !== 'active') {
            return back()->with('error', 'Masalah hanya bisa dilaporkan saat peminjaman aktif.');
        }

        $borrowing->update(['status' => 'issue_reported']);

        BorrowingLog::create([
            'borrowing_id' => $borrowing->id,
            'user_id'      => $user->id,
            'action_description' => 'Peminjam melaporkan masalah: ' . $request->issue_description,
        ]);

        return back()->with('success', 'Laporan masalah berhasil dikirim. Laboran akan segera menangani.');
    }

    /**
     * Laboran menyelesaikan laporan masalah (issue_reported → active atau completed).
     */
    public function resolveIssue(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'resolve_description' => 'required|string|min:5',
            'resolve_action'      => 'required|in:continue,complete',
        ]);

        if ($borrowing->status !== 'issue_reported') {
            return back()->with('error', 'Peminjaman ini tidak dalam status laporan masalah.');
        }

        DB::transaction(function () use ($borrowing, $request) {
            if ($request->resolve_action === 'complete') {
                // Selesaikan peminjaman & kembalikan stok
                $equipment = $borrowing->equipment;
                $equipment->update([
                    'available_stock' => min($equipment->available_stock + 1, $equipment->total_stock),
                ]);
                $borrowing->update([
                    'status'           => 'completed',
                    'return_condition' => $request->resolve_description,
                ]);
                $action = 'Masalah diselesaikan dan alat dikembalikan. Catatan: ' . $request->resolve_description;
            } else {
                // Lanjutkan peminjaman (masalah sudah ditangani)
                $borrowing->update(['status' => 'active']);
                $action = 'Masalah ditangani, peminjaman dilanjutkan. Catatan: ' . $request->resolve_description;
            }

            BorrowingLog::create([
                'borrowing_id'       => $borrowing->id,
                'user_id'            => auth()->id(),
                'action_description' => $action,
            ]);

            // Notifikasi ke mahasiswa: masalah diselesaikan
            if ($request->resolve_action === 'complete') {
                Notification::send(
                    $borrowing->user_id,
                    'Masalah Selesai & Peminjaman Ditutup ✅',
                    "Laporan masalah pada {$borrowing->equipment->name} telah diselesaikan dan peminjaman ditutup. Catatan: {$request->resolve_description}",
                    'success',
                    route('borrowings.show', $borrowing->id)
                );
            } else {
                Notification::send(
                    $borrowing->user_id,
                    'Masalah Ditangani, Peminjaman Dilanjutkan ✅',
                    "Laporan masalah pada {$borrowing->equipment->name} telah ditangani. Peminjaman Anda dilanjutkan. Catatan: {$request->resolve_description}",
                    'info',
                    route('borrowings.show', $borrowing->id)
                );
            }
        });

        return back()->with('success', 'Laporan masalah berhasil ditangani.');
    }

    /**
     * FITUR-4: Export laporan peminjaman ke PDF menggunakan dompdf.
     * Accessible by Laboran & Kepala Lab.
     */
    public function exportPdf(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', $search))
                  ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', $search));
            });
        }

        $borrowings = $query->get();

        $filterParts = [];
        if ($request->filled('status'))  $filterParts[] = 'Status: ' . $request->status;
        if ($request->filled('search'))  $filterParts[] = 'Pencarian: ' . $request->search;
        $filterInfo = $filterParts ? implode(', ', $filterParts) : null;

        $pdf = Pdf::loadView('borrowings.report-pdf', compact('borrowings', 'filterInfo'))
                  ->setPaper('a4', 'landscape');

        $filename = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * FITUR-4: Export laporan peminjaman ke CSV (tanpa package tambahan).
     */
    public function exportCsv(Request $request)
    {
        $query = Borrowing::with(['user', 'equipment'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', $search))
                  ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', $search));
            });
        }

        $borrowings = $query->get();
        $filename   = 'laporan-peminjaman-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($borrowings) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['No', 'Peminjam', 'Email', 'Alat', 'Kategori', 'Waktu Pinjam', 'Waktu Kembali', 'Status', 'Alasan Tolak', 'Tanggal Ajuan']);

            foreach ($borrowings as $i => $b) {
                fputcsv($file, [
                    $i + 1,
                    $b->user->name,
                    $b->user->email,
                    $b->equipment->name,
                    ucfirst($b->equipment->category),
                    $b->start_date,
                    $b->end_date,
                    $b->status_label,
                    $b->reject_reason ?? '',
                    $b->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
