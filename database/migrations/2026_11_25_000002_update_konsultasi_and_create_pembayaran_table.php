<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi tabel konsultasi
        if (Schema::hasTable('konsultasi')) {
            Schema::table('konsultasi', function (Blueprint $table) {
                if (! Schema::hasColumn('konsultasi', 'pertanyaan_hukum')) {
                    $table->text('pertanyaan_hukum')->nullable()->after('kronologi');
                }
                if (! Schema::hasColumn('konsultasi', 'lampiran')) {
                    $table->text('lampiran')->nullable()->after('pertanyaan_hukum');
                }
                if (! Schema::hasColumn('konsultasi', 'alasan_penolakan')) {
                    $table->text('alasan_penolakan')->nullable()->after('status');
                }
                if (! Schema::hasColumn('konsultasi', 'batas_bayar')) {
                    $table->timestamp('batas_bayar')->nullable()->after('alasan_penolakan');
                }
            });

            // Perbarui tipe status menjadi VARCHAR(50) agar fleksibel mendukung semua status alur Bagian B
            // Status: 'menunggu_tanggapan', 'menunggu_pembayaran', 'berlangsung', 'selesai', 'ditolak', 'dibatalkan', 'kedaluwarsa'
            try {
                DB::statement("ALTER TABLE `konsultasi` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'menunggu_tanggapan'");
                // Sinkronkan data lama jika ada
                DB::statement("UPDATE `konsultasi` SET `status` = 'menunggu_tanggapan' WHERE `status` = 'diajukan'");
                DB::statement("UPDATE `konsultasi` SET `status` = 'berlangsung' WHERE `status` = 'diproses'");
            } catch (\Throwable $e) {
                // Abaikan jika database engine tidak mendukung ALTER TABLE MODIFY
            }
        }

        // 2. Buat tabel pembayaran (B3.2 README)
        if (! Schema::hasTable('pembayaran')) {
            Schema::create('pembayaran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('konsultasi_id')
                    ->unique()
                    ->constrained('konsultasi')
                    ->cascadeOnDelete();
                $table->string('order_id', 100)->unique();
                $table->decimal('jumlah', 14, 2)->default(0);
                $table->string('status', 50)->default('pending'); // pending, paid, failed, expired, cancelled
                $table->string('metode', 100)->nullable();
                $table->string('snap_token', 255)->nullable();
                $table->string('transaction_id', 255)->nullable();
                $table->timestamp('dibayar_pada')->nullable();
                $table->text('payload_notifikasi')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');

        if (Schema::hasTable('konsultasi')) {
            Schema::table('konsultasi', function (Blueprint $table) {
                $columns = ['pertanyaan_hukum', 'lampiran', 'alasan_penolakan', 'batas_bayar'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('konsultasi', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
