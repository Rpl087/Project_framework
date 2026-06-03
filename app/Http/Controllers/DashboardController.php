<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Equipment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isMahasiswa()) {
            return $this->mahasiswaDashboard();
        } elseif ($user->isLaboran()) {
            return $this->laboranDashboard();
        } elseif ($user->isKepalaLab()) {
            return $this->kepalaLabDashboard();
        }

        abort(403);
    }

    private function mahasiswaDashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_borrowings'    => $user->borrowings()->count(),
            'active_borrowings'   => $user->borrowings()->where('status', 'active')->count(),
            // FIX SEDANG-2: Sertakan semua status 'dalam proses' agar stat informatif.
            // Sebelumnya hanya menghitung 'pending', melewatkan alat yang sedang
            // dalam review Laboran/Kepala Lab.
            'pending_borrowings'  => $user->borrowings()
                ->whereIn('status', ['pending', 'approved_by_laboran', 'approved_by_kepala_lab'])
                ->count(),
            'completed_borrowings'=> $user->borrowings()->where('status', 'completed')->count(),
        ];

        $recentBorrowings = $user->borrowings()
            ->with('equipment')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.mahasiswa', compact('stats', 'recentBorrowings'));
    }

    private function laboranDashboard()
    {
        // Status yang artinya "siap diserahterimakan" oleh Laboran:
        // - ready_for_pickup   : alat umum (sudah disetujui Laboran)
        // - approved_by_kepala_lab : alat khusus (sudah disetujui Kepala Lab)
        $handoverStatuses = ['ready_for_pickup', 'approved_by_kepala_lab'];

        $stats = [
            'total_equipment'      => Equipment::count(),
            'maintenance_equipment'=> Equipment::where('status', 'maintenance')->count(),
            'pending_requests'     => Borrowing::where('status', 'pending')->count(),
            'active_borrowings'    => Borrowing::where('status', 'active')->count(),
            'ready_for_pickup'     => Borrowing::whereIn('status', $handoverStatuses)->count(),
        ];

        $pendingRequests = Borrowing::with(['user', 'equipment'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $activeBorrowings = Borrowing::with(['user', 'equipment'])
            ->where('status', 'active')
            ->latest()
            ->take(10)
            ->get();

        // Alat yang sudah siap diserahterimakan (umum & khusus)
        $readyForHandover = Borrowing::with(['user', 'equipment'])
            ->whereIn('status', $handoverStatuses)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.laboran', compact(
            'stats',
            'pendingRequests',
            'activeBorrowings',
            'readyForHandover'
        ));
    }

    private function kepalaLabDashboard()
    {
        $stats = [
            'pending_approvals'   => Borrowing::where('status', 'approved_by_laboran')->count(),
            'approved_count'      => Borrowing::where('status', 'approved_by_kepala_lab')->count(),
            'total_equipment'     => Equipment::count(),
            'active_borrowings'   => Borrowing::where('status', 'active')->count(),
            'completed_this_month'=> Borrowing::where('status', 'completed')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];

        $pendingApprovals = Borrowing::with(['user', 'equipment'])
            ->where('status', 'approved_by_laboran')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.kepala-lab', compact('stats', 'pendingApprovals'));
    }
}
