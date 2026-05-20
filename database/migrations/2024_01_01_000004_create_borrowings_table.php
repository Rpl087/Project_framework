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
            $table->date('start_date');
            $table->date('end_date');
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
