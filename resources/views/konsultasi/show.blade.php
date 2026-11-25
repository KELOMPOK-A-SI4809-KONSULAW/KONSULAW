@extends('layouts.app')

@section('title', 'Rincian Konsultasi: ' . $konsultasi->judul . ' — Konsulaw')

@php
    $isMasyarakat = (auth()->id() === $konsultasi->masyarakat_id);
    $isAdvokat    = (auth()->id() === $konsultasi->advokat_id);
    $isAdmin      = (auth()->user()?->role === 'admin');

    $namaAdvokat = $konsultasi->advokat->nama ?? $konsultasi->advokat->name ?? 'Advokat';
    $namaKlien   = $konsultasi->masyarakat->nama ?? $konsultasi->masyarakat->name ?? 'Masyarakat';
    $status      = $konsultasi->status;
    $pembayaran  = $konsultasi->pembayaran;
    $ulasan      = $konsultasi->ulasan;
    $tarif       = $konsultasi->advokat->profilAdvokat->tarif_konsultasi ?? 0;
@endphp

@section('content')
<div class="min-h-[85vh] py-8 sm:py-12 bg-ivory">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb & Back --}}
        <div class="mb-4 flex items-center justify-between text-xs text-ink-soft">
            <a href="{{ $isAdvokat ? route('advokat.dashboard') : route('masyarakat.profil.edit', ['tab' => 'konsultasi-saya']) }}" class="inline-flex items-center gap-1.5 hover:text-accent font-semibold transition">
                &larr; Kembali ke {{ $isAdvokat ? 'Dasbor Advokat' : 'Konsultasi Saya' }}
            </a>
            <span class="rounded-full bg-paper border border-line px-2.5 py-0.5 text-[11px] font-mono">
                ID: #KSL-{{ str_pad($konsultasi->id, 5, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-6 rounded-[16px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 flex items-start gap-3">
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
            <div class="mb-6 rounded-[16px] bg-red-50 border border-red-200 p-4 text-xs sm:text-sm text-red-800 flex items-start gap-3">
                <svg class="h-5 w-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <div class="flex-1">
                    <p class="font-bold">Perhatian</p>
                    <p class="mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- Card Utama: Ringkasan Konsultasi --}}
        <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
            {{-- Header Perkara & Badge Status --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 border-b border-line pb-6">
                <div class="space-y-1.5 flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-ivory border border-line px-2.5 py-0.5 text-xs font-semibold text-ink-soft">
                            {{ $konsultasi->bidangHukum->nama ?? 'Konsultasi Hukum' }}
                        </span>

                        @if ($konsultasi->jenis_layanan === 'pro_bono')
                            <span class="rounded-full bg-purple-50 border border-purple-200 px-2.5 py-0.5 text-xs font-bold text-purple-800">
                                Layanan Pro Bono (Gratis)
                            </span>
                        @else
                            <span class="rounded-full bg-accent-soft px-2.5 py-0.5 text-xs font-bold text-accent-dark">
                                Jalur Reguler
                            </span>
                        @endif
                    </div>

                    <h1 class="font-display text-xl sm:text-2xl font-bold text-ink leading-snug">
                        {{ $konsultasi->judul }}
                    </h1>

                    <p class="text-xs text-ink-soft">
                        Diajukan pada {{ $konsultasi->created_at->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>

                {{-- Status Indicator Badge --}}
                <div class="shrink-0">
                    @if ($status === 'berlangsung')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1 text-xs font-bold text-success shadow-xs">
                            <span class="h-2 w-2 rounded-full bg-success animate-pulse"></span>
                            Sedang Berlangsung
                        </span>
                    @elseif ($status === 'menunggu_tanggapan')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3.5 py-1 text-xs font-bold text-amber-800 shadow-xs">
                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Menunggu Tanggapan Advokat
                        </span>
                    @elseif ($status === 'menunggu_pembayaran')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-200 px-3.5 py-1 text-xs font-bold text-blue-800 shadow-xs">
                            <svg class="h-3.5 w-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                            Menunggu Pembayaran
                        </span>
                    @elseif ($status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-neutral-100 border border-neutral-300 px-3.5 py-1 text-xs font-bold text-neutral-800 shadow-xs">
                            <svg class="h-3.5 w-3.5 text-neutral-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                            Konsultasi Selesai
                        </span>
                    @elseif ($status === 'ditolak')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 border border-red-200 px-3.5 py-1 text-xs font-bold text-danger shadow-xs">
                            <svg class="h-3.5 w-3.5 text-danger" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                            </svg>
                            Permintaan Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-paper border border-line px-3.5 py-1 text-xs font-semibold text-ink-soft">
                            {{ ucfirst($status) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Dua Pihak: Klien & Advokat --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-[16px] bg-ivory border border-line p-4">
                    <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Pemohon (Masyarakat):</span>
                    <p class="font-display text-base font-bold text-ink mt-0.5">{{ $namaKlien }}</p>
                    <p class="text-xs text-ink-soft mt-0.5">
                        Domisili: {{ $konsultasi->masyarakat->wilayah->nama ?? 'Wilayah belum diatur' }}
                    </p>
                </div>

                <div class="rounded-[16px] bg-ivory border border-line p-4">
                    <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Advokat Dituju:</span>
                    <p class="font-display text-base font-bold text-ink mt-0.5">{{ $namaAdvokat }}</p>
                    <p class="text-xs text-ink-soft mt-0.5">
                        Kualifikasi: Advokat Resmi Terverifikasi Konsulaw
                    </p>
                </div>
            </div>

            {{-- Banner Khusus Penolakan --}}
            @if ($status === 'ditolak' && $konsultasi->alasan_penolakan)
                <div class="mt-6 rounded-[16px] bg-red-50 border border-red-200 p-4">
                    <div class="flex items-center gap-2 text-danger font-bold text-xs sm:text-sm">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                        </svg>
                        Catatan Alasan Penolakan dari Advokat:
                    </div>
                    <p class="mt-1 text-xs text-red-900 leading-relaxed">
                        "{{ $konsultasi->alasan_penolakan }}"
                    </p>
                    @if ($isMasyarakat)
                        <div class="mt-3">
                            <a href="{{ route('home') }}" class="inline-flex items-center rounded-full bg-accent hover:bg-accent-dark px-4 py-1.5 text-xs font-bold text-white shadow-btn transition">
                                Cari Advokat Lain &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- GERBANG CHAT / TOMBOL TINDAKAN (B2.5 Aturan Inti) --}}
            <div class="mt-8 rounded-[18px] border border-line bg-gradient-to-br from-paper to-ivory p-5 sm:p-6 shadow-xs">
                @if ($status === 'berlangsung')
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-0.5">
                                &#9679; Ruang Diskusi Terbuka
                            </span>
                            <h3 class="font-display text-base sm:text-lg font-bold text-ink mt-1">
                                Sesi Konsultasi Sedang Aktif
                            </h3>
                            <p class="text-xs text-ink-soft mt-0.5">
                                Anda dapat bertukar pesan teks dan mengirimkan berkas bukti perkara secara dua arah.
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <a
                                href="{{ route('konsultasi.chat', $konsultasi->id) }}"
                                class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95 flex items-center gap-2"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 5.942 5.942 0 0 0 .97-2.73A8.77 8.77 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                </svg>
                                Buka Chat Konsultasi &rarr;
                            </a>
                        </div>
                    </div>
                @elseif ($status === 'menunggu_pembayaran')
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="font-display text-base sm:text-lg font-bold text-ink">
                                Tagihan Konsultasi Reguler
                            </h3>
                            <p class="text-xs text-ink-soft leading-relaxed">
                                Permohonan Anda telah disetujui advokat. Selesaikan pembayaran dalam 24 jam untuk membuka ruang chat konsultasi.
                            </p>
                            @if ($konsultasi->batas_bayar)
                                <p class="text-xs font-semibold text-accent-dark">
                                    Batas waktu: {{ \Carbon\Carbon::parse($konsultasi->batas_bayar)->translatedFormat('d F Y, H:i') }} WIB
                                </p>
                            @endif
                        </div>

                        <div class="text-right sm:border-l sm:border-line sm:pl-6 shrink-0">
                            <span class="text-[11px] text-ink-soft block">Jumlah Pembayaran:</span>
                            <span class="font-display text-xl sm:text-2xl font-bold text-ink block">
                                Rp {{ number_format($pembayaran?->jumlah ?? $tarif, 0, ',', '.') }}
                            </span>

                            @if ($isMasyarakat)
                                <div class="mt-3 flex flex-col gap-2 items-end">
                                    {{-- Tombol Snap Popup Resmi Midtrans --}}
                                    <button
                                        type="button"
                                        id="btn-bayar-snap"
                                        onclick="bayarMidtrans()"
                                        class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                                    >
                                        Bayar Sekarang (Midtrans Snap) &rarr;
                                    </button>

                                    {{-- Tombol Simulasi Pembayaran Sandbox (B3.4 Cadangan Demo Cepat) --}}
                                    <form action="{{ route('pembayaran.simulasi', $konsultasi->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button
                                            type="submit"
                                            onclick="return confirm('Gunakan simulator sandbox pembayaran sukses untuk demo localhost tanpa ngrok?')"
                                            class="rounded-full bg-paper border border-line hover:border-accent hover:text-accent px-4 py-1.5 text-[11px] font-semibold text-ink-soft transition shadow-2xs"
                                            title="Simulasi pelunasan pembayaran sukses mode sandbox (B3.4)"
                                        >
                                            &check; Simulasi Pembayaran Sukses (Sandbox)
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif ($status === 'menunggu_tanggapan')
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-ink">Permohonan Sedang Ditinjau Advokat</h4>
                                <p class="text-xs text-ink-soft mt-0.5">
                                    Ikon chat akan aktif seketika setelah advokat menyatakan bersedia menangani perkara ini.
                                </p>
                            </div>
                        </div>

                        {{-- Opsi Batalkan oleh Klien --}}
                        @if ($isMasyarakat)
                            <form action="{{ route('konsultasi.batalkan', $konsultasi->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permohonan konsultasi ini?')">
                                @csrf
                                <button type="submit" class="rounded-full bg-paper border border-line px-4 py-2 text-xs font-bold text-danger hover:bg-red-50 transition">
                                    Batalkan Pengajuan
                                </button>
                            </form>
                        @endif
                    </div>
                @elseif ($status === 'selesai')
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-neutral-200 text-neutral-800 text-[11px] font-bold px-2.5 py-0.5">
                                &check; Sesi Ditutup
                            </span>
                            <h3 class="font-display text-base font-bold text-ink mt-1">
                                Konsultasi Telah Selesai
                            </h3>
                            <p class="text-xs text-ink-soft">
                                Riwayat percakapan dan berkas tetap tersimpan rapi sebagai arsip konsultasi Anda.
                            </p>
                        </div>
                        <a
                            href="{{ route('konsultasi.chat', $konsultasi->id) }}"
                            class="rounded-full border border-line bg-paper hover:bg-ivory px-5 py-2 text-xs font-bold text-ink shadow-2xs transition"
                        >
                            Buka Riwayat Chat (Arsip) &rarr;
                        </a>
                    </div>
                @endif
            </div>

            {{-- Bagian Isi Kasus & Kronologi --}}
            <div class="mt-8 border-t border-line pt-6 space-y-6">
                <div>
                    <h3 class="text-xs uppercase font-bold text-ink-soft tracking-wider">
                        Kronologi Singkat Perkara:
                    </h3>
                    <div class="mt-2 rounded-[16px] bg-ivory/60 border border-line p-4 text-xs sm:text-sm text-ink leading-relaxed whitespace-pre-line">
                        {{ $konsultasi->kronologi }}
                    </div>
                </div>

                @if ($konsultasi->pertanyaan_hukum)
                    <div>
                        <h3 class="text-xs uppercase font-bold text-ink-soft tracking-wider">
                            Pertanyaan Hukum Pemohon:
                        </h3>
                        <div class="mt-2 rounded-[16px] bg-ivory/60 border border-line p-4 text-xs sm:text-sm text-ink italic leading-relaxed">
                            "{{ $konsultasi->pertanyaan_hukum }}"
                        </div>
                    </div>
                @endif

                {{-- Lampiran Berkas Kasus --}}
                <div>
                    <h3 class="text-xs uppercase font-bold text-ink-soft tracking-wider">
                        Dokumen Bukti Lampiran Kasus ({{ count($konsultasi->lampiran ?? []) }} berkas):
                    </h3>
                    @if (!empty($konsultasi->lampiran) && count($konsultasi->lampiran) > 0)
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($konsultasi->lampiran as $idx => $file)
                                <div class="rounded-[14px] border border-line bg-paper p-3 flex items-center justify-between gap-2 shadow-2xs">
                                    <div class="flex items-center gap-2.5 min-w-0">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-accent-soft text-accent text-xs font-bold uppercase">
                                            {{ $file['ekstensi'] ?? 'PDF' }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-ink truncate">{{ $file['nama_asli'] ?? 'Berkas Kasus' }}</p>
                                            <p class="text-[10px] text-ink-soft">{{ number_format(($file['ukuran'] ?? 0) / 1024, 0) }} KB</p>
                                        </div>
                                    </div>
                                    <a
                                        href="{{ route('konsultasi.lampiran.unduh', [$konsultasi->id, $idx]) }}"
                                        class="rounded-full bg-ivory border border-line hover:border-accent hover:text-accent p-1.5 text-xs text-ink-soft transition shrink-0"
                                        title="Unduh Berkas Lampiran Kasus"
                                    >
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.25V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-xs text-ink-soft italic">
                            Tidak ada berkas yang dilampirkan pada saat pengajuan. Berkas tambahan dapat dibagikan melalui ruang chat.
                        </p>
                    @endif
                </div>
            </div>

            {{-- BAGIAN RATING & ULASAN (FR-13, B4.4) --}}
            @if ($status === 'selesai')
                <div class="mt-10 border-t border-line pt-8">
                    <h3 class="font-display text-lg font-bold text-ink">
                        Penilaian & Ulasan Konsultasi
                    </h3>
                    <p class="text-xs text-ink-soft mt-0.5">
                        Transparansi penilaian membantu meningkatkan mutu layanan pendampingan hukum.
                    </p>

                    @if ($ulasan)
                        {{-- Tampilan Ulasan yang Sudah Diberikan --}}
                        <div class="mt-4 rounded-[16px] bg-ivory border border-line p-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $ulasan->rating ? 'text-amber-400 fill-amber-400' : 'text-neutral-300' }}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 font-bold text-ink text-xs">{{ $ulasan->rating }}.0 / 5.0</span>
                                </div>
                                <span class="text-[11px] text-ink-soft">
                                    {{ $ulasan->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>
                            @if ($ulasan->komentar)
                                <p class="mt-2.5 text-xs text-ink leading-relaxed italic">
                                    "{{ $ulasan->komentar }}"
                                </p>
                            @endif
                        </div>
                    @elseif ($isMasyarakat)
                        {{-- Form Pemberian Ulasan (Khusus Klien Masyarakat) --}}
                        <form action="{{ route('konsultasi.ulasan', $konsultasi->id) }}" method="POST" class="mt-4 rounded-[16px] bg-ivory border border-line p-5 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-ink mb-1.5">
                                    Beri Penilaian Bintang: <span class="text-accent">*</span>
                                </label>
                                <div class="flex items-center gap-2">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="rating" value="{{ $star }}" class="sr-only peer" required {{ $star == 5 ? 'checked' : '' }}>
                                            <svg class="h-6 w-6 text-neutral-300 peer-checked:text-amber-400 peer-checked:fill-amber-400 hover:text-amber-300 transition" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div>
                                <label for="komentar" class="block text-xs font-bold text-ink mb-1">
                                    Ulasan dan Pengalaman Anda (Opsional):
                                </label>
                                <textarea
                                    name="komentar"
                                    id="komentar"
                                    rows="3"
                                    placeholder="Bagaimana pelayanan advokat dalam merespons dan memberikan telaah hukum atas permasalahan Anda?"
                                    class="w-full rounded-[14px] border border-line bg-paper p-3 text-xs text-ink placeholder:text-ink-soft/60 focus:border-accent focus:outline-none"
                                ></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-5 py-2 text-xs font-bold text-white shadow-btn transition"
                                >
                                    Kirim Ulasan & Penilaian &rarr;
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="mt-2 text-xs text-ink-soft italic">
                            Klien pemohon belum memberikan penilaian ulasan untuk sesi ini.
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script Integrasi Midtrans Snap Popup (B3.4) --}}
@if ($status === 'menunggu_pembayaran' && $isMasyarakat)
    <script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"
    ></script>
    <script>
        function bayarMidtrans() {
            const btn = document.getElementById('btn-bayar-snap');
            btn.disabled = true;
            btn.textContent = 'Memuat transaksi...';

            fetch('{{ route('pembayaran.inisialisasi', $konsultasi->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang (Midtrans Snap) \u2192';

                if (data.snap_token) {
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.reload();
                        },
                        onPending: function(result) {
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal atau dibatalkan. Silakan coba kembali.');
                        },
                        onClose: function() {
                            // User menutup popup Snap tanpa menyelesaikan pembayaran
                        }
                    });
                } else {
                    alert('Gagal mengambil token pembayaran. Silakan coba metode simulasi sandbox.');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Bayar Sekarang (Midtrans Snap) \u2192';
                console.error(err);
                alert('Terjadi kesalahan jaringan saat menghubungi gateway pembayaran.');
            });
        }
    </script>
@endif
@endsection
