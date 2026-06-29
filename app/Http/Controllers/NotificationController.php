<?php

namespace App\Http\Controllers;


class NotificationController extends Controller
{
    /**
     * Tampilkan semua notifikasi untuk user yang login.
     */
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        // Tandai hanya notifikasi di halaman ini sebagai sudah dibaca,
        // bukan SEMUA notifikasi. Notifikasi di halaman 2+ tidak langsung
        // terbaca sebelum user sempat melihatnya.
        $ids = $notifications->pluck('id');
        auth()->user()->notifications()->whereIn('id', $ids)->whereNull('read_at')->update(['read_at' => now()]);

        // Gunakan view berbeda untuk admin (laboran/kepala_lab) vs mahasiswa
        $view = auth()->user()->isMahasiswa()
            ? 'notifications.index'
            : 'notifications.admin';

        return view($view, compact('notifications'));
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca.
     */
    public function markAllRead()
    {
        auth()->user()->notifications()->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}