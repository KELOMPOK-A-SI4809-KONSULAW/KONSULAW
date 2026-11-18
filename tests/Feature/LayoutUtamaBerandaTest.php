<?php

namespace Tests\Feature;

use Tests\TestCase;

class LayoutUtamaBerandaTest extends TestCase
{
    public function test_halaman_beranda_dapat_diakses_dan_menampilkan_layout_utama(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Konsulaw');
        $response->assertSee('BANTUAN HUKUM SAMPAI KE DESA');
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
