<?php

namespace App\Console\Commands;

use App\Models\Borrowing;
use App\Models\BorrowingLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MarkOverdueBorrowings extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'borrowings:mark-overdue
                            {--dry-run : Tampilkan daftar yang akan diubah tanpa benar-benar mengubah}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Tandai peminjaman aktif yang waktu end_date-nya sudah lewat sebagai overdue';

    /**
     * Jalankan command.
     *
     * Karena sistem menggunakan waktu (HH:MM) dalam satu hari penuh,
     * sebuah peminjaman dianggap OVERDUE jika:
     *  1. Dibuat pada hari yang BERBEDA dari hari ini (belum dikembalikan hari itu), ATAU
     *  2. Dibuat HARI INI dan jam sekarang sudah melewati end_date (HH:MM).
     *
     * Dijadwalkan setiap hari via routes/console.php.
     */
    public function handle(): int
    {
        $today       = now()->toDateString();
        $currentTime = now()->format('H:i');

        // Peminjaman aktif yang overdue:
        // Menggunakan updated_at sebagai proxy "kapan alat diserahterimakan" (handover),
        // karena saat status berubah menjadi 'active', updated_at diperbarui.
        // Ini lebih akurat daripada created_at (waktu pengajuan) yang bisa dari hari sebelumnya.
        //
        // Kondisi overdue:
        // - updated_at (waktu aktif) sebelum hari ini → pasti overdue (tidak dikembalikan kemarin)
        // - ATAU updated_at hari ini && jam sekarang sudah lewat end_date
        $overdueBorrowings = Borrowing::with(['equipment', 'user'])
            ->where('status', 'active')
            ->where(function ($query) use ($today, $currentTime) {
                $query
                    // Kasus 1: menjadi aktif sebelum hari ini — pasti overdue
                    ->whereDate('updated_at', '<', $today)
                    // Kasus 2: menjadi aktif hari ini tapi jam pengembalian sudah lewat
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->whereDate('updated_at', $today)
                          ->where('end_date', '<', $currentTime);
                    });
            })
            ->get();

        if ($overdueBorrowings->isEmpty()) {
            $this->info('Tidak ada peminjaman yang perlu ditandai overdue.');
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$overdueBorrowings->count()} peminjaman yang akan ditandai overdue:");

        foreach ($overdueBorrowings as $borrowing) {
            $this->line("  - #{$borrowing->id} | {$borrowing->equipment->name} | {$borrowing->user->name} | Batas: {$borrowing->end_date} | Dibuat: {$borrowing->created_at->format('Y-m-d')}");
        }

        // Mode dry-run: hanya tampilkan tanpa mengubah
        if ($this->option('dry-run')) {
            $this->warn('[DRY RUN] Tidak ada perubahan yang dilakukan.');
            return self::SUCCESS;
        }

        $count = 0;

        DB::transaction(function () use ($overdueBorrowings, &$count) {
            foreach ($overdueBorrowings as $borrowing) {
                $borrowing->update(['status' => 'overdue']);

                BorrowingLog::create([
                    'borrowing_id'       => $borrowing->id,
                    'user_id'            => $borrowing->user_id,
                    'action_description' => 'Peminjaman otomatis ditandai terlambat (overdue). Batas pengembalian: ' . $borrowing->end_date . ' pada ' . $borrowing->created_at->format('d M Y') . '.',
                ]);

                // Notifikasi ke mahasiswa: peminjaman melewati batas waktu
                \App\Models\Notification::send(
                    $borrowing->user_id,
                    'Peminjaman Terlambat! ⚠️',
                    "Peminjaman {$borrowing->equipment->name} Anda telah melewati batas waktu pengembalian ({$borrowing->end_date}). Segera kembalikan ke Laboran.",
                    'warning',
                    route('borrowings.show', $borrowing->id)
                );

                $count++;
            }
        });

        $this->info("Berhasil menandai {$count} peminjaman sebagai overdue.");
        return self::SUCCESS;
    }
}
