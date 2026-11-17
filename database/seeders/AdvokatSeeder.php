<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvokatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('advokat_bidang')->truncate();
        DB::table('advokat_wilayah')->truncate();
        DB::table('dokumen_advokat')->truncate();
        DB::table('profil_advokat')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Ambil data user yang memiliki role 'advokat'
        $advokatUser = DB::table('users')->where('role', 'advokat')->first();

        if (! $advokatUser) {
            return;
        }

        // 2. Insert ke tabel profil_advokat
        $profilAdvokatId = DB::table('profil_advokat')->insertGetId([
            'user_id'           => $advokatUser->id,
            'no_kta'            => 'KTA-PERADI-2024-0891',
            'no_bas'            => 'BAS-PT-BDG-2019-0412',
            'pengalaman'        => 'Praktisi hukum berpengalaman selama lebih dari 5 tahun dalam menangani sengketa pertanahan desa, hukum perdata, dan konsultasi pro bono untuk masyarakat pedesaan.',
            'tarif_konsultasi'  => 150000.00,
            'kuota_probono'     => 5,
            'ketersediaan'      => true,
            'status_verifikasi' => 'disetujui',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // 3. Insert ke tabel dokumen_advokat
        DB::table('dokumen_advokat')->insert([
            [
                'profil_advokat_id' => $profilAdvokatId,
                'jenis'             => 'KTA',
                'file'              => 'dokumen/advokat/kta_ratna_sembiring.pdf',
                'status'            => 'valid',
                'catatan_admin'     => 'KTA Peradi aktif dan terverifikasi.',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'profil_advokat_id' => $profilAdvokatId,
                'jenis'             => 'BAS',
                'file'              => 'dokumen/advokat/bas_ratna_sembiring.pdf',
                'status'            => 'valid',
                'catatan_admin'     => 'Berita Acara Sumpah PT Jawa Barat valid.',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'profil_advokat_id' => $profilAdvokatId,
                'jenis'             => 'Ijazah',
                'file'              => 'dokumen/advokat/ijazah_ratna_sembiring.pdf',
                'status'            => 'valid',
                'catatan_admin'     => 'Ijazah S1 Ilmu Hukum terakreditasi.',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);

        // 4. Insert ke tabel advokat_wilayah (Pivot)
        // Hubungkan advokat dengan wilayah Kabupaten Bandung dan Kota Jakarta Selatan
        $wilayahIds = DB::table('wilayah')
            ->whereIn('nama', ['Kabupaten Bandung', 'Kota Jakarta Selatan'])
            ->pluck('id');

        foreach ($wilayahIds as $wId) {
            DB::table('advokat_wilayah')->insert([
                'profil_advokat_id' => $profilAdvokatId,
                'wilayah_id'        => $wId,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        // 5. Insert ke tabel advokat_bidang (Pivot)
        // Hubungkan advokat dengan bidang keahlian: Hukum Pertanahan & Agraria Desa, serta Hukum Perdata
        $bidangIds = DB::table('bidang_hukum')
            ->whereIn('nama', ['Hukum Pertanahan & Agraria Desa', 'Hukum Perdata'])
            ->pluck('id');

        foreach ($bidangIds as $bId) {
            DB::table('advokat_bidang')->insert([
                'profil_advokat_id' => $profilAdvokatId,
                'bidang_id'         => $bId,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }
}

