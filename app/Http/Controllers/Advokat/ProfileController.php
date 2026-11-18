<?php

namespace App\Http\Controllers\Advokat;

use App\Http\Controllers\Controller;
use App\Models\BidangHukum;
use App\Models\ProfilAdvokat;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir profil profesional advokat.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Dapatkan profil advokat, atau buat draf profil baru jika belum tersedia
        $profil = $user->profilAdvokat ?? ProfilAdvokat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'status_verifikasi' => 'pending',
                'ketersediaan'      => true,
                'kuota_probono'     => 5,
                'tarif_konsultasi'  => 0,
            ]
        );

        $daftarBidang = BidangHukum::orderBy('nama')->get();
        $selectedBidangIds = $profil->bidangHukum->pluck('id')->toArray();

        $daftarWilayah = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();
        $selectedWilayahIds = $profil->wilayahPraktik->pluck('id')->toArray();

        return view('advokat.profil', compact('user', 'profil', 'daftarBidang', 'selectedBidangIds', 'daftarWilayah', 'selectedWilayahIds'));
    }

    /**
     * Memperbarui profil profesional, spesialisasi, dan wilayah praktik advokat.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'no_kta'              => ['required', 'string', 'max:100'],
            'no_bas'              => ['nullable', 'string', 'max:100'],
            'tahun_mulai_praktik' => ['required', 'integer', 'min:1970', 'max:' . date('Y')],
            'tarif_konsultasi'    => ['required', 'numeric', 'min:0'],
            'biografi'            => ['required', 'string', 'min:20', 'max:2000'],
            'bidang_hukum_ids'    => ['required', 'array', 'min:1'],
            'bidang_hukum_ids.*'  => ['exists:bidang_hukum,id'],
            'wilayah_ids'         => ['nullable', 'array'],
            'wilayah_ids.*'       => ['exists:wilayah,id'],
        ], [
            'no_kta.required'              => 'Nomor Lisensi Advokat / KTA wajib diisi.',
            'tahun_mulai_praktik.required' => 'Tahun mulai praktik wajib diisi.',
            'tahun_mulai_praktik.min'      => 'Tahun mulai praktik tidak valid.',
            'tahun_mulai_praktik.max'      => 'Tahun mulai praktik tidak boleh melebihi tahun berjalan saat ini.',
            'tarif_konsultasi.required'    => 'Tarif konsultasi wajib diisi.',
            'tarif_konsultasi.numeric'     => 'Tarif konsultasi harus berupa nominal angka.',
            'biografi.required'            => 'Deskripsi diri / biografi profesional wajib diisi.',
            'biografi.min'                 => 'Biografi profesional minimal 20 karakter.',
            'bidang_hukum_ids.required'    => 'Pilih minimal satu spesialisasi bidang hukum.',
            'bidang_hukum_ids.*.exists'    => 'Kategori bidang hukum yang dipilih tidak valid.',
            'wilayah_ids.*.exists'         => 'Wilayah praktik yang dipilih tidak valid.',
        ]);

        $profil = $user->profilAdvokat()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'no_kta'              => $validated['no_kta'],
                'no_lisensi'          => $validated['no_kta'],
                'no_bas'              => $validated['no_bas'] ?? null,
                'tahun_mulai_praktik' => $validated['tahun_mulai_praktik'],
                'tarif_konsultasi'    => $validated['tarif_konsultasi'],
                'biografi'            => $validated['biografi'],
                'pengalaman'          => $validated['biografi'],
            ]
        );

        // Sinkronisasi Many-to-Many ke tabel pivot advokat_bidang & advokat_wilayah
        $profil->bidangHukum()->sync($validated['bidang_hukum_ids']);
        $profil->wilayahPraktik()->sync($validated['wilayah_ids'] ?? []);

        return redirect()->route('advokat.profil.edit')
            ->with('success', 'Profil profesional, spesialisasi hukum, dan wilayah praktik berhasil diperbarui!');
    }
}
