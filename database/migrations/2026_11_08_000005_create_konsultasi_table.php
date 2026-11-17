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
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masyarakat_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('advokat_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('bidang_id')
                ->constrained('bidang_hukum')
                ->cascadeOnDelete();
            $table->enum('jenis_layanan', ['reguler', 'pro_bono']);
            $table->string('judul', 255);
            $table->text('kronologi');
            $table->enum('status', ['diajukan', 'diproses', 'selesai', 'dibatalkan'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi');
    }
};

