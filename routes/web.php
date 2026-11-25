<?php

use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Advokat\DashboardController as AdvokatDashboardController;
use App\Http\Controllers\Advokat\ProfileController as AdvokatProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KonsultasiController;
use App\Http\Controllers\Masyarakat\ProfileController as MasyarakatProfileController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProBonoApplicationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Konsulaw
|--------------------------------------------------------------------------
*/

// Beranda Utama Konsulaw (Berisi Daftar Advokat Terverifikasi)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Cari Advokat (Direktori Publik)
Route::get('/cari-advokat', [HomeController::class, 'index'])->name('advokat.index');

// Halaman Kamus Hukum
Route::get('/kamus-hukum', function () {
    return view('kamus.index');
})->name('kamus.index');

// Rute Autentikasi Pengguna Tamu (Guest)
Route::middleware('guest')->group(function () {
    // Registrasi Pengguna Baru (Masyarakat & Advokat)
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // Masuk / Login
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    // Pemulihan Kata Sandi (Forgot & Reset Password)
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Modul Pengajuan Pro Bono (FR-04, LAW-10)
Route::get('/pro-bono', [ProBonoApplicationController::class, 'create'])->name('pro-bono.create');
Route::post('/pro-bono', [ProBonoApplicationController::class, 'store'])->name('pro-bono.store');

// Formulir Pengajuan Konsultasi (Dapat diakses tamu lalu redirect intended jika belum login)
Route::get('/konsultasi/ajukan', [KonsultasiController::class, 'create'])->name('konsultasi.create');

// Webhook Notifikasi Midtrans Server-to-Server (CSRF Exempt)
Route::post('/midtrans/notification', [PembayaranController::class, 'webhook'])->name('midtrans.notification');

// =========================================================================
// Rute Pengguna Terautentikasi (Auth Protected)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Keluar / Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Hub Rute Profil Pengguna (Mengarahkan otomatis sesuai role)
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');

    // ---------------------------------------------------------------------
    // 0. Rute Modul Konsultasi & Pembayaran (Masyarakat & Advokat)
    // ---------------------------------------------------------------------
    Route::prefix('konsultasi')->name('konsultasi.')->group(function () {
        Route::post('/ajukan', [KonsultasiController::class, 'store'])->name('store');
        Route::get('/{id}', [KonsultasiController::class, 'show'])->name('show');
        Route::post('/{id}/batalkan', [KonsultasiController::class, 'batalkan'])->name('batalkan');
        Route::get('/{id}/chat', [KonsultasiController::class, 'chat'])->name('chat');
        Route::post('/{id}/chat', [KonsultasiController::class, 'kirimPesan'])->name('chat.kirim');
        Route::post('/{id}/selesai', [KonsultasiController::class, 'selesaikan'])->name('selesaikan');
        Route::post('/{id}/ulasan', [KonsultasiController::class, 'simpanUlasan'])->name('ulasan');
        Route::get('/{id}/lampiran/{index}', [KonsultasiController::class, 'unduhLampiran'])->name('lampiran.unduh');
    });

    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::post('/konsultasi/{id}', [PembayaranController::class, 'inisialisasi'])->name('inisialisasi');
        Route::post('/konsultasi/{id}/simulasi', [PembayaranController::class, 'simulasiSukses'])->name('simulasi');
    });

    // ---------------------------------------------------------------------
    // 1. Rute Pengelolaan Profil MASYARAKAT
    // ---------------------------------------------------------------------
    Route::middleware('role:masyarakat')->prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/profil', [MasyarakatProfileController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [MasyarakatProfileController::class, 'update'])->name('profil.update');
        Route::post('/profil/foto', [MasyarakatProfileController::class, 'updateFoto'])->name('profil.foto');
        Route::delete('/profil/foto', [MasyarakatProfileController::class, 'hapusFoto'])->name('profil.hapus-foto');
        Route::post('/profil/verifikasi-ekonomi', [MasyarakatProfileController::class, 'ajukanVerifikasiEkonomi'])->name('profil.verifikasi-ekonomi');
        Route::put('/profil/password', [MasyarakatProfileController::class, 'updatePassword'])->name('profil.password');
    });

    // ---------------------------------------------------------------------
    // 2. Rute Pengelolaan Profil & Layanan ADVOKAT
    // ---------------------------------------------------------------------
    Route::middleware('role:advokat')->prefix('advokat')->name('advokat.')->group(function () {
        // Dasbor
        Route::get('/dashboard', [AdvokatDashboardController::class, 'index'])->name('dashboard');
        Route::put('/layanan', [AdvokatDashboardController::class, 'updateLayanan'])->name('layanan.update');

        // Aksi Penerimaan / Penolakan Konsultasi
        Route::post('/konsultasi/{id}/terima', [AdvokatDashboardController::class, 'terima'])->name('konsultasi.terima');
        Route::post('/konsultasi/{id}/tolak', [AdvokatDashboardController::class, 'tolak'])->name('konsultasi.tolak');

        // Profil Saya — halaman utama (multi-tab)
        Route::get('/profil', [AdvokatProfileController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [AdvokatProfileController::class, 'update'])->name('profil.update');

        // Foto profil
        Route::post('/profil/foto', [AdvokatProfileController::class, 'updateFoto'])->name('profil.foto');
        Route::delete('/profil/foto', [AdvokatProfileController::class, 'hapusFoto'])->name('profil.hapus-foto');

        // Simpan per-tab
        Route::put('/profil/info-pribadi', [AdvokatProfileController::class, 'updateInfoPribadi'])->name('profil.info-pribadi');
        Route::put('/profil/data-profesional', [AdvokatProfileController::class, 'updateDataProfesional'])->name('profil.data-profesional');
        Route::put('/profil/bidang-wilayah', [AdvokatProfileController::class, 'updateBidangWilayah'])->name('profil.bidang-wilayah');
        Route::post('/profil/upload-dokumen', [AdvokatProfileController::class, 'uploadDokumen'])->name('profil.upload-dokumen');
        Route::post('/profil/kirim-verifikasi', [AdvokatProfileController::class, 'kirimVerifikasi'])->name('profil.kirim-verifikasi');
        Route::put('/profil/tarif-ketersediaan', [AdvokatProfileController::class, 'updateTarifKetersediaan'])->name('profil.tarif-ketersediaan');
        Route::put('/profil/kuota', [AdvokatProfileController::class, 'updateKuota'])->name('profil.kuota');
        Route::put('/profil/password', [AdvokatProfileController::class, 'updatePassword'])->name('profil.password');
    });

    // ---------------------------------------------------------------------
    // 3. Rute Pengelolaan Profil & Pusat Kendali ADMINISTRATOR (Proposal Sdg 16)
    // ---------------------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/profil', [AdminProfileController::class, 'edit'])->name('profil.edit');
        Route::put('/profil/info', [AdminProfileController::class, 'updateInfo'])->name('profil.update-info');
        Route::post('/profil/foto', [AdminProfileController::class, 'updateFoto'])->name('profil.foto');
        Route::delete('/profil/foto', [AdminProfileController::class, 'hapusFoto'])->name('profil.hapus-foto');
        Route::put('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profil.password');

        // Verifikasi Kredensial Advokat (FR-03 & Use Case C Hal 25-27 Proposal)
        Route::post('/verifikasi-advokat/{id}/setujui', [AdminProfileController::class, 'setujuiAdvokat'])->name('verifikasi.setujui');
        Route::post('/verifikasi-advokat/{id}/tolak', [AdminProfileController::class, 'tolakAdvokat'])->name('verifikasi.tolak');
        Route::get('/dokumen-advokat/{id}/unduh', [AdminProfileController::class, 'unduhDokumen'])->name('dokumen.unduh');

        // Verifikasi Status Ekonomi Masyarakat untuk Akses Pro Bono (FR-04, PBI-09)
        Route::post('/verifikasi-probono/{id}/setujui', [AdminProfileController::class, 'setujuiProBono'])->name('probono.setujui');
        Route::post('/verifikasi-probono/{id}/tolak', [AdminProfileController::class, 'tolakProBono'])->name('probono.tolak');
    });
});
