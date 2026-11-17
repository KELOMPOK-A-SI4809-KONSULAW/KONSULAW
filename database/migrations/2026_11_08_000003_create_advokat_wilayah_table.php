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
        Schema::create('advokat_wilayah', function (Blueprint $table) {
            $table->foreignId('profil_advokat_id')
                ->constrained('profil_advokat')
                ->cascadeOnDelete();
            $table->foreignId('wilayah_id')
                ->constrained('wilayah')
                ->cascadeOnDelete();
            $table->primary(['profil_advokat_id', 'wilayah_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advokat_wilayah');
    }
};

