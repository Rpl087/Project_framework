<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom start_date dan end_date dari DATE → TIME
     * karena sistem peminjaman berbasis jam dalam satu hari.
     *
     * Catatan SQLite: SQLite menyimpan semua tipe tanggal/waktu
     * sebagai TEXT, sehingga perubahan tipe ini aman tanpa kehilangan data.
     */
    public function up(): void
    {
        // SQLite tidak mendukung ALTER COLUMN secara langsung,
        // jadi kita gunakan pendekatan recreate table.
        // Pada MySQL/PostgreSQL, Schema Builder akan menggunakan ALTER TABLE biasa.

        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('borrowings_new', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
                $table->time('start_date');   // waktu mulai pinjam (HH:MM:SS)
                $table->time('end_date');     // waktu selesai pinjam (HH:MM:SS)
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

            DB::statement('INSERT INTO borrowings_new SELECT * FROM borrowings');

            Schema::drop('borrowings');
            Schema::rename('borrowings_new', 'borrowings');

            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            // MySQL / PostgreSQL
            Schema::table('borrowings', function (Blueprint $table) {
                $table->time('start_date')->change();
                $table->time('end_date')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');

            Schema::create('borrowings_restore', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
                $table->date('start_date');
                $table->date('end_date');
                $table->text('purpose');
                $table->enum('status', [
                    'pending', 'approved_by_laboran', 'approved_by_kepala_lab',
                    'ready_for_pickup', 'active', 'completed', 'rejected', 'overdue', 'issue_reported',
                ])->default('pending');
                $table->text('return_condition')->nullable();
                $table->text('reject_reason')->nullable();
                $table->timestamps();
            });

            DB::statement('INSERT INTO borrowings_restore SELECT * FROM borrowings');
            Schema::drop('borrowings');
            Schema::rename('borrowings_restore', 'borrowings');

            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            Schema::table('borrowings', function (Blueprint $table) {
                $table->date('start_date')->change();
                $table->date('end_date')->change();
            });
        }
    }
};
