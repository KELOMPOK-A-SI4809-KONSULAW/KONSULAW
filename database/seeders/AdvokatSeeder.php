<?php

namespace Database\Seeders;

use App\Models\BidangHukum;
use App\Models\DokumenAdvokat;
use App\Models\ProfilAdvokat;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdvokatSeeder extends Seeder
{
    /**
     * Menjalankan seeder untuk minimal 6 Advokat Terverifikasi dengan
     * wilayah dan keahlian spesialisasi yang berbeda-beda.
     */
    public function run(): void
    {
        // -------------------------------------------------------------
        // 1. Pastikan Wilayah Provinsi & Kabupaten/Kota Tersedia
        // -------------------------------------------------------------
        $jabar = Wilayah::firstOrCreate(['nama' => 'Jawa Barat'], ['tingkat' => 'provinsi']);
        $dki   = Wilayah::firstOrCreate(['nama' => 'DKI Jakarta'], ['tingkat' => 'provinsi']);
        $sumut = Wilayah::firstOrCreate(['nama' => 'Sumatera Utara'], ['tingkat' => 'provinsi']);
        $ntt   = Wilayah::firstOrCreate(['nama' => 'Nusa Tenggara Timur'], ['tingkat' => 'provinsi']);

        $kabBandung   = Wilayah::firstOrCreate(['nama' => 'Kabupaten Bandung'], ['parent_id' => $jabar->id, 'tingkat' => 'kabupaten_kota']);
        $kotaBandung  = Wilayah::firstOrCreate(['nama' => 'Kota Bandung'], ['parent_id' => $jabar->id, 'tingkat' => 'kabupaten_kota']);
        $kabGarut     = Wilayah::firstOrCreate(['nama' => 'Kab. Garut'], ['parent_id' => $jabar->id, 'tingkat' => 'kabupaten_kota']);
        $jaksel       = Wilayah::firstOrCreate(['nama' => 'Kota Jakarta Selatan'], ['parent_id' => $dki->id, 'tingkat' => 'kabupaten_kota']);
        $jakpus       = Wilayah::firstOrCreate(['nama' => 'Kota Jakarta Pusat'], ['parent_id' => $dki->id, 'tingkat' => 'kabupaten_kota']);
        $kabKaro      = Wilayah::firstOrCreate(['nama' => 'Kab. Karo'], ['parent_id' => $sumut->id, 'tingkat' => 'kabupaten_kota']);
        $kotaKupang   = Wilayah::firstOrCreate(['nama' => 'Kupang'], ['parent_id' => $ntt->id, 'tingkat' => 'kabupaten_kota']);

        // -------------------------------------------------------------
        // 2. Pastikan 6 Bidang Hukum Lengkap Tersedia
        // -------------------------------------------------------------
        $bidangMap = [
            'pertanahan'  => BidangHukum::firstOrCreate(['nama' => 'Hukum Pertanahan & Agraria Desa']),
            'perdata'     => BidangHukum::firstOrCreate(['nama' => 'Hukum Perdata']),
            'tenagakerja' => BidangHukum::firstOrCreate(['nama' => 'Hukum Ketenagakerjaan']),
            'pidana'      => BidangHukum::firstOrCreate(['nama' => 'Hukum Pidana']),
            'keluarga'    => BidangHukum::firstOrCreate(['nama' => 'Hukum Keluarga & Waris']),
            'bisnis'      => BidangHukum::firstOrCreate(['nama' => 'Hukum Bisnis & UMKM']),
        ];

        // -------------------------------------------------------------
        // 3. Konfigurasi Minimal 6 Advokat Terverifikasi
        // -------------------------------------------------------------
        $advokatData = [
            [
                // ADVOKAT 1
                'user' => [
                    'nama'       => 'Ratna Sembiring, S.H.',
                    'email'      => 'ratna.advokat@konsulaw.id',
                    'no_hp'      => '081298765431',
                    'wilayah_id' => $kabBandung->id,
                ],
                'profil' => [
                    'no_kta'              => 'KTA-PERADI-2024-0891',
                    'no_bas'              => 'BAS-PT-BDG-2019-0412',
                    'no_lisensi'          => 'KTA-PERADI-2024-0891',
                    'tahun_mulai_praktik' => 2019,
                    'tarif_konsultasi'    => 150000.00,
                    'kuota_probono'       => 5,
                    'ketersediaan'        => true,
                    'status_verifikasi'   => 'disetujui',
                    'pengalaman'          => 'Praktisi hukum berpengalaman selama lebih dari 5 tahun dalam menangani sengketa pertanahan desa, tanah adat/ulayat, hukum perdata, dan konsultasi pro bono untuk masyarakat pedesaan.',
                    'biografi'            => 'Praktisi hukum berpengalaman selama lebih dari 5 tahun dalam menangani sengketa pertanahan desa, tanah adat/ulayat, hukum perdata, dan konsultasi pro bono untuk masyarakat pedesaan.',
                ],
                'wilayah_ids' => [$kabBandung->id, $kotaBandung->id],
                'bidang_ids'  => [$bidangMap['pertanahan']->id, $bidangMap['perdata']->id],
            ],
            [
                // ADVOKAT 2
                'user' => [
                    'nama'       => 'Yohanes Bria, S.H., M.H.',
                    'email'      => 'yohanes.advokat@konsulaw.id',
                    'no_hp'      => '081298765432',
                    'wilayah_id' => $kotaKupang->id,
                ],
                'profil' => [
                    'no_kta'              => 'KTA-KAI-2023-1102',
                    'no_bas'              => 'BAS-PT-KPG-2018-0911',
                    'no_lisensi'          => 'KTA-KAI-2023-1102',
                    'tahun_mulai_praktik' => 2018,
                    'tarif_konsultasi'    => 250000.00,
                    'kuota_probono'       => 4,
                    'ketersediaan'        => true,
                    'status_verifikasi'   => 'disetujui',
                    'pengalaman'          => 'Fokus pada advokasi hak-hak pekerja pedesaan, perlindungan upah buruh tani dan perkebunan, keselamatan kerja, serta mediasi perselisihan hubungan industrial.',
                    'biografi'            => 'Fokus pada advokasi hak-hak pekerja pedesaan, perlindungan upah buruh tani dan perkebunan, keselamatan kerja, serta mediasi perselisihan hubungan industrial.',
                ],
                'wilayah_ids' => [$kotaKupang->id, $ntt->id],
                'bidang_ids'  => [$bidangMap['tenagakerja']->id],
            ],
            [
                // ADVOKAT 3
                'user' => [
                    'nama'       => 'Siti Nurhaliza, S.H.',
                    'email'      => 'siti.advokat@konsulaw.id',
                    'no_hp'      => '081298765433',
                    'wilayah_id' => $kabGarut->id,
                ],
                'profil' => [
                    'no_kta'              => 'KTA-PERADI-2025-0144',
                    'no_bas'              => 'BAS-PT-BDG-2021-0322',
                    'no_lisensi'          => 'KTA-PERADI-2025-0144',
                    'tahun_mulai_praktik' => 2021,
                    'tarif_konsultasi'    => 200000.00,
                    'kuota_probono'       => 6,
                    'ketersediaan'        => true,
                    'status_verifikasi'   => 'disetujui',
                    'pengalaman'          => 'Membantu mediasi hukum keluarga pedesaan, pembagian hak waris tanah keluarga secara adil, hak asuh anak, dan penyelesaian damai sengketa perdata masyarakat.',
                    'biografi'            => 'Membantu mediasi hukum keluarga pedesaan, pembagian hak waris tanah keluarga secara adil, hak asuh anak, dan penyelesaian damai sengketa perdata masyarakat.',
                ],
                'wilayah_ids' => [$kabGarut->id, $kabBandung->id],
                'bidang_ids'  => [$bidangMap['keluarga']->id, $bidangMap['perdata']->id],
            ],
            [
                // ADVOKAT 4
                'user' => [
                    'nama'       => 'Ahmad Fadillah, S.H.',
                    'email'      => 'ahmad.advokat@konsulaw.id',
                    'no_hp'      => '081298765434',
                    'wilayah_id' => $jaksel->id,
                ],
                'profil' => [
                    'no_kta'              => 'KTA-PERADI-2022-7712',
                    'no_bas'              => 'BAS-PT-JKT-2017-0801',
                    'no_lisensi'          => 'KTA-PERADI-2022-7712',
                    'tahun_mulai_praktik' => 2017,
                    'tarif_konsultasi'    => 175000.00,
                    'kuota_probono'       => 3,
                    'ketersediaan'        => true,
                    'status_verifikasi'   => 'disetujui',
                    'pengalaman'          => 'Mendampingi masyarakat dalam pendampingan hukum pidana umum, perlindungan hak saksi dan korban, serta konsultasi restorative justice untuk warga desa.',
                    'biografi'            => 'Mendampingi masyarakat dalam pendampingan hukum pidana umum, perlindungan hak saksi dan korban, serta konsultasi restorative justice untuk warga desa.',
                ],
                'wilayah_ids' => [$jaksel->id, $dki->id],
                'bidang_ids'  => [$bidangMap['pidana']->id],
            ],
            [
                // ADVOKAT 5
                'user' => [
                    'nama'       => 'Dewi Lestari, S.H., LL.M.',
                    'email'      => 'dewi.advokat@konsulaw.id',
                    'no_hp'      => '081298765435',
                    'wilayah_id' => $kotaBandung->id,
                ],
                'profil' => [
                    'no_kta'              => 'KTA-PERADI-2021-5541',
                    'no_bas'              => 'BAS-PT-BDG-2016-0119',
                    'no_lisensi'          => 'KTA-PERADI-2021-5541',
                    'tahun_mulai_praktik' => 2016,
                    'tarif_konsultasi'    => 220000.00,
                    'kuota_probono'       => 5,
                    'ketersediaan'        => true,
                    'status_verifikasi'   => 'disetujui',
                    'pengalaman'          => 'Spesialis pendampingan hukum koperasi desa, perizinan UMKM dan usaha mikro, pendaftaran merek dagang, serta penyusunan perjanjian kemitraan bisnis.',
                    'biografi'            => 'Spesialis pendampingan hukum koperasi desa, perizinan UMKM dan usaha mikro, pendaftaran merek dagang, serta penyusunan perjanjian kemitraan bisnis.',
                ],
                'wilayah_ids' => [$kotaBandung->id, $jabar->id],
                'bidang_ids'  => [$bidangMap['bisnis']->id],
            ],
            [
                // ADVOKAT 6
                'user' => [
                    'nama'       => 'Bambang Prakoso, S.H.',
                    'email'      => 'bambang.advokat@konsulaw.id',
                    'no_hp'      => '081298765436',
                    'wilayah_id' => $jakpus->id,
                ],
                'profil' => [
                    'no_kta'              => 'KTA-IKADIN-2020-3329',
                    'no_bas'              => 'BAS-PT-JKT-2015-0678',
                    'no_lisensi'          => 'KTA-IKADIN-2020-3329',
                    'tahun_mulai_praktik' => 2015,
                    'tarif_konsultasi'    => 280000.00,
                    'kuota_probono'       => 2,
                    'ketersediaan'        => true,
                    'status_verifikasi'   => 'disetujui',
                    'pengalaman'          => 'Berpengalaman menangani sengketa batas tanah desa, pendaftaran hak milik adat, wanprestasi kerjasama agrobisnis, dan mediasi perkara perdata non-litigasi.',
                    'biografi'            => 'Berpengalaman menangani sengketa batas tanah desa, pendaftaran hak milik adat, wanprestasi kerjasama agrobisnis, dan mediasi perkara perdata non-litigasi.',
                ],
                'wilayah_ids' => [$jakpus->id, $jaksel->id],
                'bidang_ids'  => [$bidangMap['pertanahan']->id, $bidangMap['bisnis']->id],
            ],
        ];

        // -------------------------------------------------------------
        // 4. Eksekusi Penyimpanan Aman (Tanpa Menghapus Data Lain)
        // -------------------------------------------------------------
        foreach ($advokatData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['user']['email']],
                [
                    'nama'           => $data['user']['nama'],
                    'password'       => Hash::make('password123'),
                    'no_hp'          => $data['user']['no_hp'],
                    'wilayah_id'     => $data['user']['wilayah_id'],
                    'role'           => 'advokat',
                    'status_akun'    => 'aktif',
                    'status_ekonomi' => null,
                ]
            );

            $profil = ProfilAdvokat::updateOrCreate(
                ['user_id' => $user->id],
                $data['profil']
            );

            // Sinkronisasi Many-to-Many
            $profil->wilayahPraktik()->sync($data['wilayah_ids']);
            $profil->bidangHukum()->sync($data['bidang_ids']);

            // Pastikan dokumen kredensial valid
            DokumenAdvokat::updateOrCreate(
                ['profil_advokat_id' => $profil->id, 'jenis' => 'KTA'],
                [
                    'file'          => 'dokumen/advokat/kta_' . $user->id . '.pdf',
                    'status'        => 'valid',
                    'catatan_admin' => 'KTA Advokat aktif dan sah.',
                ]
            );
            DokumenAdvokat::updateOrCreate(
                ['profil_advokat_id' => $profil->id, 'jenis' => 'BAS'],
                [
                    'file'          => 'dokumen/advokat/bas_' . $user->id . '.pdf',
                    'status'        => 'valid',
                    'catatan_admin' => 'Berita Acara Sumpah terdaftar di Pengadilan Tinggi.',
                ]
            );
            DokumenAdvokat::updateOrCreate(
                ['profil_advokat_id' => $profil->id, 'jenis' => 'Ijazah'],
                [
                    'file'          => 'dokumen/advokat/ijazah_' . $user->id . '.pdf',
                    'status'        => 'valid',
                    'catatan_admin' => 'Ijazah Sarjana Hukum (S.H.) terverifikasi.',
                ]
            );
        }
    }
}
