<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProBonoApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Konsulaw
|--------------------------------------------------------------------------
*/

// Beranda Utama Konsulaw (Berisi Daftar Advokat Terverifikasi)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Cari Advokat (Dikosongkan / Placeholder Sesuai Tiket Jira Sprint Berikutnya)
Route::get('/cari-advokat', function () {
    return view('advokat.index');
})->name('advokat.index');

// Halaman Kamus Hukum (Dikosongkan / Placeholder Sesuai Tiket Jira Sprint Berikutnya)
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

// Rute Pengguna Terautentikasi (Auth)
Route::middleware('auth')->group(function () {
    // Keluar / Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Modul Pengajuan Pro Bono (FR-04, LAW-10)
Route::get('/pro-bono', [ProBonoApplicationController::class, 'create'])->name('pro-bono.create');
Route::post('/pro-bono', [ProBonoApplicationController::class, 'store'])->name('pro-bono.store');
