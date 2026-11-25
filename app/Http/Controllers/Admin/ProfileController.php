<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumenAdvokat;
use App\Models\ProfilAdvokat;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Pengelolaan Profil & Pusat Kendali Administrator.
     * Sesuai Proposal Kelompok A_Sdg 16: FR-01, FR-02, FR-03, FR-04, FR-15, FR-16.
     */
    public function edit(Request $request): View
    {
        $user = $request->user() ?? auth()->user();

        // Hitung inisial nama
        $namaUser   = $user->nama ?? $user->name ?? 'Administrator';
        $bagianNama = preg_split('/\s+/', trim($namaUser)) ?: ['A', 'D'];
        $inisial    = mb_strtoupper(
            mb_substr($bagianNama[0] ?? 'A', 0, 1) .
            mb_substr($bagianNama[1] ?? 'D', 0, 1)
        );

        $tabAktif = $request->get('tab', 'informasi-pribadi');

        // Statistik operasional platform (Proposal Bab 3 & 4)
        $totalAdvokatPending = ProfilAdvokat::where('status_verifikasi', 'pending')->count();
        $totalAdvokatAktif   = ProfilAdvokat::whereIn('status_verifikasi', ['disetujui', 'terverifikasi'])->count();
        $totalAdvokatDitolak = ProfilAdvokat::where('status_verifikasi', 'ditolak')->count();

        $totalProBonoPending = Schema::hasTable('verifikasi_ekonomi')
            ? DB::table('verifikasi_ekonomi')->where('status', 'pending')->count()
            : 0;

        $totalMasyarakat = User::where('role', 'masyarakat')->count();
        $totalAdvokat    = User::where('role', 'advokat')->count();
        $totalUser       = User::count();

        // Antrean verifikasi advokat (Use Case C Hal 25-27 Proposal)
        $antreanAdvokat = ProfilAdvokat::with(['user.wilayah', 'dokumen', 'bidangHukum', 'wilayahPraktik'])
            ->where('status_verifikasi', 'pending')
            ->latest('updated_at')
            ->get();

        // Riwayat verifikasi advokat (yang sudah diproses)
        $riwayatVerifikasi = ProfilAdvokat::with(['user.wilayah', 'dokumen', 'bidangHukum'])
            ->whereIn('status_verifikasi', ['disetujui', 'terverifikasi', 'ditolak'])
            ->latest('updated_at')
            ->take(15)
            ->get();

        // Antrean verifikasi ekonomi masyarakat untuk akses Pro Bono (FR-04, PBI-09)
        $antreanProBono = collect();
        $riwayatProBono = collect();
        if (Schema::hasTable('verifikasi_ekonomi')) {
            $antreanProBono = DB::table('verifikasi_ekonomi')
                ->join('users', 'verifikasi_ekonomi.user_id', '=', 'users.id')
                ->select(
                    'verifikasi_ekonomi.*',
                    'users.nama as nama_user',
                    'users.email as email_user',
                    'users.no_hp as no_hp_user'
                )
                ->where('verifikasi_ekonomi.status', 'pending')
                ->latest('verifikasi_ekonomi.created_at')
                ->get();

            $riwayatProBono = DB::table('verifikasi_ekonomi')
                ->join('users', 'verifikasi_ekonomi.user_id', '=', 'users.id')
                ->select(
                    'verifikasi_ekonomi.*',
                    'users.nama as nama_user',
                    'users.email as email_user'
                )
                ->whereIn('verifikasi_ekonomi.status', ['disetujui', 'pro_bono', 'ditolak'])
                ->latest('verifikasi_ekonomi.updated_at')
                ->take(15)
                ->get();
        }

        // Daftar pengguna untuk ringkasan moderasi akun (FR-15)
        $daftarPengguna = User::with('wilayah')->latest('id')->paginate(10);

        $daftarWilayah = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])
            ->orderBy('tingkat')
            ->orderBy('nama')
            ->get();

        return view('admin.profil', compact(
            'user',
            'inisial',
            'tabAktif',
            'totalAdvokatPending',
            'totalAdvokatAktif',
            'totalAdvokatDitolak',
            'totalProBonoPending',
            'totalMasyarakat',
            'totalAdvokat',
            'totalUser',
            'antreanAdvokat',
            'riwayatVerifikasi',
            'antreanProBono',
            'riwayatProBono',
            'daftarPengguna',
            'daftarWilayah'
        ));
    }

    /**
     * Memperbarui informasi data diri dan kontak administrator.
     */
    public function updateInfo(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nama'        => ['required', 'string', 'max:255'],
            'no_hp'       => ['required', 'string', 'max:30'],
            'alamat'      => ['nullable', 'string', 'max:500'],
            'wilayah_id'  => ['nullable', 'exists:wilayah,id'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'nama.required'     => 'Nama administrator wajib diisi.',
            'no_hp.required'    => 'Nomor kontak / WhatsApp wajib diisi.',
            'foto_profil.image' => 'File foto harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            'foto_profil.max'   => 'Ukuran foto maksimal 2 MB.',
        ]);

        $updateData = [
            'nama'       => $validated['nama'],
            'no_hp'      => $validated['no_hp'],
            'alamat'     => $validated['alamat'] ?? null,
            'wilayah_id' => $validated['wilayah_id'] ?? null,
        ];

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $updateData['foto_profil'] = $request->file('foto_profil')->store('avatars', 'public');
        }

        $user->update($updateData);

        return redirect()->route('admin.profil.edit', ['tab' => 'informasi-pribadi'])
            ->with('success', 'Informasi profil administrator berhasil diperbarui.');
    }

    /**
     * Memperbarui foto profil administrator secara instan.
     */
    public function updateFoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'foto_profil' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'foto_profil.required' => 'Silakan pilih berkas gambar terlebih dahulu.',
            'foto_profil.image'    => 'Berkas harus berupa gambar.',
            'foto_profil.mimes'    => 'Format gambar harus JPG, PNG, atau WebP.',
            'foto_profil.max'      => 'Ukuran gambar maksimal 2 MB.',
        ]);

        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $path = $request->file('foto_profil')->store('avatars', 'public');
        $user->update(['foto_profil' => $path]);

        return back()->with('success', 'Foto profil administrator berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil administrator.
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
     * Memperbarui kata sandi akun administrator.
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

        return redirect()->route('admin.profil.edit', ['tab' => 'keamanan'])
            ->with('success', 'Kata sandi akun administrator berhasil diperbarui.');
    }

    /**
     * Setujui Verifikasi Advokat (Use Case C Hal 26 Proposal: Standard in Process Langkah 6-8).
     * Mengubah status profil advokat menjadi 'disetujui' dan memunculkan badge resmi di direktori publik.
     */
    public function setujuiAdvokat(Request $request, int $id): RedirectResponse
    {
        $profil = ProfilAdvokat::with('user')->findOrFail($id);

        $profil->update([
            'status_verifikasi'  => 'disetujui',
            'catatan_verifikasi' => null,
            'ketersediaan'       => true,
        ]);

        // Tandai seluruh dokumen pendukung sebagai valid
        DokumenAdvokat::where('profil_advokat_id', $profil->id)
            ->update([
                'status'        => 'valid',
                'catatan_admin' => 'Dokumen sah dan diverifikasi oleh Administrator pada ' . now()->translatedFormat('d F Y H:i'),
            ]);

        $namaAdvokat = $profil->user->nama ?? $profil->user->name ?? 'Advokat';

        return redirect()->route('admin.profil.edit', ['tab' => 'verifikasi-advokat'])
            ->with('success', "Verifikasi berhasil disetujui! Akun {$namaAdvokat} kini resmi berstatus Terverifikasi dan langsung tampil di Direktori Advokat Konsulaw.");
    }

    /**
     * Tolak Verifikasi Advokat (Use Case C Hal 26 Proposal: Alternative Processes Langkah 6-9).
     * Mewajibkan Admin mengisi alasan penolakan pada form modal dan mengirimkan perbaikan ke akun advokat.
     */
    public function tolakAdvokat(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan verifikasi wajib diisi untuk panduan perbaikan advokat.',
            'alasan_penolakan.min'      => 'Alasan penolakan minimal 5 karakter.',
            'alasan_penolakan.max'      => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        $profil = ProfilAdvokat::with('user')->findOrFail($id);

        $profil->update([
            'status_verifikasi'  => 'ditolak',
            'catatan_verifikasi' => $validated['alasan_penolakan'],
        ]);

        DokumenAdvokat::where('profil_advokat_id', $profil->id)
            ->update([
                'status'        => 'tidak_valid',
                'catatan_admin' => $validated['alasan_penolakan'],
            ]);

        $namaAdvokat = $profil->user->nama ?? $profil->user->name ?? 'Advokat';

        return redirect()->route('admin.profil.edit', ['tab' => 'verifikasi-advokat'])
            ->with('success', "Pengajuan verifikasi {$namaAdvokat} telah ditolak dengan catatan alasan perbaikan: \"{$validated['alasan_penolakan']}\".");
    }

    /**
     * Unduh / Pratinjau Dokumen Kualifikasi Advokat dari penyimpanan privat (TR-04 / FR-03).
     */
    public function unduhDokumen(int $id): BinaryFileResponse|RedirectResponse
    {
        $dokumen = DokumenAdvokat::findOrFail($id);

        $disk = Storage::disk('private')->exists($dokumen->file)
            ? Storage::disk('private')
            : (Storage::disk('local')->exists($dokumen->file) ? Storage::disk('local') : null);

        if (! $disk) {
            return back()->with('error', 'Berkas dokumen tidak ditemukan di media penyimpanan server.');
        }

        $fullPath = $disk->path($dokumen->file);
        $ext      = pathinfo($fullPath, PATHINFO_EXTENSION);
        $filename = "Dokumen_{$dokumen->jenis}_{$dokumen->profil_advokat_id}.{$ext}";

        return response()->download($fullPath, $filename);
    }

    /**
     * Setujui Verifikasi Status Ekonomi Masyarakat untuk Layanan Pro Bono (FR-04, PBI-09).
     */
    public function setujuiProBono(Request $request, int $id): RedirectResponse
    {
        if (! Schema::hasTable('verifikasi_ekonomi')) {
            return back()->with('error', 'Tabel verifikasi ekonomi belum tersedia.');
        }

        $verif = DB::table('verifikasi_ekonomi')->where('id', $id)->first();
        if (! $verif) {
            return back()->with('error', 'Data pengajuan pro bono tidak ditemukan.');
        }

        DB::table('verifikasi_ekonomi')->where('id', $id)->update([
            'status'         => 'disetujui',
            'diperiksa_oleh' => $request->user()->id,
            'updated_at'     => now(),
        ]);

        // Perbarui status ekonomi akun masyarakat menjadi 'pro_bono'
        User::where('id', $verif->user_id)->update(['status_ekonomi' => 'pro_bono']);

        return redirect()->route('admin.profil.edit', ['tab' => 'verifikasi-probono'])
            ->with('success', 'Status ekonomi masyarakat berhasil diverifikasi dan disetujui untuk akses Layanan Pro Bono!');
    }

    /**
     * Tolak Verifikasi Status Ekonomi Masyarakat (FR-04).
     */
    public function tolakProBono(Request $request, int $id): RedirectResponse
    {
        if (! Schema::hasTable('verifikasi_ekonomi')) {
            return back()->with('error', 'Tabel verifikasi ekonomi belum tersedia.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['required', 'string', 'max:500'],
        ], [
            'catatan_admin.required' => 'Catatan alasan penolakan wajib diisi.',
        ]);

        $verif = DB::table('verifikasi_ekonomi')->where('id', $id)->first();
        if (! $verif) {
            return back()->with('error', 'Data pengajuan tidak ditemukan.');
        }

        DB::table('verifikasi_ekonomi')->where('id', $id)->update([
            'status'         => 'ditolak',
            'catatan_admin'  => $validated['catatan_admin'],
            'diperiksa_oleh' => $request->user()->id,
            'updated_at'     => now(),
        ]);

        User::where('id', $verif->user_id)->update(['status_ekonomi' => 'reguler']);

        return redirect()->route('admin.profil.edit', ['tab' => 'verifikasi-probono'])
            ->with('success', 'Pengajuan verifikasi status ekonomi telah ditolak.');
    }
}
