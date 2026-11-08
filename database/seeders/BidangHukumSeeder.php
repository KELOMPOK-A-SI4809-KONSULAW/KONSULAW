<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BidangHukumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bidang_hukum')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Bidang hukum yang relevan dengan permasalahan hukum masyarakat dan desa di proposal Konsulaw
        $bidangHukum = [
            ['nama' => 'Hukum Pertanahan & Agraria Desa'],
            ['nama' => 'Hukum Perdata'],
            ['nama' => 'Hukum Ketenagakerjaan'],
            ['nama' => 'Hukum Pidana'],
            ['nama' => 'Hukum Keluarga & Waris'],
            ['nama' => 'Hukum Bisnis & UMKM'],
        ];

        foreach ($bidangHukum as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('bidang_hukum')->insert($bidangHukum);
    }
}

