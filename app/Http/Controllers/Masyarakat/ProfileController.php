<?php

namespace App\Http\Controllers\Masyarakat;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\User;
use App\Models\VerifikasiEkonomi;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir pengelolaan data diri dan dasbor masyarakat (B4).
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Hitung inisial
        $namaUser   = $user->nama ?? $user->name ?? 'Masyarakat';
        $bagianNama = preg_split('/\s+/', trim($namaUser)) ?: ['M', 'Y'];
        $inisial    = mb_strtoupper(
            mb_substr($bagianNama[0] ?? 'M', 0, 1) .
            mb_substr($bagianNama[1] ?? 'Y', 0, 1)
        );

        $tabAktif = $request->get('tab', 'informasi-pribadi');

        $wilayahList = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        // Riwayat Verifikasi Ekonomi Pro Bono Pengguna (B4.3)
        $verifikasiEkonomi = null;
        if (Schema::hasTable('verifikasi_ekonomi')) {
            $verifikasiEkonomi = VerifikasiEkonomi::where('user_id', $user->id)
                ->latest('id')
                ->first();
        }

        // Riwayat Konsultasi Saya (B4.4, FR-12)
        $daftarKonsultasi = collect();
        $totalBerjalan = 0;
        $totalSelesai  = 0;

        if (Schema::hasTable('konsultasi')) {
            $query = Konsultasi::with(['advokat.profilAdvokat', 'bidangHukum', 'pembayaran', 'ulasan'])
                ->where('masyarakat_id', $user->id);

            // Filter status jika ada
            if ($request->filled('status') && $request->status !== 'semua') {
                $query->where('status', $request->status);
            }

            $daftarKonsultasi = $query->latest('created_at')->get();

            $totalBerjalan = Konsultasi::where('masyarakat_id', $user->id)
                ->whereIn('status', ['menunggu_tanggapan', 'menunggu_pembayaran', 'berlangsung'])
                ->count();

            $totalSelesai = Konsultasi::where('masyarakat_id', $user->id)
                ->where('status', 'selesai')
                ->count();
        }

        // Cek apakah ada draf konsultasi yang tersimpan di session
        $adaDraf = session()->has('draf_konsultasi');

        return view('masyarakat.profil', compact(
            'user',
            'inisial',
            'tabAktif',
            'wilayahList',
            'verifikasiEkonomi',
            'daftarKonsultasi',
            'totalBerjalan',
            'totalSelesai',
            'adaDraf'
        ));
    }

    /**
     * Memperbarui informasi data diri masyarakat.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'nik'         => ['nullable', 'numeric', 'digits:16'],
            'no_hp'       => ['required', 'string', 'max:20'],
            'wilayah_id'  => ['nullable', 'exists:wilayah,id'],
            'alamat'      => ['nullable', 'string', 'max:500'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'nama.required'     => 'Nama lengkap wajib diisi.',
            'nik.digits'        => 'NIK harus berjumlah tepat 16 digit angka.',
            'nik.numeric'       => 'NIK hanya boleh berupa karakter angka.',
            'no_hp.required'    => 'Nomor handphone / WhatsApp wajib diisi.',
            'wilayah_id.exists' => 'Wilayah yang dipilih tidak valid.',
            'alamat.max'        => 'Alamat maksimal terdiri dari 500 karakter.',
            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'foto_profil.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        $updateData = [
            'nama'       => $validated['nama'],
            'nik'        => $validated['nik'] ?? null,
            'no_hp'      => $validated['no_hp'],
            'wilayah_id' => $validated['wilayah_id'] ?? null,
            'alamat'     => $validated['alamat'] ?? null,
        ];

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $updateData['foto_profil'] = $request->file('foto_profil')->store('avatars', 'public');
        }

        $user->update($updateData);

        return redirect()->route('masyarakat.profil.edit', ['tab' => 'informasi-pribadi'])
            ->with('success', 'Data profil Anda berhasil diperbarui!');
    }

    /**
     * Unggah foto profil instan.
     */
    public function updateFoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'foto_profil' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'foto_profil.required' => 'Silakan pilih berkas foto.',
            'foto_profil.image'    => 'Berkas harus berupa gambar.',
            'foto_profil.mimes'    => 'Format gambar harus JPG, PNG, atau WebP.',
            'foto_profil.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $path = $request->file('foto_profil')->store('avatars', 'public');
        $user->update(['foto_profil' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    /**
     * Hapus foto profil.
     */
    public function hapusFoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->update(['foto_profil' => null]);

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Mengajukan dokumen verifikasi ekonomi untuk akses Pro Bono (B4.3).
     */
    public function ajukanVerifikasiEkonomi(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'dokumen' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'dokumen.required' => 'Dokumen bukti ekonomi (SKTM / KIS / KKS) wajib diunggah.',
            'dokumen.mimes'    => 'Format berkas harus PDF, JPG, JPEG, atau PNG.',
            'dokumen.max'      => 'Ukuran berkas maksimal 5 MB.',
        ]);

        $path = $validated['dokumen']->store("verifikasi-ekonomi/{$user->id}", 'local');

        DB::transaction(function () use ($user, $path) {
            VerifikasiEkonomi::create([
                'user_id' => $user->id,
                'dokumen' => $path,
                'status'  => 'pending',
            ]);

            $user->update([
                'status_ekonomi' => 'pending',
            ]);
        });

        return redirect()->route('masyarakat.profil.edit', ['tab' => 'status-layanan'])
            ->with('success', 'Dokumen verifikasi berhasil dikirim. Tim administrator akan memeriksa permohonan Anda.');
    }

    /**
     * Memperbarui kata sandi pengguna masyarakat.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required'         => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required'                 => 'Kata sandi baru wajib diisi.',
            'password.min'                      => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('masyarakat.profil.edit', ['tab' => 'keamanan'])
            ->with('success', 'Kata sandi akun berhasil diperbarui.');
    }
}
