<?php

namespace App\Http\Controllers\Advokat;

use App\Http\Controllers\Controller;
use App\Models\BidangHukum;
use App\Models\DokumenAdvokat;
use App\Models\ProfilAdvokat;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Profil Saya advokat (multi-tab).
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Buat profil draft jika belum ada (first-time login)
        $profil = $user->profilAdvokat ?? ProfilAdvokat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'status_verifikasi' => 'belum_diajukan',
                'ketersediaan'      => true,
                'kuota_probono'     => 0,
                'tarif_konsultasi'  => 0,
            ]
        );

        // Reload relasi
        $profil->load(['bidangHukum', 'wilayahPraktik', 'dokumen', 'ulasan.masyarakat']);

        $daftarBidang      = BidangHukum::orderBy('nama')->get();
        $selectedBidangIds = $profil->bidangHukum->pluck('id')->toArray();

        $daftarWilayah     = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])
            ->orderBy('tingkat')->orderBy('nama')->get();
        $selectedWilayahIds = $profil->wilayahPraktik->pluck('id')->toArray();

        // Hitung dokumen per jenis
        $dokumenMap = $profil->dokumen->keyBy('jenis');

        // Kelengkapan
        $persen = $profil->persentaseKelengkapan();

        // Rating rata-rata
        $ratingAvg    = $profil->ulasan->avg('rating');
        $jumlahUlasan = $profil->ulasan->count();

        // Tab aktif (dari query param atau default)
        $tabAktif = $request->get('tab', 'informasi-pribadi');

        return view('advokat.profil', compact(
            'user', 'profil', 'persen',
            'daftarBidang', 'selectedBidangIds',
            'daftarWilayah', 'selectedWilayahIds',
            'dokumenMap', 'ratingAvg', 'jumlahUlasan',
            'tabAktif'
        ));
    }

    /**
     * Simpan perubahan Tab Informasi Pribadi.
     */
    public function updateInfoPribadi(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'no_hp'      => ['required', 'string', 'max:30'],
            'wilayah_id' => ['nullable', 'exists:wilayah,id'],
        ], [
            'nama.required'  => 'Nama lengkap beserta gelar wajib diisi.',
            'no_hp.required' => 'Nomor HP/WhatsApp aktif wajib diisi.',
        ]);

        $user->update([
            'nama'       => $validated['nama'],
            'no_hp'      => $validated['no_hp'],
            'wilayah_id' => $validated['wilayah_id'] ?? null,
        ]);

        return redirect()->route('advokat.profil.edit', ['tab' => 'informasi-pribadi'])
            ->with('success', 'Informasi pribadi berhasil diperbarui.');
    }

    /**
     * Simpan perubahan Tab Data Profesional.
     */
    public function updateDataProfesional(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'no_kta'              => ['required', 'string', 'max:100'],
            'no_bas'              => ['required', 'string', 'max:100'],
            'pengalaman'          => ['required', 'integer', 'min:0', 'max:50'],
            'biografi'            => ['nullable', 'string', 'max:500'],
            'nama_organisasi'     => ['nullable', 'string', 'max:200'],
        ], [
            'no_kta.required'     => 'Nomor KTA (Kartu Tanda Anggota) wajib diisi.',
            'no_bas.required'     => 'Nomor Berita Acara Sumpah wajib diisi.',
            'pengalaman.required' => 'Tahun pengalaman wajib diisi.',
            'pengalaman.integer'  => 'Pengalaman harus berupa angka tahun.',
        ]);

        $user->profilAdvokat()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'no_kta'          => $validated['no_kta'],
                'no_lisensi'      => $validated['no_kta'],
                'no_bas'          => $validated['no_bas'],
                'pengalaman'      => $validated['pengalaman'] . ' tahun',
                'biografi'        => $validated['biografi'] ?? null,
                'nama_organisasi' => $validated['nama_organisasi'] ?? null,
            ]
        );

        return redirect()->route('advokat.profil.edit', ['tab' => 'data-profesional'])
            ->with('success', 'Data profesional berhasil disimpan.');
    }

    /**
     * Simpan perubahan Tab Bidang & Wilayah.
     */
    public function updateBidangWilayah(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'bidang_hukum_ids'   => ['required', 'array', 'min:1'],
            'bidang_hukum_ids.*' => ['exists:bidang_hukum,id'],
            'wilayah_ids'        => ['required', 'array', 'min:1'],
            'wilayah_ids.*'      => ['exists:wilayah,id'],
        ], [
            'bidang_hukum_ids.required' => 'Pilih minimal satu bidang keahlian hukum.',
            'wilayah_ids.required'      => 'Pilih minimal satu wilayah praktik.',
        ]);

        $profil = $user->profilAdvokat()->firstOrCreate(
            ['user_id' => $user->id],
            ['status_verifikasi' => 'belum_diajukan', 'ketersediaan' => true]
        );

        $profil->bidangHukum()->sync($validated['bidang_hukum_ids']);
        $profil->wilayahPraktik()->sync($validated['wilayah_ids']);

        return redirect()->route('advokat.profil.edit', ['tab' => 'bidang-wilayah'])
            ->with('success', 'Bidang keahlian dan wilayah praktik berhasil diperbarui.');
    }

    /**
     * Upload dokumen verifikasi (KTA / BAS / Ijazah).
     */
    public function uploadDokumen(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'jenis'  => ['required', 'in:KTA,BAS,Ijazah'],
            'file'   => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'file.required' => 'File dokumen wajib dipilih.',
            'file.mimes'    => 'Format file harus PDF, JPG, atau PNG.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $profil = $user->profilAdvokat()->firstOrCreate(
            ['user_id' => $user->id],
            ['status_verifikasi' => 'belum_diajukan', 'ketersediaan' => true]
        );

        // Hapus file lama jika ada
        $existing = DokumenAdvokat::where('profil_advokat_id', $profil->id)
            ->where('jenis', $validated['jenis'])
            ->first();

        if ($existing && Storage::disk('private')->exists($existing->file)) {
            Storage::disk('private')->delete($existing->file);
        }

        $path = $request->file('file')->store(
            'dokumen/advokat/' . $user->id,
            'private'
        );

        DokumenAdvokat::updateOrCreate(
            ['profil_advokat_id' => $profil->id, 'jenis' => $validated['jenis']],
            ['file' => $path, 'status' => 'pending', 'catatan_admin' => null]
        );

        return redirect()->route('advokat.profil.edit', ['tab' => 'dokumen-verifikasi'])
            ->with('success', 'Dokumen ' . $validated['jenis'] . ' berhasil diunggah.');
    }

    /**
     * Kirim profil untuk diverifikasi admin.
     */
    public function kirimVerifikasi(Request $request): RedirectResponse
    {
        $user   = $request->user();
        $profil = $user->profilAdvokat;

        if (!$profil || !$profil->siapDikirim()) {
            return redirect()->route('advokat.profil.edit', ['tab' => 'dokumen-verifikasi'])
                ->with('error', 'Lengkapi semua data dan dokumen terlebih dahulu sebelum mengirim verifikasi.');
        }

        if (!in_array($profil->status_verifikasi, ['belum_diajukan', 'ditolak'])) {
            return redirect()->route('advokat.profil.edit')
                ->with('error', 'Status verifikasi tidak memungkinkan pengiriman ulang.');
        }

        $profil->update(['status_verifikasi' => 'pending']);

        return redirect()->route('advokat.profil.edit', ['tab' => 'dokumen-verifikasi'])
            ->with('success', 'Profil berhasil dikirim! Admin akan memeriksa dokumen Anda. Anda akan menerima notifikasi hasilnya.');
    }

    /**
     * Simpan Tarif & Ketersediaan.
     */
    public function updateTarifKetersediaan(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->merge(['ketersediaan' => $request->boolean('ketersediaan')]);

        $validated = $request->validate([
            'tarif_konsultasi' => ['required', 'numeric', 'min:0'],
            'ketersediaan'     => ['required', 'boolean'],
            'catatan_pembayaran' => ['nullable', 'string', 'max:500'],
        ], [
            'tarif_konsultasi.required' => 'Tarif konsultasi wajib diisi.',
            'tarif_konsultasi.numeric'  => 'Tarif harus berupa angka.',
        ]);

        $user->profilAdvokat()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'tarif_konsultasi' => $validated['tarif_konsultasi'],
                'ketersediaan'     => $validated['ketersediaan'],
            ]
        );

        return redirect()->route('advokat.profil.edit', ['tab' => 'tarif-ketersediaan'])
            ->with('success', 'Tarif dan ketersediaan berhasil diperbarui.');
    }

    /**
     * Simpan Kuota Pro Bono.
     */
    public function updateKuota(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'kuota_probono' => ['required', 'integer', 'min:0', 'max:10'],
        ], [
            'kuota_probono.required' => 'Kuota pro bono wajib diisi.',
            'kuota_probono.integer'  => 'Kuota harus berupa angka bulat.',
            'kuota_probono.max'      => 'Kuota maksimal 10 kasus per bulan.',
        ]);

        $user->profilAdvokat()->updateOrCreate(
            ['user_id' => $user->id],
            ['kuota_probono' => $validated['kuota_probono']]
        );

        return redirect()->route('advokat.profil.edit', ['tab' => 'kuota-probono'])
            ->with('success', 'Kuota pro bono berhasil diperbarui.');
    }

    /**
     * Ganti password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password'      => ['required', 'string'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required'         => 'Kata sandi baru wajib diisi.',
            'password.min'              => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak sesuai.'])
                ->withInput()->with('tab_active', 'keamanan-akun');
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect()->route('advokat.profil.edit', ['tab' => 'keamanan-akun'])
            ->with('success', 'Kata sandi berhasil diperbarui.');
    }

    /**
     * (Lama) Update profil lengkap sekaligus — tetap dipertahankan untuk kompatibilitas.
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
            'bidang_hukum_ids'   => ['required', 'array', 'min:1'],
            'bidang_hukum_ids.*' => ['exists:bidang_hukum,id'],
            'wilayah_ids'        => ['nullable', 'array'],
            'wilayah_ids.*'      => ['exists:wilayah,id'],
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

        $profil->bidangHukum()->sync($validated['bidang_hukum_ids']);
        $profil->wilayahPraktik()->sync($validated['wilayah_ids'] ?? []);

        return redirect()->route('advokat.profil.edit')
            ->with('success', 'Profil profesional berhasil diperbarui!');
    }
}
