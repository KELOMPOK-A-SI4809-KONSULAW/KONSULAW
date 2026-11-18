<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdvokatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('advokat_bidang')->truncate();
        DB::table('advokat_wilayah')->truncate();
        DB::table('dokumen_advokat')->truncate();
        DB::table('profil_advokat')->truncate();
        Schema::enableForeignKeyConstraints();

        // Data Advokat Sesuai Mockup Bab 4.5.2 Proposal Konsulaw
        $advokatConfigs = [
            [
                'email'             => 'ratna.advokat@konsulaw.id',
                'no_kta'            => 'KTA-PERADI-2024-0891',
                'no_bas'            => 'BAS-PT-MDN-2019-0412',
                'pengalaman'        => 'Berpengalaman 5+ tahun dalam sengketa tanah adat, agraria desa, dan hukum perdata umum.',
                'tarif_konsultasi'  => 150000.00,
                'kuota_probono'     => 2, // Sesuai Mockup 4.5.2: "Pro bono: sisa 2, Rp150rb"
                'wilayah_nama'      => 'Kab. Karo',
                'bidang_names'      => ['Pertanahan', 'Perdata'],
            ],
            [
                'email'             => 'yohanes.advokat@konsulaw.id',
                'no_kta'            => 'KTA-KAI-2023-1102',
                'no_bas'            => 'BAS-PT-KPG-2018-0911',
                'pengalaman'        => 'Fokus pendampingan hak pekerja, sengketa upah buruh tani, dan hukum ketenagakerjaan daerah.',
                'tarif_konsultasi'  => 300000.00,
                'kuota_probono'     => 0, // Sesuai Mockup 4.5.2: "Pro bono: sisa 0, Rp300rb"
                'wilayah_nama'      => 'Kupang',
                'bidang_names'      => ['Ketenagakerjaan'],
            ],
            [
                'email'             => 'siti.advokat@konsulaw.id',
                'no_kta'            => 'KTA-PERADI-2025-0144',
                'no_bas'            => 'BAS-PT-BDG-2021-0322',
                'pengalaman'        => 'Membantu mediasi hukum keluarga, pembagian hak waris tanah keluarga, dan perdata.',
                'tarif_konsultasi'  => 200000.00,
                'kuota_probono'     => 4, // Sesuai Mockup 4.5.2: "Pro bono: sisa 4, Rp200rb"
                'wilayah_nama'      => 'Kab. Garut',
                'bidang_names'      => ['Keluarga & Waris', 'Perdata'],
            ],
            [
                'email'             => 'ahmad.advokat@konsulaw.id',
                'no_kta'            => 'KTA-PERADI-2022-7712',
                'no_bas'            => 'BAS-PT-BDG-2017-0801',
                'pengalaman'        => 'Mendampingi masyarakat pedesaan dalam perizinan usaha kecil, pidana umum, dan sengketa batas lahan.',
                'tarif_konsultasi'  => 175000.00,
                'kuota_probono'     => 3,
                'wilayah_nama'      => 'Kabupaten Bandung',
                'bidang_names'      => ['Pertanahan', 'Pidana'],
            ],
        ];

        foreach ($advokatConfigs as $config) {
            $user = DB::table('users')->where('email', $config['email'])->first();
            if (! $user) {
                continue;
            }

            $profilId = DB::table('profil_advokat')->insertGetId([
                'user_id'           => $user->id,
                'no_kta'            => $config['no_kta'],
                'no_bas'            => $config['no_bas'],
                'pengalaman'        => $config['pengalaman'],
                'tarif_konsultasi'  => $config['tarif_konsultasi'],
                'kuota_probono'     => $config['kuota_probono'],
                'ketersediaan'      => true,
                'status_verifikasi' => 'disetujui',
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Dokumen valid
            DB::table('dokumen_advokat')->insert([
                [
                    'profil_advokat_id' => $profilId,
                    'jenis'             => 'KTA',
                    'file'              => 'dokumen/advokat/kta_' . $user->id . '.pdf',
                    'status'            => 'valid',
                    'catatan_admin'     => 'KTA Advokat aktif.',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
                [
                    'profil_advokat_id' => $profilId,
                    'jenis'             => 'BAS',
                    'file'              => 'dokumen/advokat/bas_' . $user->id . '.pdf',
                    'status'            => 'valid',
                    'catatan_admin'     => 'Berita Acara Sumpah sah.',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
                [
                    'profil_advokat_id' => $profilId,
                    'jenis'             => 'Ijazah',
                    'file'              => 'dokumen/advokat/ijazah_' . $user->id . '.pdf',
                    'status'            => 'valid',
                    'catatan_admin'     => 'Ijazah Sarjana Hukum terverifikasi.',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ],
            ]);

            // Hubungkan ke Wilayah Praktik
            $wilayah = DB::table('wilayah')->where('nama', $config['wilayah_nama'])->first();
            if ($wilayah) {
                DB::table('advokat_wilayah')->insert([
                    'profil_advokat_id' => $profilId,
                    'wilayah_id'        => $wilayah->id,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            // Hubungkan ke Bidang Hukum
            $bidangIds = DB::table('bidang_hukum')
                ->whereIn('nama', $config['bidang_names'])
                ->pluck('id');

            foreach ($bidangIds as $bId) {
                DB::table('advokat_bidang')->insert([
                    'profil_advokat_id' => $profilId,
                    'bidang_id'         => $bId,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }
        }
    }
}
