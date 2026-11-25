@extends('layouts.app')

@section('title', 'Dasbor Advokat — Konsulaw')

@php
    $namaAdvokat = $user->nama ?? $user->name ?? 'Advokat';
    $statusVerif = $profil->status_verifikasi ?? 'pending';
    $isVerif     = in_array($statusVerif, ['disetujui', 'terverifikasi']);
@endphp

@section('content')
<div class="min-h-[85vh] py-8 sm:py-12 bg-ivory">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 space-y-8">
        {{-- ============================================================ --}}
        {{-- HEADER DASBOR ADVOKAT --}}
        {{-- ============================================================ --}}
        <div class="rounded-[24px] border border-line bg-paper p-6 sm:p-8 shadow-card flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="flex items-start sm:items-center gap-4">
                {{-- Avatar --}}
                @if ($user->foto_profil)
                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $namaAdvokat }}" class="h-16 w-16 rounded-full object-cover shadow-btn ring-4 ring-accent-soft shrink-0">
                @else
                    @php
                        $parts = preg_split('/\s+/', trim($namaAdvokat)) ?: ['A', 'D'];
                        $inisialAdv = mb_strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . mb_substr($parts[1] ?? 'D', 0, 1));
                    @endphp
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-accent text-white font-display font-bold text-2xl shadow-btn ring-4 ring-accent-soft">
                        {{ $inisialAdv }}
                    </div>
                @endif

                <div class="space-y-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="font-display text-xl sm:text-2xl font-bold text-ink truncate">
                            {{ $namaAdvokat }}
                        </h1>
                        @if ($isVerif)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-bold text-success">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Status: {{ ucfirst(str_replace('_', ' ', $statusVerif)) }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs sm:text-sm text-ink-soft">
                        Kelola antrean permohonan konsultasi masuk, pantau sesi chat aktif, dan atur ketersediaan layanan Anda.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 shrink-0">
                {{-- Form Toggle Ketersediaan --}}
                <form action="{{ route('advokat.layanan.update') }}" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="ketersediaan" value="{{ $profil->ketersediaan ? '0' : '1' }}">
                    <button
                        type="submit"
                        class="rounded-full px-4 py-2 text-xs font-bold border transition flex items-center gap-2 shadow-2xs {{ $profil->ketersediaan ? 'bg-emerald-50 border-emerald-300 text-emerald-800 hover:bg-emerald-100' : 'bg-paper border-line text-ink-soft hover:text-ink' }}"
                        title="Klik untuk mengubah ketersediaan menerima klien baru"
                    >
                        <span class="h-2 w-2 rounded-full {{ $profil->ketersediaan ? 'bg-success animate-pulse' : 'bg-neutral-400' }}"></span>
                        {{ $profil->ketersediaan ? 'Menerima Konsultasi' : 'Sedang Ditutup' }}
                    </button>
                </form>

                <a
                    href="{{ route('advokat.profil.edit') }}"
                    class="rounded-full bg-paper border border-line hover:border-accent hover:text-accent px-4 py-2 text-xs font-bold text-ink transition shadow-2xs"
                >
                    Edit Profil &amp; Tarif &rarr;
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="rounded-[16px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 flex items-start gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Berhasil</p>
                    <p class="mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-[16px] bg-red-50 border border-red-200 p-4 text-xs sm:text-sm text-red-800 flex items-start gap-3">
                <svg class="h-5 w-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Perhatian</p>
                    <p class="mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Checklist Onboarding jika belum terverifikasi (README 3) --}}
        @if (! $isVerif)
            <div class="rounded-[20px] border border-amber-200 bg-amber-50/70 p-5 sm:p-6">
                <div class="flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-display text-sm sm:text-base font-bold text-amber-900">
                            Akun Anda Belum Dapat Menerima Permintaan Konsultasi
                        </h3>
                        <p class="text-xs text-amber-800 mt-0.5 leading-relaxed">
                            Profil advokat hanya akan ditayangkan di direktori pencarian publik setelah seluruh dokumen kredensial (Ijazah S.H., BAS, KTA) disetujui oleh tim verifikator admin.
                        </p>
                        <div class="mt-3">
                            <a href="{{ route('advokat.profil.edit', ['tab' => 'dokumen-verifikasi']) }}" class="inline-flex items-center gap-1.5 rounded-full bg-amber-800 text-white hover:bg-neutral-900 px-4 py-1.5 text-xs font-bold transition shadow-btn">
                                Buka Dokumen Verifikasi &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- 4 KARTU STATISTIK RINGKAS --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="rounded-[20px] border border-line bg-paper p-5 shadow-card">
                <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Antrean Masuk</span>
                <p class="font-display text-2xl sm:text-3xl font-bold text-ink mt-1">
                    {{ $antreanMasuk->count() }}
                </p>
                <p class="text-[11px] text-ink-soft mt-0.5">Menunggu respon Anda</p>
            </div>

            <div class="rounded-[20px] border border-line bg-paper p-5 shadow-card">
                <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Sesi Berlangsung</span>
                <p class="font-display text-2xl sm:text-3xl font-bold text-success mt-1">
                    {{ $konsultasiBerlangsung->count() }}
                </p>
                <p class="text-[11px] text-ink-soft mt-0.5">Chat aktif saat ini</p>
            </div>

            <div class="rounded-[20px] border border-line bg-paper p-5 shadow-card">
                <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Menunggu Pembayaran</span>
                <p class="font-display text-2xl sm:text-3xl font-bold text-accent mt-1">
                    {{ $konsultasiMenungguBayar->count() }}
                </p>
                <p class="text-[11px] text-ink-soft mt-0.5">Batas bayar klien 24 jam</p>
            </div>

            <div class="rounded-[20px] border border-line bg-paper p-5 shadow-card">
                <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Sisa Kuota Pro Bono</span>
                <p class="font-display text-2xl sm:text-3xl font-bold text-purple-900 mt-1">
                    {{ $profil->kuota_probono ?? 0 }}
                </p>
                <p class="text-[11px] text-ink-soft mt-0.5">Alokasi bulan ini</p>
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN 1: ANTREAN PERMINTAAN KONSULTASI MASUK (B2.3, B2.4) --}}
        {{-- ============================================================ --}}
        <div class="rounded-[24px] border border-line bg-paper p-6 sm:p-8 shadow-card">
            <div class="flex items-center justify-between border-b border-line pb-4 mb-6">
                <div>
                    <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                        Permintaan Konsultasi Masuk
                    </h2>
                    <p class="text-xs text-ink-soft mt-0.5">
                        Telaah duduk perkara sebelum memutuskan menerima atau menolak permohonan.
                    </p>
                </div>
                <span class="rounded-full bg-accent-soft text-accent-dark font-bold text-xs px-3 py-1">
                    {{ $antreanMasuk->count() }} Permintaan
                </span>
            </div>

            <div class="space-y-4">
                @forelse ($antreanMasuk as $item)
                    @php
                        $klienName = $item->masyarakat->nama ?? $item->masyarakat->name ?? 'Pemohon';
                    @endphp
                    <div class="rounded-[18px] border border-line bg-ivory/50 p-5 space-y-4 hover:border-accent/40 transition">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-paper border border-line px-2.5 py-0.5 text-xs font-semibold text-ink-soft">
                                        {{ $item->bidangHukum->nama ?? 'Kasus Hukum' }}
                                    </span>

                                    @if ($item->jenis_layanan === 'pro_bono')
                                        <span class="rounded-full bg-purple-50 border border-purple-200 px-2.5 py-0.5 text-xs font-bold text-purple-800">
                                            Pro Bono (Gratis)
                                        </span>
                                    @else
                                        <span class="rounded-full bg-accent-soft px-2.5 py-0.5 text-xs font-bold text-accent-dark">
                                            Reguler (Rp {{ number_format($profil->tarif_konsultasi ?? 0, 0, ',', '.') }})
                                        </span>
                                    @endif

                                    <span class="text-xs text-ink-soft">
                                        &bull; {{ $item->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h3 class="font-display text-base sm:text-lg font-bold text-ink">
                                    {{ $item->judul }}
                                </h3>

                                <p class="text-xs text-ink-soft">
                                    Pemohon: <strong class="text-ink">{{ $klienName }}</strong> ({{ $item->masyarakat->wilayah->nama ?? 'Domisili tidak diketahui' }})
                                </p>
                            </div>

                            {{-- Tombol Aksi Terima & Tolak --}}
                            <div class="flex items-center gap-2 shrink-0">
                                {{-- Tombol Terima --}}
                                <form action="{{ route('advokat.konsultasi.terima', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda bersedia menerima permohonan konsultasi ini?')">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="rounded-full bg-accent hover:bg-accent-dark px-4 py-2 text-xs font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95 flex items-center gap-1.5"
                                    >
                                        &check; Terima Permintaan
                                    </button>
                                </form>

                                {{-- Tombol Tolak (Buka Modal Alasan) --}}
                                <button
                                    type="button"
                                    onclick="bukaModalTolak({{ $item->id }}, '{{ addslashes($item->judul) }}')"
                                    class="rounded-full border border-line bg-paper hover:bg-red-50 hover:border-danger hover:text-danger px-3.5 py-2 text-xs font-bold text-ink-soft transition shadow-2xs"
                                >
                                    Tolak &times;
                                </button>
                            </div>
                        </div>

                        {{-- Ringkasan Kronologi Kasus --}}
                        <div class="rounded-[14px] bg-paper border border-line p-4 text-xs text-ink leading-relaxed">
                            <span class="block text-[10px] font-bold uppercase text-ink-soft tracking-wider mb-1">
                                Kronologi Pokok Perkara:
                            </span>
                            <p class="whitespace-pre-line">{{ $item->kronologi }}</p>

                            @if ($item->pertanyaan_hukum)
                                <div class="mt-2.5 pt-2.5 border-t border-line text-ink-soft">
                                    <strong class="text-ink">Pertanyaan Klien:</strong> "{{ $item->pertanyaan_hukum }}"
                                </div>
                            @endif

                            @if (!empty($item->lampiran) && count($item->lampiran) > 0)
                                <div class="mt-3 pt-2.5 border-t border-line flex flex-wrap items-center gap-2">
                                    <span class="text-[11px] text-ink-soft font-bold">Berkas Bukti Kasus:</span>
                                    @foreach ($item->lampiran as $lIdx => $lamp)
                                        <a
                                            href="{{ route('konsultasi.lampiran.unduh', [$item->id, $lIdx]) }}"
                                            class="rounded-full bg-ivory border border-line px-2.5 py-1 text-[11px] font-medium text-ink hover:text-accent hover:border-accent transition flex items-center gap-1"
                                            title="Unduh berkas bukti perkara"
                                        >
                                            <svg class="h-3 w-3 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                            </svg>
                                            {{ $lamp['nama_asli'] ?? 'Lampiran' }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-xs text-ink-soft">
                        <svg class="h-10 w-10 mx-auto text-ink-soft/40 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Saat ini tidak ada permohonan konsultasi baru yang menunggu tanggapan Anda.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN 2: KONSULTASI BERLANGSUNG (CHAT AKTIF) --}}
        {{-- ============================================================ --}}
        <div class="rounded-[24px] border border-line bg-paper p-6 sm:p-8 shadow-card">
            <div class="flex items-center justify-between border-b border-line pb-4 mb-6">
                <div>
                    <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                        Konsultasi Sedang Berlangsung
                    </h2>
                    <p class="text-xs text-ink-soft mt-0.5">
                        Ruang chat aktif untuk berdiskusi langsung dengan klien pencari keadilan.
                    </p>
                </div>
                <span class="rounded-full bg-emerald-50 text-success border border-emerald-200 font-bold text-xs px-3 py-1">
                    {{ $konsultasiBerlangsung->count() }} Aktif
                </span>
            </div>

            <div class="space-y-3">
                @forelse ($konsultasiBerlangsung as $aktif)
                    @php
                        $klienName = $aktif->masyarakat->nama ?? $aktif->masyarakat->name ?? 'Pemohon';
                    @endphp
                    <div class="rounded-[18px] border border-line bg-paper p-4.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:border-accent/40 transition">
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-success animate-pulse"></span>
                                <h3 class="font-display text-sm sm:text-base font-bold text-ink truncate">
                                    {{ $aktif->judul }}
                                </h3>
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $aktif->jenis_layanan === 'pro_bono' ? 'bg-purple-50 text-purple-800' : 'bg-accent-soft text-accent-dark' }}">
                                    {{ $aktif->jenis_layanan === 'pro_bono' ? 'Pro Bono' : 'Reguler' }}
                                </span>
                            </div>
                            <p class="text-xs text-ink-soft">
                                Klien: <strong class="text-ink">{{ $klienName }}</strong> &bull; Total pesan: {{ $aktif->pesan->count() }} pesan
                            </p>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <a
                                href="{{ route('konsultasi.chat', $aktif->id) }}"
                                class="rounded-full bg-accent hover:bg-accent-dark px-4 py-2 text-xs font-bold text-white shadow-btn transition flex items-center gap-1.5"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 5.942 5.942 0 0 0 .97-2.73A8.77 8.77 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                </svg>
                                Buka Ruang Chat &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-ink-soft">
                        Belum ada sesi konsultasi yang sedang aktif.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ============================================================ --}}
        {{-- BAGIAN 3: MENUNGGU PEMBAYARAN KLIEN --}}
        {{-- ============================================================ --}}
        @if ($konsultasiMenungguBayar->count() > 0)
            <div class="rounded-[24px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                <div class="border-b border-line pb-4 mb-4">
                    <h2 class="font-display text-base sm:text-lg font-bold text-ink">
                        Menunggu Pembayaran dari Klien (Jalur Reguler)
                    </h2>
                    <p class="text-xs text-ink-soft mt-0.5">
                        Klien memiliki waktu 24 jam untuk melunasi tagihan. Chat terbuka otomatis setelah pembayaran terverifikasi.
                    </p>
                </div>

                <div class="divide-y divide-line">
                    @foreach ($konsultasiMenungguBayar as $mb)
                        <div class="py-3 flex items-center justify-between gap-3 text-xs">
                            <div>
                                <h4 class="font-bold text-ink">{{ $mb->judul }}</h4>
                                <p class="text-ink-soft mt-0.5">
                                    Klien: {{ $mb->masyarakat->nama ?? $mb->masyarakat->name }} &bull; Disetujui: {{ $mb->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-ink block">Rp {{ number_format($mb->pembayaran?->jumlah ?? $profil->tarif_konsultasi, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-amber-800 font-semibold">Menunggu Pelunasan</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- BAGIAN 4: RIWAYAT KONSULTASI TERDAHULU --}}
        {{-- ============================================================ --}}
        @if ($riwayatKonsultasi->count() > 0)
            <div class="rounded-[24px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                <div class="border-b border-line pb-4 mb-4">
                    <h2 class="font-display text-base sm:text-lg font-bold text-ink">
                        Riwayat Konsultasi
                    </h2>
                </div>

                <div class="divide-y divide-line text-xs">
                    @foreach ($riwayatKonsultasi as $rk)
                        <div class="py-3 flex items-center justify-between gap-4">
                            <div>
                                <h4 class="font-bold text-ink">{{ $rk->judul }}</h4>
                                <p class="text-ink-soft mt-0.5">
                                    Klien: {{ $rk->masyarakat->nama ?? $rk->masyarakat->name }} &bull; Status: <strong class="capitalize">{{ $rk->status }}</strong>
                                </p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                @if ($rk->ulasan)
                                    <span class="inline-flex items-center gap-1 text-amber-500 font-bold">
                                        ★ {{ $rk->ulasan->rating }}.0
                                    </span>
                                @endif
                                <a href="{{ route('konsultasi.show', $rk->id) }}" class="text-xs font-semibold text-accent hover:underline">
                                    Rincian &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ============================================================ --}}
{{-- MODAL TOLAK PERMINTAAN KONSULTASI (Wajib Alasan Penolakan) --}}
{{-- ============================================================ --}}
<div id="modal-tolak" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs hidden">
    <div class="rounded-[24px] border border-line bg-paper max-w-lg w-full p-6 sm:p-8 shadow-2xl space-y-4">
        <div>
            <h3 class="font-display text-lg font-bold text-ink">
                Tolak Permintaan Konsultasi
            </h3>
            <p id="modal-tolak-judul" class="text-xs text-ink-soft mt-1 italic"></p>
        </div>

        <form id="form-tolak-konsultasi" method="POST" action="">
            @csrf
            <div>
                <label for="alasan_penolakan" class="block text-xs sm:text-sm font-bold text-ink">
                    Alasan Penolakan <span class="text-accent">*</span>
                </label>
                <textarea
                    name="alasan_penolakan"
                    id="alasan_penolakan"
                    rows="4"
                    required
                    minlength="5"
                    placeholder="Contoh: Permasalahan di luar spesialisasi hukum kami, atau sedang menangani perkara pihak terkait (konflik kepentingan)..."
                    class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory p-3.5 text-xs text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                ></textarea>
                <p class="mt-1 text-[11px] text-ink-soft">
                    Alasan ini akan dikirimkan ke pemohon untuk transparansi pertimbangan hukum. Untuk pengajuan pro bono, kuota Anda tidak akan berkurang.
                </p>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button
                    type="button"
                    onclick="tutupModalTolak()"
                    class="rounded-full border border-line bg-paper px-4 py-2 text-xs font-semibold text-ink-soft hover:text-ink transition"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="rounded-full bg-danger hover:bg-red-700 px-5 py-2 text-xs font-bold text-white shadow-btn transition"
                >
                    Konfirmasi Penolakan &rarr;
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalTolak(konsultasiId, judul) {
        document.getElementById('modal-tolak-judul').textContent = 'Perkara: "' + judul + '"';
        document.getElementById('form-tolak-konsultasi').action = '/advokat/konsultasi/' + konsultasiId + '/tolak';
        document.getElementById('modal-tolak').classList.remove('hidden');
    }

    function tutupModalTolak() {
        document.getElementById('modal-tolak').classList.add('hidden');
    }
</script>
@endsection
