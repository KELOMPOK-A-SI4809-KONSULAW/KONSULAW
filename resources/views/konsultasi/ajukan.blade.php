@extends('layouts.app')

@section('title', 'Ajukan Konsultasi Hukum — Konsulaw')

@php
    $advokatTerpilihId = $advokatTerpilih?->id ?? old('advokat_id', request('advokat'));
    $hasAdvokatAwal = !empty($advokatTerpilih);
@endphp

@section('content')
<div class="min-h-[85vh] py-8 sm:py-12 bg-ivory">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        {{-- Header & Progress Langkah --}}
        <div class="mb-8 text-center sm:text-left sm:flex sm:items-end sm:justify-between border-b border-line pb-6">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-accent-soft px-3 py-1 text-xs font-bold text-accent-dark">
                    <svg class="h-3.5 w-3.5 text-accent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Permohonan Konsultasi Pra-Litigasi
                </span>
                <h1 class="mt-2.5 font-display text-2xl sm:text-3xl font-bold text-ink">
                    Pengajuan Konsultasi Hukum
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-ink-soft">
                    Sampaikan persoalan hukum Anda kepada advokat profesional terverifikasi secara aman dan transparan.
                </p>
            </div>

            {{-- Stepper Pill Indicators --}}
            <div class="mt-4 sm:mt-0 flex items-center justify-center sm:justify-end gap-2 text-xs font-bold">
                <span id="step-badge-1" class="step-pill rounded-full px-3 py-1 bg-accent text-white shadow-btn transition">
                    1. Pilih Advokat
                </span>
                <span class="text-ink-soft/40">&rarr;</span>
                <span id="step-badge-2" class="step-pill rounded-full px-3 py-1 bg-paper border border-line text-ink-soft transition">
                    2. Jenis Layanan
                </span>
                <span class="text-ink-soft/40">&rarr;</span>
                <span id="step-badge-3" class="step-pill rounded-full px-3 py-1 bg-paper border border-line text-ink-soft transition">
                    3. Isi Kasus
                </span>
                <span class="text-ink-soft/40">&rarr;</span>
                <span id="step-badge-4" class="step-pill rounded-full px-3 py-1 bg-paper border border-line text-ink-soft transition">
                    4. Konfirmasi
                </span>
            </div>
        </div>

        {{-- Alert Notifikasi / Pesan Error / Draf Tersimpan --}}
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

        @if ($errors->any())
            <div class="mb-6 rounded-[16px] bg-red-50 border border-red-200 p-4 text-xs sm:text-sm text-red-800">
                <p class="font-bold">Mohon perbaiki isian permohonan Anda:</p>
                <ul class="mt-1 list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Utama Multi-Langkah --}}
        <form id="form-ajukan-konsultasi" action="{{ route('konsultasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="advokat_id" id="input-advokat-id" value="{{ $advokatTerpilihId }}">
            <input type="hidden" name="jenis_layanan" id="input-jenis-layanan" value="{{ old('jenis_layanan', 'reguler') }}">

            {{-- ============================================================ --}}
            {{-- LANGKAH 1: PILIH ADVOKAT TERVERIFIKASI --}}
            {{-- ============================================================ --}}
            <div id="step-1" class="step-content space-y-6">
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-line pb-5">
                        <div>
                            <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                                Langkah 1: Pilih Advokat Praktik
                            </h2>
                            <p class="text-xs sm:text-sm text-ink-soft mt-1">
                                Pilih advokat terverifikasi yang sesuai dengan kebutuhan perkara dan domisili Anda.
                            </p>
                        </div>

                        {{-- Filter Pencarian Cepat --}}
                        <div class="flex items-center gap-2">
                            <input
                                type="text"
                                id="cari-advokat-input"
                                placeholder="Cari nama advokat..."
                                class="rounded-full border border-line bg-ivory px-3.5 py-1.5 text-xs text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                                onkeyup="filterAdvokatList()"
                            >
                        </div>
                    </div>

                    {{-- Daftar Advokat Siap Konsultasi --}}
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[500px] overflow-y-auto pr-1" id="advokat-grid">
                        @forelse ($daftarAdvokat as $adv)
                            @php
                                $profilAdv = $adv->profilAdvokat;
                                $namaAdv = $adv->nama ?? $adv->name ?? 'Advokat';
                                $parts = preg_split('/\s+/', trim($namaAdv)) ?: ['A', 'D'];
                                $inisialAdv = mb_strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . mb_substr($parts[1] ?? 'D', 0, 1));
                                $wilayahAdv = $profilAdv?->wilayahPraktik->pluck('nama')->toArray() ?: [$adv->wilayah->nama ?? 'Nasional'];
                                $bidangAdv  = $profilAdv?->bidangHukum->pluck('nama')->toArray() ?: [];
                                $tarifAdv   = $profilAdv?->tarif_konsultasi ?? 0;
                                $kuotaAdv   = $profilAdv?->kuota_probono ?? 0;
                                $isTerpilih = ($advokatTerpilihId == $adv->id);
                            @endphp
                            <div
                                class="advokat-card rounded-[16px] border {{ $isTerpilih ? 'border-accent ring-2 ring-accent/20 bg-accent-soft/30' : 'border-line bg-paper hover:border-accent/50' }} p-4.5 transition cursor-pointer flex flex-col justify-between"
                                data-id="{{ $adv->id }}"
                                data-nama="{{ $namaAdv }}"
                                data-tarif="{{ $tarifAdv }}"
                                data-kuota="{{ $kuotaAdv }}"
                                data-wilayah="{{ implode(', ', $wilayahAdv) }}"
                                onclick="pilihAdvokat({{ $adv->id }}, '{{ addslashes($namaAdv) }}', {{ $tarifAdv }}, {{ $kuotaAdv }}, '{{ addslashes(implode(', ', $wilayahAdv)) }}')"
                            >
                                <div>
                                    <div class="flex items-start gap-3">
                                        @if($adv->foto_profil)
                                            <img src="{{ asset('storage/' . $adv->foto_profil) }}" alt="{{ $namaAdv }}" class="h-12 w-12 rounded-[14px] object-cover shrink-0">
                                        @else
                                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-[14px] bg-accent-soft text-accent font-display font-bold text-base">
                                                {{ $inisialAdv }}
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <h3 class="font-display text-sm font-bold text-ink truncate">{{ $namaAdv }}</h3>
                                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-success border border-emerald-200">
                                                    Terverifikasi
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-ink-soft truncate mt-0.5">
                                                {{ implode(', ', $wilayahAdv) }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Bidang Keahlian Chip --}}
                                    <div class="mt-3 flex flex-wrap gap-1">
                                        @foreach (array_slice($bidangAdv, 0, 2) as $b)
                                            <span class="rounded-full bg-ivory border border-line px-2 py-0.5 text-[10px] text-ink-soft">
                                                {{ $b }}
                                            </span>
                                        @endforeach
                                        @if (count($bidangAdv) > 2)
                                            <span class="rounded-full bg-ivory border border-line px-1.5 py-0.5 text-[10px] text-ink-soft">
                                                +{{ count($bidangAdv) - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4 pt-3 border-t border-line flex items-center justify-between text-xs">
                                    <div>
                                        <span class="block text-[10px] text-ink-soft">Tarif Reguler</span>
                                        <span class="font-bold text-ink">Rp {{ number_format($tarifAdv, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="block text-[10px] text-ink-soft">Pro Bono</span>
                                        @if ($kuotaAdv > 0)
                                            <span class="font-bold text-accent-dark">Sisa {{ $kuotaAdv }}</span>
                                        @else
                                            <span class="font-medium text-ink-soft line-through">Penuh</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 py-10 text-center text-ink-soft text-xs">
                                Tidak ada advokat aktif yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>

                    {{-- Tombol Lanjut Langkah 1 --}}
                    <div class="mt-6 pt-4 border-t border-line flex justify-end">
                        <button
                            type="button"
                            id="btn-next-step-1"
                            onclick="keLangkah(2)"
                            disabled
                            class="rounded-full bg-accent hover:bg-accent-dark disabled:opacity-40 disabled:cursor-not-allowed px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition"
                        >
                            Lanjutkan ke Jenis Layanan &rarr;
                        </button>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- LANGKAH 2: PILIH JENIS LAYANAN (REGULER VS PRO BONO) --}}
            {{-- ============================================================ --}}
            <div id="step-2" class="step-content space-y-6 hidden">
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    {{-- Advokat Terpilih Strip --}}
                    <div class="mb-6 rounded-[16px] bg-ivory border border-line p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-accent text-white font-display font-bold text-sm">
                                &#9878;
                            </div>
                            <div>
                                <span class="text-[10px] uppercase font-bold text-ink-soft tracking-wider">Advokat Dituju:</span>
                                <p id="summary-advokat-nama" class="font-display text-sm sm:text-base font-bold text-ink">
                                    {{ $advokatTerpilih ? ($advokatTerpilih->nama ?? $advokatTerpilih->name) : 'Belum dipilih' }}
                                </p>
                            </div>
                        </div>
                        <button
                            type="button"
                            onclick="keLangkah(1)"
                            class="text-xs font-bold text-accent hover:underline shrink-0"
                        >
                            Ganti Advokat
                        </button>
                    </div>

                    <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                        Langkah 2: Pilih Jenis Layanan
                    </h2>
                    <p class="text-xs sm:text-sm text-ink-soft mt-1">
                        Tentukan skema konsultasi: jalur reguler berbayar atau bantuan hukum cuma-cuma (pro bono).
                    </p>

                    {{-- 2 Pilihan Kartu: Reguler vs Pro Bono --}}
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kartu Reguler --}}
                        <div
                            id="card-layanan-reguler"
                            class="service-card rounded-[18px] border-2 border-accent bg-accent-soft/20 p-5 cursor-pointer transition relative"
                            onclick="pilihJenisLayanan('reguler')"
                        >
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full bg-paper border border-line px-2.5 py-0.5 text-xs font-bold text-ink">
                                    Jalur Reguler
                                </span>
                                <span id="check-reguler" class="flex h-5 w-5 items-center justify-center rounded-full bg-accent text-white text-xs">
                                    &check;
                                </span>
                            </div>
                            <div class="mt-4">
                                <p class="text-xs text-ink-soft">Tarif Konsultasi:</p>
                                <p id="summary-tarif-reguler" class="font-display text-xl font-bold text-ink mt-0.5">
                                    Rp {{ number_format($advokatTerpilih?->profilAdvokat?->tarif_konsultasi ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="mt-2 text-[11px] text-ink-soft leading-relaxed">
                                    &bull; <strong>Bayar setelah advokat menerima</strong> permohonan Anda.<br>
                                    &bull; Akses chat aktif otomatis setelah pembayaran lunas.<br>
                                    &bull; Tanpa perlu unggah surat keterangan ekonomi.
                                </p>
                            </div>
                        </div>

                        {{-- Kartu Pro Bono --}}
                        @php
                            $sisaKuota = $advokatTerpilih?->profilAdvokat?->kuota_probono ?? 5;
                            $kuotaHabis = ($sisaKuota <= 0);
                        @endphp
                        <div
                            id="card-layanan-probono"
                            class="service-card rounded-[18px] border border-line bg-paper p-5 cursor-pointer transition relative {{ $kuotaHabis ? 'opacity-50 cursor-not-allowed' : '' }}"
                            onclick="{{ $kuotaHabis ? 'return false;' : "pilihJenisLayanan('pro_bono')" }}"
                        >
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full bg-purple-50 border border-purple-200 px-2.5 py-0.5 text-xs font-bold text-purple-800">
                                    Layanan Pro Bono
                                </span>
                                <span id="check-probono" class="hidden flex h-5 w-5 items-center justify-center rounded-full bg-accent text-white text-xs">
                                    &check;
                                </span>
                            </div>
                            <div class="mt-4">
                                <p class="text-xs text-ink-soft">Biaya Konsultasi:</p>
                                <p class="font-display text-xl font-bold text-emerald-700 mt-0.5">
                                    Gratis (Rp 0)
                                </p>
                                <p class="mt-2 text-[11px] text-ink-soft leading-relaxed">
                                    &bull; Sisa kuota advokat bulan ini: <strong id="summary-kuota-probono">{{ $sisaKuota }}</strong> kasus.<br>
                                    &bull; Khusus masyarakat pencari keadilan kurang mampu.<br>
                                    &bull; Memerlukan status ekonomi Pro Bono terverifikasi.
                                </p>
                                @if ($kuotaHabis)
                                    <span class="mt-2 inline-block rounded-full bg-red-100 text-red-800 text-[10px] font-bold px-2.5 py-0.5">
                                        Kuota Pro Bono Bulan Ini Penuh
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Panel Percabangan Verifikasi Ekonomi Pro Bono (B2.2) --}}
                    <div id="panel-verifikasi-ekonomi" class="mt-6 hidden">
                        @if ($statusEkonomi === 'pro_bono')
                            {{-- Kondisi 3: Disetujui / Pro Bono Aktif --}}
                            <div class="rounded-[16px] bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3">
                                <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-xs sm:text-sm font-bold text-emerald-900">Status Pro Bono Anda Aktif & Terverifikasi!</p>
                                    <p class="text-xs text-emerald-800 mt-0.5">
                                        Akun Anda telah memenuhi kriteria bantuan hukum cuma-cuma. Anda dapat melanjutkan pengisian kasus.
                                    </p>
                                </div>
                            </div>
                        @elseif ($statusEkonomi === 'pending')
                            {{-- Kondisi 2: Sedang Diproses --}}
                            <div class="rounded-[16px] bg-amber-50 border border-amber-200 p-4 flex items-start gap-3">
                                <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs sm:text-sm font-bold text-amber-900">Verifikasi Dokumen Ekonomi Sedang Diperiksa Admin</p>
                                    <p class="text-xs text-amber-800 mt-0.5">
                                        Pengajuan SKTM/KIS Anda sedang ditinjau. Anda dapat menunggu konfirmasi (draf kasus akan tersimpan) atau beralih menggunakan jalur Reguler.
                                    </p>
                                    <div class="mt-3 flex gap-2">
                                        <button type="button" onclick="pilihJenisLayanan('reguler')" class="rounded-full bg-paper border border-amber-300 px-3 py-1 text-xs font-bold text-amber-900 hover:bg-amber-100 transition">
                                            Beralih ke Reguler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Kondisi 1: Belum Pernah Mengajukan atau Ditolak --}}
                            <div class="rounded-[16px] bg-amber-50/80 border border-amber-300 p-4 flex items-start gap-3">
                                <svg class="h-5 w-5 text-amber-700 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-xs sm:text-sm font-bold text-amber-900">Akun Anda Perlu Verifikasi Ekonomi Terlebih Dahulu</p>
                                    <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                                        Untuk mendapatkan layanan konsultasi pro bono gratis, unggah dokumen pendukung seperti SKTM, KIS, atau KKS. Draf isian kasus Anda akan tersimpan otomatis.
                                    </p>
                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                        <a
                                            href="{{ route('masyarakat.profil.edit', ['tab' => 'status-layanan']) }}"
                                            class="rounded-full bg-accent hover:bg-accent-dark px-4 py-1.5 text-xs font-bold text-white shadow-btn transition"
                                        >
                                            Ajukan Verifikasi Pro Bono &rarr;
                                        </a>
                                        <button
                                            type="button"
                                            onclick="pilihJenisLayanan('reguler')"
                                            class="rounded-full bg-paper border border-line px-3.5 py-1.5 text-xs font-semibold text-ink hover:text-accent transition"
                                        >
                                            Tetap Lanjut sebagai Reguler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Navigasi Langkah 2 --}}
                    <div class="mt-8 pt-4 border-t border-line flex items-center justify-between">
                        <button
                            type="button"
                            onclick="keLangkah(1)"
                            class="rounded-full border border-line bg-paper px-5 py-2.5 text-xs sm:text-sm font-semibold text-ink-soft hover:text-ink transition"
                        >
                            &larr; Kembali
                        </button>

                        <button
                            type="button"
                            id="btn-next-step-2"
                            onclick="keLangkah(3)"
                            class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition"
                        >
                            Lanjutkan ke Isi Kasus &rarr;
                        </button>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- LANGKAH 3: ISI KASUS & PERTANYAAN HUKUM --}}
            {{-- ============================================================ --}}
            <div id="step-3" class="step-content space-y-6 hidden">
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                        Langkah 3: Uraikan Pokok Perkara
                    </h2>
                    <p class="text-xs sm:text-sm text-ink-soft mt-1">
                        Ceritakan permasalahan hukum Anda dengan jelas agar advokat dapat melakukan penelaahan pra-litigasi.
                    </p>

                    <div class="mt-6 space-y-5">
                        {{-- 1. Kategori Masalah / Bidang Hukum --}}
                        <div>
                            <label for="bidang_id" class="block text-xs sm:text-sm font-bold text-ink">
                                Kategori Masalah Hukum <span class="text-accent">*</span>
                            </label>
                            <div class="relative mt-1.5">
                                <select
                                    name="bidang_id"
                                    id="bidang_id"
                                    required
                                    class="w-full appearance-none rounded-[14px] border border-line bg-ivory px-4 py-2.5 pr-10 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                                >
                                    <option value="">-- Pilih Bidang Hukum Terkait --</option>
                                    @foreach ($daftarBidang as $b)
                                        <option value="{{ $b->id }}" {{ old('bidang_id', $drafKasus['bidang_id'] ?? '') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-ink-soft">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </span>
                            </div>
                        </div>

                        {{-- 2. Judul Masalah Singkat --}}
                        <div>
                            <label for="judul" class="block text-xs sm:text-sm font-bold text-ink">
                                Judul Pokok Perkara <span class="text-accent">*</span>
                            </label>
                            <input
                                type="text"
                                name="judul"
                                id="judul"
                                required
                                value="{{ old('judul', $drafKasus['judul'] ?? '') }}"
                                placeholder="Contoh: Sengketa Batas Patok Tanah Waris di Desa Cibiru"
                                class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                            >
                        </div>

                        {{-- 3. Kronologi Kejadian (Wajib, Min 20 karakter) --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="kronologi" class="block text-xs sm:text-sm font-bold text-ink">
                                    Kronologi Singkat Kejadian <span class="text-accent">*</span>
                                </label>
                                <span class="text-[11px] text-ink-soft" id="char-count">Min. 20 karakter</span>
                            </div>
                            <textarea
                                name="kronologi"
                                id="kronologi"
                                rows="5"
                                required
                                minlength="20"
                                onkeyup="updateCharCount(this)"
                                placeholder="Jelaskan urutan waktu peristiwa, pihak yang bersangkutan, serta duduk persoalan yang sedang Anda hadapi..."
                                class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory p-4 text-xs sm:text-sm text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                            >{{ old('kronologi', $drafKasus['kronologi'] ?? '') }}</textarea>
                            <p class="mt-1 text-[11px] text-ink-soft">
                                Kronologi wajib diisi untuk kedua jalur (Reguler & Pro Bono) agar advokat memiliki dasar menelaah sebelum menerima permintaan.
                            </p>
                        </div>

                        {{-- 4. Pertanyaan Hukum Spesifik (Opsional) --}}
                        <div>
                            <label for="pertanyaan_hukum" class="block text-xs sm:text-sm font-bold text-ink">
                                Pertanyaan / Hal yang Ingin Dikonsultasikan <span class="text-[11px] font-normal text-ink-soft">(Opsional)</span>
                            </label>
                            <textarea
                                name="pertanyaan_hukum"
                                id="pertanyaan_hukum"
                                rows="3"
                                placeholder="Contoh: Apakah surat perjanjian bawah tangan ini memiliki kekuatan hukum yang sah jika dibawa ke mediasi desa?"
                                class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory p-4 text-xs sm:text-sm text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                            >{{ old('pertanyaan_hukum', $drafKasus['pertanyaan_hukum'] ?? '') }}</textarea>
                        </div>

                        {{-- 5. Lampiran Berkas Kasus (Opsional, maks 3 file, 5MB per file) --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-bold text-ink">
                                Dokumen Bukti Pendukung Kasus <span class="text-[11px] font-normal text-ink-soft">(Opsional, Maks. 3 berkas)</span>
                            </label>
                            <input
                                type="file"
                                name="lampiran[]"
                                id="lampiran"
                                multiple
                                accept=".pdf,.jpg,.jpeg,.png"
                                class="mt-1.5 block w-full text-xs text-ink-soft file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-accent-soft file:text-accent-dark hover:file:bg-accent/20 cursor-pointer"
                            >
                            <p class="mt-1.5 text-[11px] text-ink-soft leading-relaxed">
                                Format yang diterima: PDF, JPG, PNG (maks. 5 MB/berkas). Contoh: surat tanah, kontrak, tanda terima, atau somasi. Dokumen dapat dilewati dan dikirimkan nanti di ruang chat.
                            </p>
                        </div>

                        {{-- Kotak Kerahasiaan Perkara (FR-16 / NFR-05) --}}
                        <div class="rounded-[14px] bg-ivory border border-line p-3.5 flex items-start gap-2.5 text-[11px] text-ink-soft">
                            <svg class="h-4 w-4 text-accent shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                            <p>
                                <strong>Prinsip Kerahasiaan Klien-Advokat:</strong> Berkas perkara dan kronologi kasus Anda disimpan secara aman dan hanya dapat dibuka oleh advokat yang dituju. Administrator sistem tidak memiliki akses membuka dokumen perkara Anda.
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Navigasi Langkah 3 --}}
                    <div class="mt-8 pt-4 border-t border-line flex items-center justify-between">
                        <button
                            type="button"
                            onclick="keLangkah(2)"
                            class="rounded-full border border-line bg-paper px-5 py-2.5 text-xs sm:text-sm font-semibold text-ink-soft hover:text-ink transition"
                        >
                            &larr; Kembali
                        </button>

                        <button
                            type="button"
                            id="btn-next-step-3"
                            onclick="keLangkah(4)"
                            class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition"
                        >
                            Tinjau & Kirim &rarr;
                        </button>
                    </div>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- LANGKAH 4: TINJAU & KIRIM PERMOHONAN --}}
            {{-- ============================================================ --}}
            <div id="step-4" class="step-content space-y-6 hidden">
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                        Langkah 4: Tinjau Rincian Permohonan
                    </h2>
                    <p class="text-xs sm:text-sm text-ink-soft mt-1">
                        Pastikan seluruh data yang Anda masukkan telah benar sebelum dikirimkan ke advokat.
                    </p>

                    <div class="mt-6 divide-y divide-line rounded-[16px] border border-line bg-ivory/50">
                        {{-- Ringkasan Advokat --}}
                        <div class="p-4 flex items-center justify-between gap-4">
                            <span class="text-xs font-bold text-ink-soft">Advokat Dituju:</span>
                            <span id="review-advokat-nama" class="text-xs sm:text-sm font-bold text-ink text-right">
                                {{ $advokatTerpilih ? ($advokatTerpilih->nama ?? $advokatTerpilih->name) : '-' }}
                            </span>
                        </div>

                        {{-- Ringkasan Jenis Layanan & Biaya --}}
                        <div class="p-4 flex items-center justify-between gap-4">
                            <span class="text-xs font-bold text-ink-soft">Jenis Layanan:</span>
                            <span id="review-jenis-layanan" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold bg-accent-soft text-accent-dark">
                                Reguler (Rp {{ number_format($advokatTerpilih?->profilAdvokat?->tarif_konsultasi ?? 0, 0, ',', '.') }})
                            </span>
                        </div>

                        {{-- Ringkasan Kategori --}}
                        <div class="p-4 flex items-center justify-between gap-4">
                            <span class="text-xs font-bold text-ink-soft">Bidang Hukum:</span>
                            <span id="review-bidang-hukum" class="text-xs font-semibold text-ink text-right">-</span>
                        </div>

                        {{-- Ringkasan Judul --}}
                        <div class="p-4 flex flex-col gap-1">
                            <span class="text-xs font-bold text-ink-soft">Judul Kasus:</span>
                            <p id="review-judul" class="text-xs sm:text-sm font-semibold text-ink italic">-</p>
                        </div>

                        {{-- Ringkasan Kronologi --}}
                        <div class="p-4 flex flex-col gap-1">
                            <span class="text-xs font-bold text-ink-soft">Kronologi Singkat:</span>
                            <p id="review-kronologi" class="text-xs text-ink/80 leading-relaxed whitespace-pre-line">-</p>
                        </div>
                    </div>

                    {{-- Pemberitahuan Mekanisme Pembayaran (B2.4) --}}
                    <div id="review-reguler-note" class="mt-6 rounded-[14px] bg-emerald-50 border border-emerald-200 p-4 flex items-start gap-3 text-xs text-emerald-900">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <p class="font-bold">Keamanan Transaksi Anda Terjamin</p>
                            <p class="mt-0.5 leading-relaxed">
                                Anda <strong>baru melakukan pembayaran setelah advokat memeriksa dan menyetujui</strong> permohonan konsultasi ini. Anda tidak akan ditagih sebelum advokat bersedia menangani perkara Anda.
                            </p>
                        </div>
                    </div>

                    <div id="review-probono-note" class="mt-6 rounded-[14px] bg-purple-50 border border-purple-200 p-4 flex items-start gap-3 text-xs text-purple-900 hidden">
                        <svg class="h-5 w-5 text-purple-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.51 48.51 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.583V21" />
                        </svg>
                        <div>
                            <p class="font-bold">Bantuan Hukum Cuma-Cuma (Pro Bono)</p>
                            <p class="mt-0.5 leading-relaxed">
                                Konsultasi ini sepenuhnya bebas biaya. Setelah advokat menerima permohonan, kuota advokat akan berkurang dan ruang chat akan aktif seketika.
                            </p>
                        </div>
                    </div>

                    {{-- Tombol Navigasi Langkah 4 & Submit Form --}}
                    <div class="mt-8 pt-4 border-t border-line flex items-center justify-between">
                        <button
                            type="button"
                            onclick="keLangkah(3)"
                            class="rounded-full border border-line bg-paper px-5 py-2.5 text-xs sm:text-sm font-semibold text-ink-soft hover:text-ink transition"
                        >
                            &larr; Kembali Edit Kasus
                        </button>

                        <button
                            type="submit"
                            id="btn-submit-konsultasi"
                            class="rounded-full bg-accent hover:bg-accent-dark px-7 py-3 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                        >
                            Kirim Permohonan Konsultasi &rarr;
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Skrip Interaktivitas Wizard Multi-Langkah --}}
<script>
    let stepAktif = {{ $hasAdvokatAwal ? 2 : 1 }};
    let terpilihAdvokatId = {{ $advokatTerpilihId ? $advokatTerpilihId : 'null' }};
    let terpilihNamaAdv = '{{ addslashes($advokatTerpilih?->nama ?? $advokatTerpilih?->name ?? '') }}';
    let terpilihTarifAdv = {{ $advokatTerpilih?->profilAdvokat?->tarif_konsultasi ?? 0 }};
    let terpilihKuotaAdv = {{ $advokatTerpilih?->profilAdvokat?->kuota_probono ?? 0 }};
    let jenisLayananAktif = '{{ old('jenis_layanan', 'reguler') }}';
    const statusEkonomiUser = '{{ $statusEkonomi }}';

    function initPage() {
        if (terpilihAdvokatId) {
            document.getElementById('btn-next-step-1').disabled = false;
        }
        keLangkah(stepAktif);
        pilihJenisLayanan(jenisLayananAktif);
    }

    function keLangkah(step) {
        if (step === 2 && !terpilihAdvokatId) {
            alert('Silakan pilih salah satu advokat terlebih dahulu.');
            return;
        }
        if (step === 4) {
            // Validasi langkah 3
            const judul = document.getElementById('judul').value.trim();
            const kronologi = document.getElementById('kronologi').value.trim();
            const bidang = document.getElementById('bidang_id').value;

            if (!bidang) {
                alert('Silakan pilih kategori masalah hukum.');
                document.getElementById('bidang_id').focus();
                return;
            }
            if (!judul) {
                alert('Silakan isi judul masalah perkara Anda.');
                document.getElementById('judul').focus();
                return;
            }
            if (kronologi.length < 20) {
                alert('Kronologi perkara minimal harus terdiri dari 20 karakter agar advokat dapat menelaah kasus Anda.');
                document.getElementById('kronologi').focus();
                return;
            }

            // Update data review di Langkah 4
            document.getElementById('review-advokat-nama').textContent = terpilihNamaAdv;
            const bidangSelect = document.getElementById('bidang_id');
            document.getElementById('review-bidang-hukum').textContent = bidangSelect.options[bidangSelect.selectedIndex]?.text || '-';
            document.getElementById('review-judul').textContent = judul;
            document.getElementById('review-kronologi').textContent = kronologi;

            if (jenisLayananAktif === 'pro_bono') {
                document.getElementById('review-jenis-layanan').textContent = 'Pro Bono (Gratis)';
                document.getElementById('review-jenis-layanan').className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold bg-purple-50 text-purple-800 border border-purple-200';
                document.getElementById('review-reguler-note').classList.add('hidden');
                document.getElementById('review-probono-note').classList.remove('hidden');
            } else {
                document.getElementById('review-jenis-layanan').textContent = 'Reguler (Rp ' + Number(terpilihTarifAdv).toLocaleString('id-ID') + ')';
                document.getElementById('review-jenis-layanan').className = 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold bg-accent-soft text-accent-dark border border-accent/20';
                document.getElementById('review-reguler-note').classList.remove('hidden');
                document.getElementById('review-probono-note').classList.add('hidden');
            }
        }

        stepAktif = step;

        // Toggle visibility container langkah
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('hidden'));
        const activeContainer = document.getElementById('step-' + step);
        if (activeContainer) activeContainer.classList.remove('hidden');

        // Update stepper pills
        for (let i = 1; i <= 4; i++) {
            const badge = document.getElementById('step-badge-' + i);
            if (!badge) continue;
            if (i === step) {
                badge.className = 'step-pill rounded-full px-3 py-1 bg-accent text-white shadow-btn transition';
            } else if (i < step) {
                badge.className = 'step-pill rounded-full px-3 py-1 bg-emerald-50 border border-emerald-200 text-success font-bold transition';
            } else {
                badge.className = 'step-pill rounded-full px-3 py-1 bg-paper border border-line text-ink-soft transition';
            }
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function pilihAdvokat(id, nama, tarif, kuota, wilayah) {
        terpilihAdvokatId = id;
        terpilihNamaAdv = nama;
        terpilihTarifAdv = tarif;
        terpilihKuotaAdv = kuota;

        document.getElementById('input-advokat-id').value = id;
        document.getElementById('summary-advokat-nama').textContent = nama;
        document.getElementById('summary-tarif-reguler').textContent = 'Rp ' + Number(tarif).toLocaleString('id-ID');
        document.getElementById('summary-kuota-probono').textContent = kuota;

        // Visual selection on cards
        document.querySelectorAll('.advokat-card').forEach(c => {
            c.classList.remove('border-accent', 'ring-2', 'ring-accent/20', 'bg-accent-soft/30');
            c.classList.add('border-line', 'bg-paper');
        });

        const activeCard = document.querySelector(`.advokat-card[data-id="${id}"]`);
        if (activeCard) {
            activeCard.classList.remove('border-line', 'bg-paper');
            activeCard.classList.add('border-accent', 'ring-2', 'ring-accent/20', 'bg-accent-soft/30');
        }

        document.getElementById('btn-next-step-1').disabled = false;
    }

    function pilihJenisLayanan(jenis) {
        jenisLayananAktif = jenis;
        document.getElementById('input-jenis-layanan').value = jenis;

        const cardReguler = document.getElementById('card-layanan-reguler');
        const cardProbono = document.getElementById('card-layanan-probono');
        const checkReguler = document.getElementById('check-reguler');
        const checkProbono = document.getElementById('check-probono');
        const panelEkonomi = document.getElementById('panel-verifikasi-ekonomi');
        const btnNext2 = document.getElementById('btn-next-step-2');

        if (jenis === 'reguler') {
            cardReguler.className = 'service-card rounded-[18px] border-2 border-accent bg-accent-soft/20 p-5 cursor-pointer transition relative';
            cardProbono.className = 'service-card rounded-[18px] border border-line bg-paper p-5 cursor-pointer transition relative';
            checkReguler.classList.remove('hidden');
            checkProbono.classList.add('hidden');
            panelEkonomi.classList.add('hidden');
            btnNext2.disabled = false;
        } else {
            cardProbono.className = 'service-card rounded-[18px] border-2 border-accent bg-purple-50/50 p-5 cursor-pointer transition relative';
            cardReguler.className = 'service-card rounded-[18px] border border-line bg-paper p-5 cursor-pointer transition relative';
            checkProbono.classList.remove('hidden');
            checkReguler.classList.add('hidden');
            panelEkonomi.classList.remove('hidden');

            // Cek apakah akun lolos pro bono
            if (statusEkonomiUser !== 'pro_bono') {
                btnNext2.disabled = true;
            } else {
                btnNext2.disabled = false;
            }
        }
    }

    function updateCharCount(textarea) {
        const count = textarea.value.length;
        const countEl = document.getElementById('char-count');
        if (count >= 20) {
            countEl.textContent = count + ' karakter (Memenuhi syarat)';
            countEl.className = 'text-[11px] font-bold text-success';
        } else {
            countEl.textContent = count + ' / 20 karakter';
            countEl.className = 'text-[11px] text-ink-soft';
        }
    }

    function filterAdvokatList() {
        const query = document.getElementById('cari-advokat-input').value.toLowerCase();
        document.querySelectorAll('.advokat-card').forEach(card => {
            const nama = card.getAttribute('data-nama').toLowerCase();
            const wilayah = card.getAttribute('data-wilayah').toLowerCase();
            if (nama.includes(query) || wilayah.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initPage);
</script>
@endsection
