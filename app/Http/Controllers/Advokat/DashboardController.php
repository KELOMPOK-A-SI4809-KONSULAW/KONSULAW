<?php

namespace App\Http\Controllers\Advokat;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\ProfilAdvokat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dasbor advokat beserta pengaturan ketersediaan dan kuota pro bono.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $profil = $user->profilAdvokat ?? ProfilAdvokat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'status_verifikasi' => 'pending',
                'ketersediaan'      => true,
                'kuota_probono'     => 5,
                'tarif_konsultasi'  => 0,
            ]
        );

        $totalKonsultasi = 0;
        $konsultasiAktif = 0;
        $konsultasiProBono = 0;

        if (Schema::hasTable('konsultasi')) {
            $totalKonsultasi   = Konsultasi::where('advokat_id', $user->id)->count();
            $konsultasiAktif   = Konsultasi::where('advokat_id', $user->id)->whereIn('status', ['diajukan', 'diproses', 'berlangsung'])->count();
            $konsultasiProBono = Konsultasi::where('advokat_id', $user->id)->where('jenis_layanan', 'pro_bono')->count();
        }

        return view('advokat.dashboard', compact('user', 'profil', 'totalKonsultasi', 'konsultasiAktif', 'konsultasiProBono'));
    }

    /**
     * Memperbarui ketersediaan layanan dan kuota maksimal pro bono bulanan.
     */
    public function updateLayanan(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'ketersediaan'  => ['required', 'boolean'],
            'kuota_probono' => ['required', 'integer', 'min:0', 'max:100'],
        ], [
            'ketersediaan.required'  => 'Status ketersediaan layanan wajib dipilih.',
            'kuota_probono.required' => 'Kuota maksimal pro bono bulanan wajib diisi.',
            'kuota_probono.integer'  => 'Kuota pro bono harus berupa angka bulat.',
            'kuota_probono.min'      => 'Kuota pro bono minimal adalah 0.',
            'kuota_probono.max'      => 'Kuota pro bono maksimal adalah 100 per bulan.',
        ]);

        $profil = $user->profilAdvokat()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'ketersediaan'  => (bool) $validated['ketersediaan'],
                'kuota_probono' => $validated['kuota_probono'],
            ]
        );

        return redirect()->route('advokat.dashboard')
            ->with('success', 'Pengaturan ketersediaan layanan dan kuota pro bono bulanan berhasil diperbarui!');
    }
}
