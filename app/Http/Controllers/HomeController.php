<?php

namespace App\Http\Controllers;

use App\Models\ProfilAdvokat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda utama Konsulaw (Berisi Daftar Advokat Terverifikasi).
     */
    public function index(Request $request): View
    {
        if (! Schema::hasTable('profil_advokat')) {
            $advokatList = collect();
            return view('welcome', compact('advokatList'));
        }

        $advokatList = ProfilAdvokat::query()
            ->where('status_verifikasi', 'disetujui')
            ->where('ketersediaan', true)
            ->with(['user.wilayah', 'bidangHukum', 'wilayahPraktik', 'ulasan'])
            ->latest('id')
            ->get();

        return view('welcome', compact('advokatList'));
    }
}
