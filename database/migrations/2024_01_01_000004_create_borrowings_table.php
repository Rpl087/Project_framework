<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
            // CATATAN (FIX ANEH-1): Kolom start_date dan end_date didefinisikan sebagai
            // DATE di sini, namun diubah menjadi TIME oleh migration:
            //   2026_06_03_000006_change_borrowing_date_columns_to_time.php
            // Alasan: sistem peminjaman berbasis jam dalam sehari (HH:MM), bukan per-tanggal.
            // Jika menjalankan migrate:fresh, kedua migration ini akan berjalan berurutan
            // dan hasilnya BENAR (kolom akhirnya menjadi TIME).
            $table->date('start_date'); // → diubah ke TIME oleh migration v6
            $table->date('end_date');   // → diubah ke TIME oleh migration v6
            $table->text('purpose');
            $table->enum('status', [
                'pending',
                'approved_by_laboran',
                'approved_by_kepala_lab',
                'ready_for_pickup',
                'active',
                'completed',
                'rejected',
                'overdue',
                'issue_reported',
            ])->default('pending');
            $table->text('return_condition')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
