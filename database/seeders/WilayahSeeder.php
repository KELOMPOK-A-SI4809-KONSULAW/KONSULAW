<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('wilayah')->truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Tingkat Provinsi (parent_id = null)
        $jabarId = DB::table('wilayah')->insertGetId([
            'parent_id'  => null,
            'nama'       => 'Jawa Barat',
            'tingkat'    => 'provinsi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dkiId = DB::table('wilayah')->insertGetId([
            'parent_id'  => null,
            'nama'       => 'DKI Jakarta',
            'tingkat'    => 'provinsi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sumutId = DB::table('wilayah')->insertGetId([
            'parent_id'  => null,
            'nama'       => 'Sumatera Utara',
            'tingkat'    => 'provinsi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nttId = DB::table('wilayah')->insertGetId([
            'parent_id'  => null,
            'nama'       => 'Nusa Tenggara Timur',
            'tingkat'    => 'provinsi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Tingkat Kabupaten / Kota (Sesuai Mockup Bab 4.5.2 Proposal)
        $karoId = DB::table('wilayah')->insertGetId([
            'parent_id'  => $sumutId,
            'nama'       => 'Kab. Karo',
            'tingkat'    => 'kabupaten_kota',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kupangId = DB::table('wilayah')->insertGetId([
            'parent_id'  => $nttId,
            'nama'       => 'Kupang',
            'tingkat'    => 'kabupaten_kota',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $garutId = DB::table('wilayah')->insertGetId([
            'parent_id'  => $jabarId,
            'nama'       => 'Kab. Garut',
            'tingkat'    => 'kabupaten_kota',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $kabBandungId = DB::table('wilayah')->insertGetId([
            'parent_id'  => $jabarId,
            'nama'       => 'Kabupaten Bandung',
            'tingkat'    => 'kabupaten_kota',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $jakselId = DB::table('wilayah')->insertGetId([
            'parent_id'  => $dkiId,
            'nama'       => 'Kota Jakarta Selatan',
            'tingkat'    => 'kabupaten_kota',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Tingkat Kecamatan
        $bojongsoangId = DB::table('wilayah')->insertGetId([
            'parent_id'  => $kabBandungId,
            'nama'       => 'Kecamatan Bojongsoang',
            'tingkat'    => 'kecamatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Tingkat Desa / Kelurahan
        DB::table('wilayah')->insert([
            [
                'parent_id'  => $bojongsoangId,
                'nama'       => 'Desa Lengkong',
                'tingkat'    => 'desa_kelurahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id'  => $bojongsoangId,
                'nama'       => 'Desa Bojongsoang',
                'tingkat'    => 'desa_kelurahan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
