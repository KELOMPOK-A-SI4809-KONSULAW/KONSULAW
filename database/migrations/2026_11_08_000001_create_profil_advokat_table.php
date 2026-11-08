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
        Schema::create('profil_advokat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('no_kta', 50)->nullable();
            $table->string('no_bas', 50)->nullable();
            $table->text('pengalaman')->nullable();
            $table->decimal('tarif_konsultasi', 12, 2)->default(0);
            $table->integer('kuota_probono')->default(0);
            $table->boolean('ketersediaan')->default(true);
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_advokat');
    }
};
