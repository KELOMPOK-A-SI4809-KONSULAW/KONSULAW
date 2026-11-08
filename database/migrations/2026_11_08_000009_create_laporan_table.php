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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelapor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('ditangani_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->string('target_tipe', 50)->comment("misal: 'ulasan', 'advokat', 'konsultasi'");
            $table->unsignedBigInteger('target_id');
            $table->text('alasan');
            $table->enum('status', ['pending', 'diselesaikan'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};

