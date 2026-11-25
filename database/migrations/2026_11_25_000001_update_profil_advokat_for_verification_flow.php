<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah enum status_verifikasi: tambahkan nilai 'belum_diajukan' dan 'terverifikasi'
        // Lakukan via raw SQL karena ALTER ENUM tidak didukung Blueprint secara langsung
        DB::statement("ALTER TABLE `profil_advokat` MODIFY COLUMN `status_verifikasi` ENUM('belum_diajukan','pending','disetujui','terverifikasi','ditolak') NOT NULL DEFAULT 'belum_diajukan'");

        Schema::table('profil_advokat', function (Blueprint $table) {
            // 2. Kolom untuk catatan penolakan dari admin
            if (!Schema::hasColumn('profil_advokat', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable()->after('status_verifikasi');
            }
            // 3. Kolom untuk nama organisasi / kantor advokat
            if (!Schema::hasColumn('profil_advokat', 'nama_organisasi')) {
                $table->string('nama_organisasi', 200)->nullable()->after('catatan_verifikasi');
            }
        });

        // 4. Update existing 'disetujui' rows to 'terverifikasi' untuk konsistensi dengan README
        // (disetujui dan terverifikasi adalah sinonim, terverifikasi jadi label UI)
        // Kita tetap pertahankan 'disetujui' di data lama untuk tidak merusak HomeController
    }

    public function down(): void
    {
        Schema::table('profil_advokat', function (Blueprint $table) {
            if (Schema::hasColumn('profil_advokat', 'catatan_verifikasi')) {
                $table->dropColumn('catatan_verifikasi');
            }
            if (Schema::hasColumn('profil_advokat', 'nama_organisasi')) {
                $table->dropColumn('nama_organisasi');
            }
        });

        DB::statement("ALTER TABLE `profil_advokat` MODIFY COLUMN `status_verifikasi` ENUM('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending'");
    }
};
