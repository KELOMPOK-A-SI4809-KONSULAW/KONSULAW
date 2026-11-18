<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil sampel ID wilayah
        $wilayahDesa   = DB::table('wilayah')->where('tingkat', 'desa_kelurahan')->first();
        $wilayahKaro   = DB::table('wilayah')->where('nama', 'Kab. Karo')->first();
        $wilayahKupang = DB::table('wilayah')->where('nama', 'Kupang')->first();
        $wilayahGarut  = DB::table('wilayah')->where('nama', 'Kab. Garut')->first();
        $wilayahBandung= DB::table('wilayah')->where('nama', 'Kabupaten Bandung')->first();
        $wilayahProv   = DB::table('wilayah')->where('tingkat', 'provinsi')->first();

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
            // 2. Advokat 1: Ratna Sembiring, S.H. (Kab. Karo - Mockup 4.5.2)
            [
                'wilayah_id'     => $wilayahKaro ? $wilayahKaro->id : null,
                'nama'           => 'Ratna Sembiring, S.H.',
                'email'          => 'ratna.advokat@konsulaw.id',
                'password'       => Hash::make('password123'),
                'no_hp'          => '081298765431',
                'role'           => 'advokat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 3. Advokat 2: Yohanes Bria, S.H., M.H. (Kupang - Mockup 4.5.2)
            [
                'wilayah_id'     => $wilayahKupang ? $wilayahKupang->id : null,
                'nama'           => 'Yohanes Bria, S.H., M.H.',
                'email'          => 'yohanes.advokat@konsulaw.id',
                'password'       => Hash::make('password123'),
                'no_hp'          => '081298765432',
                'role'           => 'advokat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 4. Advokat 3: Siti Nurhaliza, S.H. (Kab. Garut - Mockup 4.5.2)
            [
                'wilayah_id'     => $wilayahGarut ? $wilayahGarut->id : null,
                'nama'           => 'Siti Nurhaliza, S.H.',
                'email'          => 'siti.advokat@konsulaw.id',
                'password'       => Hash::make('password123'),
                'no_hp'          => '081298765433',
                'role'           => 'advokat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 5. Advokat 4: Ahmad Fadillah, S.H. (Kab. Bandung)
            [
                'wilayah_id'     => $wilayahBandung ? $wilayahBandung->id : null,
                'nama'           => 'Ahmad Fadillah, S.H.',
                'email'          => 'ahmad.advokat@konsulaw.id',
                'password'       => Hash::make('password123'),
                'no_hp'          => '081298765434',
                'role'           => 'advokat',
                'foto_profil'    => null,
                'status_akun'    => 'aktif',
                'status_ekonomi' => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            // 6. Akun Masyarakat Reguler: Budi Santoso
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
            // 7. Akun Masyarakat Pemohon Layanan Pro Bono: Siti Aminah
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
