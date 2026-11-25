<?php

use App\Http\Controllers\Advokat\DashboardController as AdvokatDashboardController;
use App\Http\Controllers\Advokat\ProfileController as AdvokatProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Masyarakat\ProfileController as MasyarakatProfileController;
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
Route::get('/cari-advokat', function () {
    return view('advokat.index');
})->name('advokat.index');

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

// =========================================================================
// Rute Pengguna Terautentikasi (Auth Protected)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Keluar / Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Hub Rute Profil Pengguna (Mengarahkan otomatis sesuai role)
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil.index');

    // ---------------------------------------------------------------------
    // 1. Rute Pengelolaan Profil MASYARAKAT
    // ---------------------------------------------------------------------
    Route::middleware('role:masyarakat')->prefix('masyarakat')->name('masyarakat.')->group(function () {
        Route::get('/profil', [MasyarakatProfileController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [MasyarakatProfileController::class, 'update'])->name('profil.update');
    });

    // ---------------------------------------------------------------------
    // 2. Rute Pengelolaan Profil & Layanan ADVOKAT
    // ---------------------------------------------------------------------
    Route::middleware('role:advokat')->prefix('advokat')->name('advokat.')->group(function () {
        // Dasbor
        Route::get('/dashboard', [AdvokatDashboardController::class, 'index'])->name('dashboard');
        Route::put('/layanan', [AdvokatDashboardController::class, 'updateLayanan'])->name('layanan.update');

        // Profil Saya — halaman utama (multi-tab)
        Route::get('/profil', [AdvokatProfileController::class, 'edit'])->name('profil.edit');
        Route::put('/profil', [AdvokatProfileController::class, 'update'])->name('profil.update');

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
});
