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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsultasi_id')
                ->constrained('konsultasi')
                ->cascadeOnDelete();
            $table->foreignId('masyarakat_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('advokat_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedTinyInteger('rating')->comment('Nilai 1-5');
            $table->text('komentar')->nullable();
            $table->enum('status_moderasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};

