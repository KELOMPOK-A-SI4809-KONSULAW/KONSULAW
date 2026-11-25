<?php

namespace App\Http\Controllers;

use App\Models\BidangHukum;
use App\Models\Konsultasi;
use App\Models\PesanKonsultasi;
use App\Models\ProfilAdvokat;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class KonsultasiController extends Controller
{
    /**
     * Menampilkan formulir pengajuan konsultasi (B2.1, B4A.3, B4A.4).
     * Mendukung 2 pintu masuk:
     * 1. Dari Tombol Utama Beranda/Dasbor (belum ada advokat) -> Langkah 1: Pilih Advokat
     * 2. Dari Kartu Advokat (?advokat={id}) -> Advokat sudah terpilih, langsung ke Langkah 2: Jenis Layanan
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->guest(route('login'))
                ->with('error', 'Silakan masuk ke akun Anda terlebih dahulu untuk mengajukan konsultasi hukum.');
        }

        $user = $request->user();

        // Hanya masyarakat yang dapat mengajukan konsultasi
        if ($user->role !== 'masyarakat') {
            return redirect()->route('home')
                ->with('error', 'Hanya akun Masyarakat yang dapat mengajukan permintaan konsultasi hukum.');
        }

        $advokatTerpilih = null;
        $advokatId = $request->query('advokat');

        if ($advokatId) {
            $advUser = User::with(['profilAdvokat.bidangHukum', 'profilAdvokat.wilayahPraktik', 'wilayah'])
                ->where('role', 'advokat')
                ->where('id', $advokatId)
                ->first();

            // Validasi kredensial advokat terpilih
            if ($advUser && $advUser->profilAdvokat &&
                in_array($advUser->profilAdvokat->status_verifikasi, ['disetujui', 'terverifikasi']) &&
                $advUser->profilAdvokat->ketersediaan) {

                // Cegah pengajuan duplikasi bila sudah ada konsultasi aktif
                $duplikat = Konsultasi::where('masyarakat_id', $user->id)
                    ->where('advokat_id', $advUser->id)
                    ->whereIn('status', ['menunggu_tanggapan', 'menunggu_pembayaran', 'berlangsung'])
                    ->first();

                if ($duplikat) {
                    return redirect()->route('konsultasi.show', $duplikat->id)
                        ->with('error', 'Anda sudah memiliki sesi konsultasi aktif dengan Advokat ' . ($advUser->nama ?? $advUser->name) . '. Silakan lanjutkan konsultasi yang sedang berjalan.');
                }

                $advokatTerpilih = $advUser;
            } else {
                return redirect()->route('konsultasi.create')
                    ->with('error', 'Advokat yang Anda pilih sedang tidak aktif atau tidak ditemukan. Silakan pilih advokat lain.');
            }
        }

        // Daftar advokat terverifikasi yang siap konsultasi (untuk Langkah 1)
        $daftarAdvokat = User::where('role', 'advokat')
            ->whereHas('profilAdvokat', function ($q) {
                $q->whereIn('status_verifikasi', ['disetujui', 'terverifikasi'])
                  ->where('ketersediaan', true);
            })
            ->with(['profilAdvokat.bidangHukum', 'profilAdvokat.wilayahPraktik', 'profilAdvokat.ulasan', 'wilayah'])
            ->get();

        $daftarBidang  = BidangHukum::orderBy('nama')->get();
        $daftarWilayah = Wilayah::whereIn('tingkat', ['kabupaten_kota', 'provinsi'])->orderBy('nama')->get();

        // Cek status ekonomi pengguna
        $statusEkonomi = $user->status_ekonomi ?? 'reguler';

        // Ambil draf pengisian dari session jika ada (B2.2)
        $drafKasus = session('draf_konsultasi', []);

        return view('konsultasi.ajukan', compact(
            'user',
            'advokatTerpilih',
            'daftarAdvokat',
            'daftarBidang',
            'daftarWilayah',
            'statusEkonomi',
            'drafKasus'
        ));
    }

    /**
     * Menyimpan permohonan konsultasi baru (B2.1, B2.6, B4A.4).
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->role !== 'masyarakat') {
            abort(403, 'Akses tidak diizinkan.');
        }

        $validated = $request->validate([
            'advokat_id'        => ['required', 'exists:users,id'],
            'bidang_id'         => ['required', 'exists:bidang_hukum,id'],
            'jenis_layanan'     => ['required', 'in:reguler,pro_bono'],
            'judul'             => ['required', 'string', 'max:255'],
            'kronologi'         => ['required', 'string', 'min:20'],
            'pertanyaan_hukum'  => ['nullable', 'string', 'max:2000'],
            'lampiran.*'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'advokat_id.required'    => 'Silakan pilih advokat yang dituju.',
            'bidang_id.required'     => 'Kategori bidang hukum wajib dipilih.',
            'jenis_layanan.required' => 'Pilih jenis layanan (Reguler atau Pro Bono).',
            'judul.required'         => 'Judul masalah hukum wajib diisi.',
            'kronologi.required'     => 'Kronologi kejadian perkara wajib diisi untuk bahan telaah advokat.',
            'kronologi.min'          => 'Kronologi kejadian minimal terdiri dari 20 karakter agar jelas dipahami.',
            'lampiran.*.mimes'       => 'Berkas lampiran harus berformat PDF, JPG, JPEG, atau PNG.',
            'lampiran.*.max'         => 'Ukuran masing-masing berkas lampiran maksimal 5 MB.',
        ]);

        $advokat = User::with('profilAdvokat')->findOrFail($validated['advokat_id']);
        $profilAdvokat = $advokat->profilAdvokat;

        if (! $profilAdvokat || ! in_array($profilAdvokat->status_verifikasi, ['disetujui', 'terverifikasi']) || ! $profilAdvokat->ketersediaan) {
            return back()->withInput()->with('error', 'Advokat ini saat ini sedang tidak menerima konsultasi baru.');
        }

        // Cek batasan 3 konsultasi menunggu pembayaran per pengguna (B3.5)
        if ($validated['jenis_layanan'] === 'reguler') {
            $totalMenungguBayar = Konsultasi::where('masyarakat_id', $user->id)
                ->where('status', 'menunggu_pembayaran')
                ->count();
            if ($totalMenungguBayar >= 3) {
                return back()->withInput()->with('error', 'Anda telah mencapai batas maksimal 3 konsultasi yang menunggu pembayaran. Silakan selesaikan pembayaran sebelumnya terlebih dahulu.');
            }
        }

        // Cek cabang Pro Bono (B2.2)
        if ($validated['jenis_layanan'] === 'pro_bono') {
            // Simpan draf ke session agar tidak hilang
            session()->put('draf_konsultasi', [
                'advokat_id'       => $validated['advokat_id'],
                'bidang_id'        => $validated['bidang_id'],
                'judul'            => $validated['judul'],
                'kronologi'        => $validated['kronologi'],
                'pertanyaan_hukum' => $validated['pertanyaan_hukum'] ?? null,
            ]);

            // Verifikasi status ekonomi
            if ($user->status_ekonomi !== 'pro_bono') {
                return redirect()->route('masyarakat.profil.edit', ['tab' => 'status-layanan'])
                    ->with('error', 'Untuk menggunakan jalur Pro Bono gratis, akun Anda harus terverifikasi status Pro Bono terlebih dahulu. Draf konsultasi Anda telah disimpan.');
            }

            // Cek kuota pro bono advokat
            if ($profilAdvokat->kuota_probono <= 0) {
                return back()->withInput()->with('error', 'Kuota Pro Bono advokat ini untuk bulan berjalan telah habis. Silakan pilih jalur Reguler atau ajukan ke advokat lain yang masih memiliki kuota.');
            }
        }

        // Cegah pengajuan ganda ke advokat yang sama jika masih ada konsultasi aktif
        $duplikat = Konsultasi::where('masyarakat_id', $user->id)
            ->where('advokat_id', $advokat->id)
            ->whereIn('status', ['menunggu_tanggapan', 'menunggu_pembayaran', 'berlangsung'])
            ->first();

        if ($duplikat) {
            return redirect()->route('konsultasi.show', $duplikat->id)
                ->with('error', 'Anda sudah memiliki permohonan konsultasi aktif dengan advokat ini.');
        }

        // Proses unggah berkas kasus opsional (maks 3 file)
        $lampiranPaths = [];
        if ($request->hasFile('lampiran')) {
            $files = array_slice($request->file('lampiran'), 0, 3);
            foreach ($files as $file) {
                $path = $file->store("dokumen/kasus/{$user->id}", 'private');
                $lampiranPaths[] = [
                    'nama_asli' => $file->getClientOriginalName(),
                    'path'      => $path,
                    'ukuran'    => $file->getSize(),
                    'ekstensi'  => $file->getClientOriginalExtension(),
                ];
            }
        }

        // Buat record konsultasi
        $konsultasi = Konsultasi::create([
            'masyarakat_id'    => $user->id,
            'advokat_id'       => $advokat->id,
            'bidang_id'        => $validated['bidang_id'],
            'jenis_layanan'    => $validated['jenis_layanan'],
            'judul'            => $validated['judul'],
            'kronologi'        => $validated['kronologi'],
            'pertanyaan_hukum' => $validated['pertanyaan_hukum'] ?? null,
            'lampiran'         => $lampiranPaths,
            'status'           => 'menunggu_tanggapan',
        ]);

        // Hapus draf di session jika berhasil
        session()->forget('draf_konsultasi');

        // Kirim notifikasi jika tabel notifikasi ada
        if (Schema::hasTable('notifikasi')) {
            DB::table('notifikasi')->insert([
                'user_id'    => $advokat->id,
                'tipe'       => 'konsultasi_masuk',
                'isi'        => "Permintaan konsultasi baru dari {$user->nama} ({$validated['judul']})",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('konsultasi.show', $konsultasi->id)
            ->with('success', 'Permintaan konsultasi Anda berhasil dikirim ke advokat dan sedang menunggu tanggapan.');
    }

    /**
     * Menampilkan rincian konsultasi dan status proses (B2.3, B2.4).
     */
    public function show(Request $request, int $id): View|RedirectResponse
    {
        $user = $request->user();

        $konsultasi = Konsultasi::with(['masyarakat.wilayah', 'advokat.profilAdvokat', 'bidangHukum', 'pembayaran', 'ulasan'])
            ->findOrFail($id);

        // Otorisasi: Hanya pemilik masyarakat, advokat terkait, atau admin yang berhak melihat
        if ($user->id !== $konsultasi->masyarakat_id &&
            $user->id !== $konsultasi->advokat_id &&
            $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak untuk mengakses data konsultasi ini.');
        }

        return view('konsultasi.show', compact('konsultasi', 'user'));
    }

    /**
     * Membatalkan konsultasi sebelum diterima advokat atau sebelum dibayar.
     */
    public function batalkan(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::findOrFail($id);

        if ($user->id !== $konsultasi->masyarakat_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if (! in_array($konsultasi->status, ['menunggu_tanggapan', 'menunggu_pembayaran'])) {
            return back()->with('error', 'Konsultasi yang sedang berlangsung atau sudah selesai tidak dapat dibatalkan.');
        }

        $konsultasi->update(['status' => 'dibatalkan']);

        return redirect()->route('konsultasi.show', $konsultasi->id)
            ->with('success', 'Konsultasi berhasil dibatalkan.');
    }

    /**
     * Halaman Ruang Chat Konsultasi Dua Arah (B2.5, FR-11).
     * Aturan Inti: Hanya dapat diakses saat status 'berlangsung' (atau 'selesai' sebagai read-only).
     */
    public function chat(Request $request, int $id): View|RedirectResponse
    {
        $user = $request->user();

        $konsultasi = Konsultasi::with(['masyarakat', 'advokat.profilAdvokat', 'pesan.pengirim', 'ulasan'])
            ->findOrFail($id);

        // Otorisasi akses chat (FR-16)
        if ($user->id !== $konsultasi->masyarakat_id && $user->id !== $konsultasi->advokat_id) {
            abort(403, 'Ruang chat ini bersifat privat dan hanya dapat diakses oleh pihak yang terlibat.');
        }

        // Cek gerbang chat: Hanya bisa dibuka jika status berlangsung atau selesai
        if (! in_array($konsultasi->status, ['berlangsung', 'selesai'])) {
            $pesanAlasan = match ($konsultasi->status) {
                'menunggu_tanggapan'  => 'Ruang chat baru akan dibuka setelah advokat menyetujui permintaan konsultasi.',
                'menunggu_pembayaran' => 'Ruang chat baru akan dibuka setelah pembayaran sesi konsultasi terkonfirmasi.',
                'ditolak'             => 'Permintaan konsultasi ini ditolak oleh advokat.',
                'kedaluwarsa'         => 'Permintaan konsultasi ini telah kedaluwarsa.',
                default               => 'Sesi chat konsultasi belum dapat diakses.',
            };

            return redirect()->route('konsultasi.show', $konsultasi->id)
                ->with('error', $pesanAlasan);
        }

        $isReadOnly = ($konsultasi->status === 'selesai');

        return view('konsultasi.chat', compact('konsultasi', 'user', 'isReadOnly'));
    }

    /**
     * Mengirim pesan dalam sesi chat konsultasi (FR-11).
     */
    public function kirimPesan(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::findOrFail($id);

        if ($user->id !== $konsultasi->masyarakat_id && $user->id !== $konsultasi->advokat_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Jika selesai, chat menjadi read-only (B2.5)
        if ($konsultasi->status !== 'berlangsung') {
            return back()->with('error', 'Konsultasi ini telah selesai atau tidak aktif. Pesan baru tidak dapat dikirim.');
        }

        $validated = $request->validate([
            'isi'      => ['required_without:lampiran', 'nullable', 'string', 'max:3000'],
            'lampiran' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'isi.required_without' => 'Pesan teks atau berkas lampiran wajib diisi.',
            'lampiran.mimes'       => 'Format berkas lampiran harus PDF, JPG, JPEG, atau PNG.',
            'lampiran.max'         => 'Ukuran lampiran maksimal 5 MB.',
        ]);

        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $lampiranPath = $request->file('lampiran')->store("dokumen/chat/{$konsultasi->id}", 'private');
        }

        PesanKonsultasi::create([
            'konsultasi_id' => $konsultasi->id,
            'pengirim_id'   => $user->id,
            'isi'           => $validated['isi'] ?? '',
            'lampiran'      => $lampiranPath,
        ]);

        return back();
    }

    /**
     * Mengakhiri sesi konsultasi (advokat atau masyarakat) -> Status menjadi 'selesai' (B2.3, B2.4).
     */
    public function selesaikan(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::findOrFail($id);

        if ($user->id !== $konsultasi->masyarakat_id && $user->id !== $konsultasi->advokat_id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($konsultasi->status !== 'berlangsung') {
            return back()->with('error', 'Hanya konsultasi yang sedang berlangsung yang dapat diselesaikan.');
        }

        $konsultasi->update(['status' => 'selesai']);

        return redirect()->route('konsultasi.show', $konsultasi->id)
            ->with('success', 'Sesi konsultasi hukum telah resmi diselesaikan. Terima kasih atas kerja sama Anda.');
    }

    /**
     * Memberi rating dan ulasan setelah konsultasi selesai (FR-13, B4.4).
     */
    public function simpanUlasan(Request $request, int $id): RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::findOrFail($id);

        if ($user->id !== $konsultasi->masyarakat_id) {
            abort(403, 'Hanya pemohon masyarakat yang dapat memberikan penilaian ulasan.');
        }

        if ($konsultasi->status !== 'selesai') {
            return back()->with('error', 'Penilaian hanya dapat diberikan setelah sesi konsultasi dinyatakan selesai.');
        }

        // Cek apakah sudah pernah mengulas
        if (Ulasan::where('konsultasi_id', $konsultasi->id)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk konsultasi ini.');
        }

        $validated = $request->validate([
            'rating'   => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Nilai rating bintang 1 sampai 5 wajib dipilih.',
            'rating.between'  => 'Rating harus bernilai antara 1 sampai 5 bintang.',
        ]);

        Ulasan::create([
            'konsultasi_id'   => $konsultasi->id,
            'masyarakat_id'   => $user->id,
            'advokat_id'      => $konsultasi->advokat_id,
            'rating'          => $validated['rating'],
            'komentar'        => $validated['komentar'] ?? null,
            'status_moderasi' => 'disetujui',
        ]);

        return redirect()->route('konsultasi.show', $konsultasi->id)
            ->with('success', 'Terima kasih! Ulasan dan rating Anda telah berhasil disimpan.');
    }

    /**
     * Mengunduh berkas lampiran kasus secara aman dari storage privat (FR-16).
     */
    public function unduhLampiran(Request $request, int $id, int $index): BinaryFileResponse|RedirectResponse
    {
        $user = $request->user();
        $konsultasi = Konsultasi::findOrFail($id);

        // Hanya pemilik masyarakat, advokat tujuan, atau admin
        if ($user->id !== $konsultasi->masyarakat_id &&
            $user->id !== $konsultasi->advokat_id &&
            $user->role !== 'admin') {
            abort(403, 'Akses berkas kasus ditolak.');
        }

        $lampiran = $konsultasi->lampiran ?? [];
        if (! isset($lampiran[$index])) {
            return back()->with('error', 'Berkas lampiran tidak ditemukan.');
        }

        $item = $lampiran[$index];
        $disk = Storage::disk('private')->exists($item['path'])
            ? Storage::disk('private')
            : (Storage::disk('local')->exists($item['path']) ? Storage::disk('local') : null);

        if (! $disk) {
            return back()->with('error', 'Berkas fisik tidak ditemukan di server.');
        }

        return response()->download($disk->path($item['path']), $item['nama_asli'] ?? 'Lampiran_Kasus');
    }
}
