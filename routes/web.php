<?php

use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
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

    // ---- Mahasiswa Routes ----
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/catalog', [EquipmentController::class, 'catalog'])->name('catalog');
        Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    });

    // ---- Laboran Routes ----
    Route::middleware('role:laboran')->group(function () {
        Route::resource('equipments', EquipmentController::class)->except(['show']);
        Route::post('/borrowings/{borrowing}/approve-laboran', [BorrowingController::class, 'approveLaboran'])->name('borrowings.approve-laboran');
        Route::post('/borrowings/{borrowing}/handover', [BorrowingController::class, 'handover'])->name('borrowings.handover');
        Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'processReturn'])->name('borrowings.return');
    });

    // ---- Kepala Lab Routes ----
    Route::middleware('role:kepala_lab')->group(function () {
        Route::post('/borrowings/{borrowing}/approve-kepala-lab', [BorrowingController::class, 'approveKepalaLab'])->name('borrowings.approve-kepala-lab');
    });

    // ---- Shared Routes (Laboran & Kepala Lab can reject) ----
    Route::middleware('role:laboran|kepala_lab')->group(function () {
        Route::post('/borrowings/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
    });

    // ---- All Authenticated Users ----
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::get('/borrowings/{borrowing}', [BorrowingController::class, 'show'])->name('borrowings.show');
});

require __DIR__.'/auth.php';
