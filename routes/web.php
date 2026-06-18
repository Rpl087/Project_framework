<?php

use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard (role-based redirect)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // ---- Profile (semua role) ----
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // ---- Notifications (semua role) ----
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // ---- Mahasiswa Routes ----
    Route::middleware(['role:mahasiswa', 'verified'])->group(function () {
        Route::get('/catalog', [EquipmentController::class, 'catalog'])->name('catalog');
        Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        // DESAIN-2: Mahasiswa laporkan masalah alat
        Route::post('/borrowings/{borrowing}/report-issue', [BorrowingController::class, 'reportIssue'])->name('borrowings.report-issue');
        // PERBAIKAN: Mahasiswa batalkan pengajuan pending
        Route::post('/borrowings/{borrowing}/cancel', [BorrowingController::class, 'cancel'])->name('borrowings.cancel');
    });

    // ---- Laboran Routes ----
    Route::middleware('role:laboran')->group(function () {
        Route::resource('equipments', EquipmentController::class)->except(['show']);
        Route::post('/borrowings/{borrowing}/approve-laboran', [BorrowingController::class, 'approveLaboran'])->name('borrowings.approve-laboran');
        Route::post('/borrowings/{borrowing}/handover', [BorrowingController::class, 'handover'])->name('borrowings.handover');
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])->name('borrowings.return');
        // DESAIN-2: Laboran selesaikan laporan masalah
        Route::post('/borrowings/{borrowing}/resolve-issue', [BorrowingController::class, 'resolveIssue'])->name('borrowings.resolve-issue');
    });

    // ---- Kepala Lab Routes ----
    Route::middleware('role:kepala_lab')->group(function () {
        Route::post('/borrowings/{borrowing}/approve-kepala-lab', [BorrowingController::class, 'approveKepalaLab'])->name('borrowings.approve-kepala-lab');
    });

    // ---- FITUR-2: Manajemen User (Laboran & Kepala Lab) ----
    Route::middleware('role:laboran|kepala_lab')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        // ---- Shared Reject (Laboran & Kepala Lab) ----
        Route::post('/borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
        // ---- FITUR-4: Export Laporan ----
        Route::get('/borrowings/export/pdf', [BorrowingController::class, 'exportPdf'])->name('borrowings.export-pdf');
        Route::get('/borrowings/export/csv', [BorrowingController::class, 'exportCsv'])->name('borrowings.export-csv');
    });

    // ---- All Authenticated Users ----
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
});

require __DIR__.'/auth.php';
