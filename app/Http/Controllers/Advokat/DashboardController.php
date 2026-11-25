<?php

namespace App\Http\Controllers\Advokat;

use App\Http\Controllers\Controller;
use App\Models\Konsultasi;
use App\Models\Pembayaran;
use App\Models\ProfilAdvokat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan dasbor advokat, antrean permintaan masuk, dan pengaturan ketersediaan (B2.3, B2.4).
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

        $totalKonsultasi   = 0;
        $konsultasiAktif   = 0;
        $konsultasiProBono = 0;
        $konsultasiSelesai = 0;

        $antreanMasuk            = collect();
        $konsultasiBerlangsung   = collect();
        $konsultasiMenungguBayar = collect();
        $riwayatKonsultasi       = collect();

        if (Schema::hasTable('konsultasi')) {
            $totalKonsultasi   = Konsultasi::where('advokat_id', $user->id)->count();
            $konsultasiAktif   = Konsultasi::where('advokat_id', $user->id)->whereIn('status', ['menunggu_tanggapan', 'menunggu_pembayaran', 'berlangsung', 'diproses', 'diajukan'])->count();
            $konsultasiProBono = Konsultasi::where('advokat_id', $user->id)->where('jenis_layanan', 'pro_bono')->count();
            $konsultasiSelesai = Konsultasi::where('advokat_id', $user->id)->where('status', 'selesai')->count();

            // Antrean Permintaan Konsultasi Masuk (Menunggu Keputusan Advokat)
            $antreanMasuk = Konsultasi::with(['masyarakat.wilayah', 'bidangHukum'])
                ->where('advokat_id', $user->id)
                ->whereIn('status', ['menunggu_tanggapan', 'diajukan'])
                ->latest('created_at')
                ->get();

            // Sesi Konsultasi Berlangsung (Chat Aktif)
            $konsultasiBerlangsung = Konsultasi::with(['masyarakat', 'bidangHukum', 'pesan'])
                ->where('advokat_id', $user->id)
                ->whereIn('status', ['berlangsung', 'diproses'])
                ->latest('updated_at')
                ->get();

            // Menunggu Pembayaran dari Klien (Jalur Reguler)
            $konsultasiMenungguBayar = Konsultasi::with(['masyarakat', 'bidangHukum', 'pembayaran'])
                ->where('advokat_id', $user->id)
                ->where('status', 'menunggu_pembayaran')
                ->latest('updated_at')
                ->get();

            // Riwayat Selesai & Ditolak
            $riwayatKonsultasi = Konsultasi::with(['masyarakat', 'bidangHukum', 'ulasan'])
                ->where('advokat_id', $user->id)
                ->whereIn('status', ['selesai', 'ditolak', 'dibatalkan', 'kedaluwarsa'])
                ->latest('updated_at')
                ->take(15)
                ->get();
        }

        return view('advokat.dashboard', compact(
            'user',
            'profil',
            'totalKonsultasi',
            'konsultasiAktif',
            'konsultasiProBono',
            'konsultasiSelesai',
            'antreanMasuk',
            'konsultasiBerlangsung',
            'konsultasiMenungguBayar',
            'riwayatKonsultasi'
        ));
    }

    /**
     * Menerima Permintaan Konsultasi dari Masyarakat (B2.3, B2.4).
     */
    public function terima(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::with('masyarakat')->findOrFail($id);

        if ($konsultasi->advokat_id !== $user->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (! in_array($konsultasi->status, ['menunggu_tanggapan', 'diajukan'])) {
            return back()->with('error', 'Permintaan ini telah diproses sebelumnya.');
        }

        $profil = $user->profilAdvokat;

        // 1. Jalur Pro Bono (B2.3)
        if ($konsultasi->jenis_layanan === 'pro_bono') {
            if ($profil->kuota_probono <= 0) {
                return back()->with('error', 'Sisa kuota Pro Bono Anda untuk bulan ini telah habis. Anda tidak dapat menyetujui pengajuan Pro Bono baru.');
            }

            // Kurangi kuota advokat 1 dan aktifkan chat langsung
            $profil->decrement('kuota_probono');
            $konsultasi->update(['status' => 'berlangsung']);

            return redirect()->route('konsultasi.chat', $konsultasi->id)
                ->with('success', 'Permintaan konsultasi Pro Bono diterima! Kuota bulanan Anda telah disesuaikan dan ruang chat telah dibuka.');
        }

        // 2. Jalur Reguler (B2.4)
        // Status menjadi 'menunggu_pembayaran', batas waktu bayar 24 jam
        $tarif = $profil->tarif_konsultasi ?? 0;

        if ($tarif <= 0) {
            $konsultasi->update(['status' => 'berlangsung']);
            return redirect()->route('konsultasi.chat', $konsultasi->id)
                ->with('success', 'Permintaan konsultasi diterima dan ruang chat telah aktif.');
        }

        $konsultasi->update([
            'status'      => 'menunggu_pembayaran',
            'batas_bayar' => now()->addHours(24),
        ]);

        // Buat record pembayaran
        Pembayaran::firstOrCreate(
            ['konsultasi_id' => $konsultasi->id],
            [
                'order_id' => 'KSL-' . $konsultasi->id . '-' . time(),
                'jumlah'   => $tarif,
                'status'   => 'pending',
            ]
        );

        return redirect()->route('advokat.dashboard')
            ->with('success', 'Permintaan konsultasi Reguler disetujui. Tagihan telah diteruskan ke masyarakat (batas waktu bayar 24 jam). Chat akan terbuka setelah pembayaran lunas.');
    }

    /**
     * Menolak Permintaan Konsultasi (B2.3, B2.4, Proposal Use Case B).
     */
    public function tolak(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::findOrFail($id);

        if ($konsultasi->advokat_id !== $user->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Mohon sertakan alasan penolakan agar masyarakat memahaminya.',
            'alasan_penolakan.min'      => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $konsultasi->update([
            'status'           => 'ditolak',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        // Untuk pro bono, kuota TIDAK berkurang jika ditolak (B2.3)
        return redirect()->route('advokat.dashboard')
            ->with('success', 'Permintaan konsultasi telah ditolak.');
    }

    /**
     * Memperbarui ketersediaan layanan dan kuota maksimal pro bono bulanan.
     */
    public function updateLayanan(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->merge([
            'ketersediaan' => $request->boolean('ketersediaan'),
        ]);

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

        $user->profilAdvokat()->updateOrCreate(
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
