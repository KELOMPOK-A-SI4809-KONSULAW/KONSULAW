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
        Schema::create('advokat_bidang', function (Blueprint $table) {
            $table->foreignId('profil_advokat_id')
                ->constrained('profil_advokat')
                ->cascadeOnDelete();
            $table->foreignId('bidang_id')
                ->constrained('bidang_hukum')
                ->cascadeOnDelete();
            $table->primary(['profil_advokat_id', 'bidang_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advokat_bidang');
    }
};

