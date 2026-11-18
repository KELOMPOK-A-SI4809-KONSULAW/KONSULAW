<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BidangHukumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('bidang_hukum')->truncate();
        Schema::enableForeignKeyConstraints();

        // Bidang hukum yang relevan dengan permasalahan hukum masyarakat dan desa di proposal Konsulaw
        $bidangHukum = [
            ['nama' => 'Pertanahan'],
            ['nama' => 'Perdata'],
            ['nama' => 'Ketenagakerjaan'],
            ['nama' => 'Keluarga & Waris'],
            ['nama' => 'Pidana'],
            ['nama' => 'Bisnis & UMKM'],
        ];

        foreach ($bidangHukum as &$item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
        }

        DB::table('bidang_hukum')->insert($bidangHukum);
    }
}
