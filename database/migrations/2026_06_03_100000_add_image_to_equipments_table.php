<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MINOR-4: Tambahkan kolom image ke tabel equipments untuk upload gambar alat.
     * FITUR-6: Gambar disimpan di public/images/equipments/.
     */
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->string('image')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
