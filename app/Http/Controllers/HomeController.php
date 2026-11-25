<?php

namespace App\Http\Controllers;

use App\Models\BidangHukum;
use App\Models\ProfilAdvokat;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda utama Konsulaw (Direktori Advokat Terverifikasi + Filter Kasus).
     */
    public function index(Request $request): View
    {
        if (! Schema::hasTable('profil_advokat')) {
            $advokatList = collect();
            $bidangHukumList = collect();
            $wilayahList = collect();
            return view('welcome', compact('advokatList', 'bidangHukumList', 'wilayahList'));
        }

        $query = ProfilAdvokat::query()
            ->where('status_verifikasi', 'disetujui')
            ->where('ketersediaan', true)
            ->with(['user.wilayah', 'bidangHukum', 'wilayahPraktik', 'ulasan']);

        // Filter bidang hukum / kasus
        if ($request->filled('bidang') && $request->bidang !== 'semua') {
            $bidangVal = $request->bidang;
            $query->whereHas('bidangHukum', function ($q) use ($bidangVal) {
                $q->where('nama', $bidangVal)->orWhere('bidang_hukum.id', $bidangVal);
            });
        }

        // Filter toggle Pro Bono tersedia
        if ($request->boolean('pro_bono')) {
            $query->where('kuota_probono', '>', 0);
        }

        // Filter wilayah
        if ($request->filled('wilayah')) {
            $wilayahVal = $request->wilayah;
            $query->where(function ($q) use ($wilayahVal) {
                $q->whereHas('wilayahPraktik', function ($wq) use ($wilayahVal) {
                    $wq->where('wilayah.id', $wilayahVal)->orWhere('wilayah.nama', 'like', '%' . $wilayahVal . '%');
                })->orWhereHas('user', function ($uq) use ($wilayahVal) {
                    $uq->where('wilayah_id', $wilayahVal);
                });
            });
        }

        // Pengurutan
        if ($request->get('urutkan') === 'tarif_terendah') {
            $query->orderBy('tarif_konsultasi', 'asc');
        } elseif ($request->get('urutkan') === 'tarif_tertinggi') {
            $query->orderBy('tarif_konsultasi', 'desc');
        } else {
            $query->latest('id');
        }

        $advokatList = $query->get();

        $bidangHukumList = Schema::hasTable('bidang_hukum') ? BidangHukum::all() : collect();
        $wilayahList = Schema::hasTable('wilayah') ? Wilayah::all() : collect();

        return view('welcome', compact('advokatList', 'bidangHukumList', 'wilayahList'));
    }
}
