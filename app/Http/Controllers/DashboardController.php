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
            'total_borrowings' => $user->borrowings()->count(),
            'active_borrowings' => $user->borrowings()->where('status', 'active')->count(),
            'pending_borrowings' => $user->borrowings()->where('status', 'pending')->count(),
            'completed_borrowings' => $user->borrowings()->where('status', 'completed')->count(),
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
        $stats = [
            'total_equipment' => Equipment::count(),
            'maintenance_equipment' => Equipment::where('status', 'maintenance')->count(),
            'pending_requests' => Borrowing::where('status', 'pending')->count(),
            'active_borrowings' => Borrowing::where('status', 'active')->count(),
            'ready_for_pickup' => Borrowing::where('status', 'ready_for_pickup')->count(),
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

        return view('dashboard.laboran', compact('stats', 'pendingRequests', 'activeBorrowings'));
    }

    private function kepalaLabDashboard()
    {
        $stats = [
            'pending_approvals' => Borrowing::where('status', 'approved_by_laboran')->count(),
            'total_equipment' => Equipment::count(),
            'active_borrowings' => Borrowing::where('status', 'active')->count(),
            'completed_this_month' => Borrowing::where('status', 'completed')
                ->whereMonth('updated_at', now()->month)
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
