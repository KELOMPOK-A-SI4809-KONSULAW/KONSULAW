<?php

namespace Tests\Feature;

use App\Models\ProfilAdvokat;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_masuk_dapat_diakses_oleh_tamu(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('Alamat Email');
        $response->assertSee('Kata Sandi');
    }

    public function test_halaman_daftar_dapat_diakses_oleh_tamu(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('Masyarakat');
        $response->assertSee('Advokat');
    }

    public function test_masyarakat_dapat_mendaftar_dan_otomatis_masuk(): void
    {
        $wilayah = Wilayah::create([
            'nama'    => 'Kabupaten Bandung',
            'tingkat' => 'kabupaten_kota',
        ]);

        $response = $this->post('/register', [
            'nama'                  => 'Ahmad Solihin',
            'email'                 => 'ahmad.solihin@desa.id',
            'no_hp'                 => '081234567899',
            'wilayah_id'            => $wilayah->id,
            'role'                  => 'masyarakat',
            'password'              => 'katasandi123',
            'password_confirmation' => 'katasandi123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email'          => 'ahmad.solihin@desa.id',
            'role'           => 'masyarakat',
            'status_akun'    => 'aktif',
            'status_ekonomi' => 'reguler',
            'wilayah_id'     => $wilayah->id,
        ]);

        $user = User::where('email', 'ahmad.solihin@desa.id')->first();
        $this->assertTrue(Hash::check('katasandi123', $user->password));
    }

    public function test_advokat_dapat_mendaftar_dan_profil_advokat_dibuat_dengan_status_pending(): void
    {
        $response = $this->post('/register', [
            'nama'                  => 'Sari Dewi, S.H.',
            'email'                 => 'sari.dewi@kantorhukum.id',
            'no_hp'                 => '081299998888',
            'role'                  => 'advokat',
            'password'              => 'advokataman123',
            'password_confirmation' => 'advokataman123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = User::where('email', 'sari.dewi@kantorhukum.id')->firstOrFail();
        $this->assertEquals('advokat', $user->role);

        // Profil advokat otomatis terbuat dan berstatus verifikasi 'pending'
        $this->assertDatabaseHas('profil_advokat', [
            'user_id'           => $user->id,
            'status_verifikasi' => 'pending',
        ]);
    }

    public function test_validasi_pendaftaran_menolak_email_duplikat_dan_sandi_pendek(): void
    {
        User::factory()->create([
            'email' => 'terdaftar@konsulaw.id',
        ]);

        $response = $this->post('/register', [
            'nama'                  => 'Warga Baru',
            'email'                 => 'terdaftar@konsulaw.id',
            'no_hp'                 => '0811112222',
            'role'                  => 'masyarakat',
            'password'              => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_pengguna_dapat_masuk_dengan_kredensial_yang_benar(): void
    {
        $user = User::factory()->create([
            'email'       => 'budi@warga.id',
            'password'    => Hash::make('password123'),
            'status_akun' => 'aktif',
        ]);

        $response = $this->post('/login', [
            'email'    => 'budi@warga.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_pengguna_gagal_masuk_dengan_kata_sandi_salah(): void
    {
        User::factory()->create([
            'email'    => 'budi@warga.id',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email'    => 'budi@warga.id',
            'password' => 'sandi-yang-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_pengguna_dengan_status_akun_nonaktif_tidak_dapat_masuk(): void
    {
        User::factory()->create([
            'email'       => 'suspend@warga.id',
            'password'    => Hash::make('password123'),
            'status_akun' => 'nonaktif',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email'    => 'suspend@warga.id',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_pengguna_dapat_keluar_logout_dari_sistem(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_middleware_role_mengizinkan_peran_yang_sesuai(): void
    {
        Route::get('/tes-advokat', function () {
            return 'Area Advokat';
        })->middleware('role:advokat');

        $advokat = User::factory()->create(['role' => 'advokat']);

        $response = $this->actingAs($advokat)->get('/tes-advokat');
        $response->assertOk();
        $response->assertSee('Area Advokat');
    }

    public function test_middleware_role_menolak_peran_yang_tidak_berhak(): void
    {
        Route::get('/tes-admin', function () {
            return 'Area Khusus Admin';
        })->middleware('role:admin');

        $masyarakat = User::factory()->create(['role' => 'masyarakat']);

        $response = $this->actingAs($masyarakat)->get('/tes-admin');
        $response->assertStatus(403);
    }

    public function test_pengguna_dapat_meminta_tautan_pemulihan_kata_sandi(): void
    {
        $user = User::factory()->create(['email' => 'lupa@warga.id']);

        $response = $this->post('/forgot-password', [
            'email' => 'lupa@warga.id',
        ]);

        $response->assertSessionHas('status');
    }

    public function test_pengguna_dapat_mengatur_ulang_kata_sandi_baru_dengan_token_sah(): void
    {
        $user = User::factory()->create(['email' => 'reset@warga.id']);
        $token = Password::broker()->createToken($user);

        $response = $this->post('/reset-password', [
            'token'                 => $token,
            'email'                 => 'reset@warga.id',
            'password'              => 'sandibaru123',
            'password_confirmation' => 'sandibaru123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('sandibaru123', $user->password));
    }
}
