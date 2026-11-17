<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperasionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mengambil data referensi yang sudah ada tanpa melakukan truncate agar data aman
        $admin = DB::table('users')->where('role', 'admin')->first();
        $advokat = DB::table('users')->where('role', 'advokat')->first();
        $masyarakatProBono = DB::table('users')->where('role', 'masyarakat')->where('status_ekonomi', 'pro_bono')->first()
            ?? DB::table('users')->where('role', 'masyarakat')->first();
        $masyarakatReguler = DB::table('users')->where('role', 'masyarakat')->where('status_ekonomi', 'reguler')->first();
        $bidangHukum = DB::table('bidang_hukum')->first();

        // 1. Seed Tabel verifikasi_ekonomi
        if ($masyarakatProBono) {
            DB::table('verifikasi_ekonomi')->insert([
                'user_id'        => $masyarakatProBono->id,
                'diperiksa_oleh' => $admin ? $admin->id : null,
                'dokumen'        => 'dokumen/ekonomi/sktm_warga_desa.pdf',
                'status'         => 'disetujui',
                'catatan_admin'  => 'Dokumen SKTM dari Kelurahan valid dan memenuhi kriteria pro bono.',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        // 2. Seed Tabel konsultasi
        $konsultasiId = null;
        if ($masyarakatProBono && $advokat && $bidangHukum) {
            $konsultasiId = DB::table('konsultasi')->insertGetId([
                'masyarakat_id' => $masyarakatProBono->id,
                'advokat_id'    => $advokat->id,
                'bidang_id'     => $bidangHukum->id,
                'jenis_layanan' => 'pro_bono',
                'judul'         => 'Sengketa Batas Tanah Waris Keluarga di Desa Lengkong',
                'kronologi'     => 'Tanah warisan keluarga seluas 500 m2 di Desa Lengkong diklaim sepihak oleh tetangga batas tanpa menunjukkan bukti sertifikat yang sah.',
                'status'        => 'selesai',
                'created_at'    => now()->subDays(3),
                'updated_at'    => now()->subDay(),
            ]);

            // Konsultasi kedua (Jalur Reguler)
            if ($masyarakatReguler) {
                DB::table('konsultasi')->insert([
                    'masyarakat_id' => $masyarakatReguler->id,
                    'advokat_id'    => $advokat->id,
                    'bidang_id'     => $bidangHukum->id,
                    'jenis_layanan' => 'reguler',
                    'judul'         => 'Konsultasi Perjanjian Kerjasama Sewa Lahan Pertanian',
                    'kronologi'     => 'Ingin membuat surat perjanjian sewa lahan pertanian selama 3 tahun dengan pembagian hasil panen yang adil bagi petani penggarap.',
                    'status'        => 'diproses',
                    'created_at'    => now()->subHours(6),
                    'updated_at'    => now()->subHours(2),
                ]);
            }
        }

        // 3. Seed Tabel pesan_konsultasi
        if ($konsultasiId && $masyarakatProBono && $advokat) {
            DB::table('pesan_konsultasi')->insert([
                [
                    'konsultasi_id' => $konsultasiId,
                    'pengirim_id'   => $masyarakatProBono->id,
                    'isi'           => 'Selamat pagi Bapak/Ibu Advokat, mohon arahan terkait batas tanah waris keluarga kami yang diklaim tetangga.',
                    'lampiran'      => null,
                    'created_at'    => now()->subDays(2),
                    'updated_at'    => now()->subDays(2),
                ],
                [
                    'konsultasi_id' => $konsultasiId,
                    'pengirim_id'   => $advokat->id,
                    'isi'           => 'Selamat pagi. Apakah ada surat Letter C, Girik, atau saksi batas tanah dari pihak desa setempat?',
                    'lampiran'      => null,
                    'created_at'    => now()->subDays(2)->addMinutes(20),
                    'updated_at'    => now()->subDays(2)->addMinutes(20),
                ],
                [
                    'konsultasi_id' => $konsultasiId,
                    'pengirim_id'   => $masyarakatProBono->id,
                    'isi'           => 'Ada, kami punya salinan Letter C dan surat pengantar dari Kepala Desa.',
                    'lampiran'      => 'lampiran/letter_c_desa.pdf',
                    'created_at'    => now()->subDay(),
                    'updated_at'    => now()->subDay(),
                ],
            ]);
        }

        // 4. Seed Tabel ulasan
        if ($konsultasiId && $masyarakatProBono && $advokat) {
            DB::table('ulasan')->insert([
                'konsultasi_id'   => $konsultasiId,
                'masyarakat_id'   => $masyarakatProBono->id,
                'advokat_id'      => $advokat->id,
                'rating'          => 5,
                'komentar'        => 'Advokat sangat ramah, sabar, dan memberikan solusi mediasi yang jelas bagi kami warga desa.',
                'status_moderasi' => 'disetujui',
                'created_at'      => now()->subHours(12),
                'updated_at'      => now()->subHours(12),
            ]);
        }

        // 5. Seed Tabel laporan
        if ($masyarakatReguler && $admin) {
            DB::table('laporan')->insert([
                'pelapor_id'     => $masyarakatReguler->id,
                'ditangani_oleh' => $admin->id,
                'target_tipe'    => 'ulasan',
                'target_id'      => 1,
                'alasan'         => 'Permohonan pengecekan komentar ulasan yang terindikasi spam promosi di luar topik bantuan hukum.',
                'status'         => 'pending',
                'created_at'     => now()->subHours(4),
                'updated_at'     => now()->subHours(4),
            ]);
        }

        // 6. Seed Tabel notifikasi
        if ($masyarakatProBono) {
            DB::table('notifikasi')->insert([
                [
                    'user_id'    => $masyarakatProBono->id,
                    'tipe'       => 'verifikasi_ekonomi',
                    'isi'        => 'Selamat, berkas pengajuan status ekonomi Pro Bono Anda telah disetujui oleh admin.',
                    'dibaca_pada'=> now()->subDays(2),
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'user_id'    => $masyarakatProBono->id,
                    'tipe'       => 'konsultasi',
                    'isi'        => 'Advokat telah menanggapi pesan pada sesi konsultasi Anda.',
                    'dibaca_pada'=> null,
                    'created_at' => now()->subDay(),
                    'updated_at' => now()->subDay(),
                ],
            ]);
        }

        if ($advokat) {
            DB::table('notifikasi')->insert([
                'user_id'    => $advokat->id,
                'tipe'       => 'pengajuan_konsultasi',
                'isi'        => 'Terdapat permohonan konsultasi hukum baru yang menunggu konfirmasi Anda.',
                'dibaca_pada'=> null,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ]);
        }
    }
}

