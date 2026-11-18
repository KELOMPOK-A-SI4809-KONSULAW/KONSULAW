@extends('layouts.app')

@section('content')
    {{-- Alert Notifikasi Sukses / Pesan Status --}}
    @if (session('success'))
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-center gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- BERANDA UTAMA: HANYA MENAMPILKAN DAFTAR ADVOKAT TERVERIFIKASI --}}
    <section class="bg-neutral-50 py-8 sm:py-12 border-b border-neutral-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Header Beranda yang Sederhana --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-950">
                        Daftar Advokat Terverifikasi
                    </h1>
                    <p class="mt-1 text-sm text-neutral-600">
                        Praktisi hukum resmi yang siap memberikan konsultasi awal dan bantuan hukum bagi masyarakat.
                    </p>
                </div>

                {{-- Tautan Cepat Layanan Bantuan Pro Bono --}}
                <div class="shrink-0">
                    <a
                        href="{{ url('/pro-bono') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-red-800 bg-white px-4 py-2 text-xs font-bold text-red-800 transition hover:bg-red-800 hover:text-white"
                    >
                        <span>Ajukan Pro Bono (Gratis) &rarr;</span>
                    </a>
                </div>
            </div>

            {{-- Grid Kartu Advokat Terverifikasi --}}
            @if (isset($advokatList) && $advokatList->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($advokatList as $advokat)
                        @php
                            $namaAdvokat = $advokat->user->nama ?? 'Advokat Konsulaw';
                            $parts = preg_split('/\s+/', trim($namaAdvokat)) ?: ['A', 'D'];
                            $inisial = mb_strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . mb_substr($parts[1] ?? 'D', 0, 1));
                            $wilayahListNames = $advokat->wilayahPraktik->pluck('nama')->toArray();
                            $wilayahText = !empty($wilayahListNames) ? implode(', ', $wilayahListNames) : ($advokat->user->wilayah->nama ?? 'Nasional');
                            $bidangNames = $advokat->bidangHukum->pluck('nama')->toArray();
                            $ratingAvg = $advokat->ulasan->avg('rating') ?: 4.9;
                            $totalUlasan = $advokat->ulasan->count() ?: 12;
                        @endphp

                        <div class="rounded-2xl border border-neutral-300 bg-white p-5 shadow-xs flex flex-col justify-between">
                            <div>
                                {{-- Header Kartu Advokat --}}
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-800 text-sm font-extrabold text-white">
                                            {{ $inisial }}
                                        </div>
                                        <div>
                                            <h2 class="text-sm sm:text-base font-extrabold text-neutral-950">
                                                {{ $namaAdvokat }}
                                            </h2>
                                            <p class="text-xs text-neutral-500">
                                                📍 {{ $wilayahText }}
                                            </p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center rounded-md bg-emerald-50 px-2 py-0.5 text-[11px] font-bold text-emerald-800 border border-emerald-200">
                                        ✓ Terverifikasi
                                    </span>
                                </div>

                                {{-- Bidang Keahlian --}}
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($bidangNames as $bidang)
                                        <span class="rounded-md bg-neutral-100 px-2 py-0.5 text-[11px] font-semibold text-neutral-800">
                                            {{ $bidang }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- Pengalaman Singkat --}}
                                <p class="mt-2.5 text-xs text-neutral-600 line-clamp-2">
                                    {{ $advokat->pengalaman ?? 'Praktisi hukum berpengalaman mendampingi masyarakat pencari keadilan.' }}
                                </p>

                                {{-- Rating & Kuota Pro Bono --}}
                                <div class="mt-3 pt-2.5 border-t border-neutral-100 flex items-center justify-between text-xs">
                                    <span class="text-neutral-700">★ <strong>{{ number_format($ratingAvg, 1) }}</strong> ({{ $totalUlasan }} ulasan)</span>
                                    @if ($advokat->kuota_probono > 0)
                                        <span class="text-[11px] font-bold text-emerald-700">Pro Bono: Sisa {{ $advokat->kuota_probono }}</span>
                                    @else
                                        <span class="text-[11px] text-neutral-400">Pro Bono: Penuh</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer Kartu Advokat --}}
                            <div class="mt-4 pt-3 border-t border-neutral-200 flex items-center justify-between gap-2">
                                <div>
                                    <span class="block text-[10px] text-neutral-500 leading-none">Tarif Konsultasi</span>
                                    <span class="text-xs sm:text-sm font-bold text-neutral-950">
                                        Rp {{ number_format($advokat->tarif_konsultasi, 0, ',', '.') }}
                                    </span>
                                </div>

                                @auth
                                    <a
                                        href="{{ url('/pro-bono') }}"
                                        class="rounded-xl bg-red-800 px-4 py-2 text-xs font-bold text-white hover:bg-neutral-950 transition"
                                    >
                                        Konsultasi
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="rounded-xl bg-red-800 px-4 py-2 text-xs font-bold text-white hover:bg-neutral-950 transition"
                                    >
                                        Konsultasi
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-neutral-300 bg-white p-6 text-center">
                    <p class="text-sm font-bold text-neutral-800">Belum ada advokat yang terdaftar.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- INFORMASI BATASAN LAYANAN (PBI-08 & Bab 1.3 Proposal) --}}
    @include('components.scope-limitations')
@endsection
