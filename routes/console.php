<?php

use App\Console\Commands\MarkOverdueBorrowings;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
 * ─────────────────────────────────────────────────────────────
 *  Jadwal otomatis: tandai peminjaman yang melebihi batas waktu
 *  Berjalan setiap hari pukul 00:01 (WIB, sesuai APP_TIMEZONE)
 *
 *  Untuk menjalankan manual (preview):
 *    php artisan borrowings:mark-overdue --dry-run
 *
 *  Untuk menjalankan manual (eksekusi nyata):
 *    php artisan borrowings:mark-overdue
 *
 *  Untuk mengaktifkan scheduler di server:
 *    * * * * * php /path-to-project/artisan schedule:run >> /dev/null 2>&1
 * ─────────────────────────────────────────────────────────────
 */
Schedule::command(MarkOverdueBorrowings::class)
    ->dailyAt('00:01')
    ->withoutOverlapping()
    ->runInBackground();
