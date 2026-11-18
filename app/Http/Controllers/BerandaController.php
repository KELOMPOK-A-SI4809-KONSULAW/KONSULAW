<?php

namespace App\Http\Controllers;

use App\Models\BidangHukum;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BerandaController extends Controller
{
    /**
     * Menampilkan halaman beranda utama dengan data dinamis wilayah dan bidang hukum.
     */
    public function index()
    {
        $wilayahList = collect();
        $bidangHukumList = collect();

        // Aman untuk lingkungan testing (in-memory sqlite) maupun production
        try {
            if (Schema::hasTable('wilayah')) {
                $wilayahList = Wilayah::orderBy('nama')->get();
            }
            if (Schema::hasTable('bidang_hukum')) {
                $bidangHukumList = BidangHukum::orderBy('nama')->get();
            }
        } catch (\Throwable $e) {
            // Fallback graceful jika koneksi / tabel belum termigrasi
        }

        $kataKunciPopuler = [
            'Sengketa batas sawah',
            'Tanah waris & Letter C',
            'PHK & pesangon',
            'Wanprestasi',
            'Keluarga & waris',
            'Bantuan pro bono',
        ];

        return view('welcome', compact('wilayahList', 'bidangHukumList', 'kataKunciPopuler'));
    }
}