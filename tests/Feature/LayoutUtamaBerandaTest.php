<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LayoutUtamaBerandaTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_beranda_dapat_diakses_dan_menampilkan_layout_utama(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Konsulaw');
        $response->assertSee('Daftar Advokat Terverifikasi');
        // Tombol masuk & daftar di navbar
        $response->assertSee('Masuk');
        $response->assertSee('Daftar Akun');
    }

    public function test_halaman_cari_advokat_dapat_diakses(): void
    {
        $response = $this->get('/cari-advokat');

        $response->assertOk();
        $response->assertSee('Pencarian &amp; Direktori Lengkap Advokat', false);
    }

    public function test_halaman_kamus_hukum_dapat_diakses(): void
    {
        $response = $this->get('/kamus-hukum');

        $response->assertOk();
        $response->assertSee('Kamus Istilah Hukum &amp; Peraturan', false);
    }

    public function test_halaman_beranda_menampilkan_identitas_pengguna_ketika_login(): void
    {
        $user = User::factory()->create([
            'nama' => 'Bambang Kusuma',
            'role' => 'masyarakat',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertOk();
        $response->assertSee('Bambang Kusuma');
        $response->assertSee('masyarakat');
    }

    public function test_layout_utama_memuat_navbar_footer_dan_navigasi_bawah_mobile_sesuai_nfr04(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        // Memastikan elemen Navigasi Bawah khusus tampilan mobile (md:hidden) tersedia
        $response->assertSee('id="navigasi-bawah-mobile"', false);
        $response->assertSee('Beranda');
        $response->assertSee('Cari');
        $response->assertSee('Konsultasi');
        $response->assertSee('Kamus');
        $response->assertSee('Akun');

        // Memastikan Footer memuat referensi SDGs 16 dan catatan batasan layanan
        $response->assertSee('SDGs Tujuan 16');
        $response->assertSee('Catatan Batasan Layanan (PBI-08)');
    }

    public function test_beranda_menayangkan_kelima_informasi_batasan_ruang_lingkup_layanan_sesuai_pbi08(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('id="batasan-layanan"', false);
        $response->assertSee('Informasi Batasan Ruang Lingkup Layanan Konsulaw');
        $response->assertSee('Cakupan Data & Partisipasi Advokat');
        $response->assertSee('Mekanisme Verifikasi Kredensial Internal');
        $response->assertSee('Fokus pada Konsultasi Awal (Pra-Litigasi)');
        $response->assertSee('Sifat Edukasi & Informasi Hukum Umum');
        $response->assertSee('Ketentuan Kuota Layanan Bantuan Pro Bono');
    }
}
