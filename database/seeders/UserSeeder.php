<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Ambil sampel ID wilayah
        $wilayahDesa = DB::table('wilayah')->where('tingkat', 'desa_kelurahan')->first();
        $wilayahKab  = DB::table('wilayah')->where('tingkat', 'kabupaten_kota')->first();
        $wilayahProv = DB::table('wilayah')->where('tingkat', 'provinsi')->first();

        DB::table('users')->insert([
            // 1. Akun Admin Platform
            [
                'wilayah_id'     => $wilayahProv ? $wilayahProv->id : null,
                'nama'           => 'Administrator Konsulaw',
                'email'          => 'admin@konsulaw.id',
                'password'       => Hash::make('password123'),
                'no_hp'          => '081234567890',
                'role'           => 'admin',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => 'reguler',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 2. Akun Advokat (Contoh sesuai mockup proposal Konsulaw)
            [
                'wilayah_id'     => $wilayahKab ? $wilayahKab->id : null,
                'nama'           => 'Ratna Sembiring, S.H.',
                'email'          => 'ratna.advokat@konsulaw.id',
                'password'       => Hash::make('password123'),
                'no_hp'          => '081298765432',
                'role'           => 'advokat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => 'reguler',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 3. Akun Masyarakat Reguler
            [
                'wilayah_id'     => $wilayahDesa ? $wilayahDesa->id : null,
                'nama'           => 'Budi Santoso',
                'email'          => 'budi.santoso@gmail.com',
                'password'       => Hash::make('password123'),
                'no_hp'          => '085712345678',
                'role'           => 'masyarakat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => 'reguler',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 4. Akun Masyarakat Pemohon Layanan Pro Bono
            [
                'wilayah_id'     => $wilayahDesa ? $wilayahDesa->id : null,
                'nama'           => 'Siti Aminah',
                'email'          => 'siti.aminah@gmail.com',
                'password'       => Hash::make('password123'),
                'no_hp'          => '085812345679',
                'role'           => 'masyarakat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => 'pro_bono',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}

