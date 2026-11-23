@extends('layouts.app')

@section('content')
    {{-- Alert Notifikasi Sukses / Pesan Status --}}
    @if (session('success'))
        <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
            <div class="rounded-[14px] border border-emerald-200 bg-emerald-50 p-4 text-sm text-success flex items-center gap-3">
                <svg class="h-5 w-5 text-success shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- 1. HEADER + FILTER AREA DENGAN HEADER-GLOW SESUAI baselineui.md --}}
    <section class="header-glow pt-8 pb-10 sm:pt-12 sm:pb-14 border-b border-line">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Judul 1 Baris dengan Aksen Vermilion Italic (.em) --}}
            <div class="max-w-3xl">
                <h1 class="font-display text-3xl font-bold tracking-tight text-ink sm:text-4xl lg:text-5xl leading-tight">
                    Cari advokat sesuai <span class="em">kasus</span> Anda
                </h1>
                <p class="mt-2 text-sm sm:text-base text-ink-soft">
                    Daftar Advokat Terverifikasi yang siap memberikan konsultasi awal dan pendampingan hukum terpercaya.
                </p>
            </div>

            {{-- Form Filter Kasus & Kebutuhan --}}
            <form action="{{ route('home') }}" method="GET" class="mt-6 space-y-4">
                {{-- Chip Filter Kasus (Scroll Horizontal) --}}
                @php
                    $bidangAktif = request('bidang', 'semua');
                    $daftarBidang = collect([
                        (object)['nama' => 'semua', 'label' => 'Semua Kasus'],
                    ]);

                    if (isset($bidangHukumList) && $bidangHukumList->isNotEmpty()) {
                        foreach ($bidangHukumList as $b) {
                            $daftarBidang->push((object)['nama' => $b->nama, 'label' => $b->nama]);
                        }
                    } else {
                        $daftarBidang->push((object)['nama' => 'Sengketa Tanah & Desa', 'label' => 'Tanah & Desa']);
                        $daftarBidang->push((object)['nama' => 'Hukum Keluarga & Waris', 'label' => 'Keluarga & Waris']);
                        $daftarBidang->push((object)['nama' => 'Ketenagakerjaan', 'label' => 'Kerja']);
                        $daftarBidang->push((object)['nama' => 'Hukum Pidana', 'label' => 'Pidana']);
                        $daftarBidang->push((object)['nama' => 'Bisnis & UMKM', 'label' => 'Bisnis & UMKM']);
                    }
                @endphp

                <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                    @foreach ($daftarBidang as $item)
                        @php
                            $isAktif = ($bidangAktif === $item->nama) || ($bidangAktif === 'semua' && $item->nama === 'semua');
                        @endphp
                        <a
                            href="{{ route('home', array_merge(request()->query(), ['bidang' => $item->nama === 'semua' ? null : $item->nama])) }}"
                            class="shrink-0 rounded-full px-4 py-2 text-xs sm:text-sm font-semibold transition {{ $isAktif ? 'bg-accent text-white shadow-btn' : 'bg-paper border border-line text-ink hover:border-accent' }}"
                        >
                            {{ $item->label }}
                        </a>
                    @endforeach
                </div>

                {{-- Baris Filter Tambahan: Wilayah, Pro Bono, Urutkan --}}
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    {{-- Filter Wilayah --}}
                    <div class="relative">
                        <select
                            name="wilayah"
                            onchange="this.form.submit()"
                            class="rounded-full border border-line bg-paper px-4 py-2 pr-8 text-xs font-semibold text-ink appearance-none focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent-soft cursor-pointer"
                        >
                            <option value="">Semua Wilayah</option>
                            @if (isset($wilayahList) && $wilayahList->isNotEmpty())
                                @foreach ($wilayahList as $w)
                                    <option value="{{ $w->nama }}" {{ request('wilayah') == $w->nama ? 'selected' : '' }}>
                                        {{ $w->nama }}
                                    </option>
                                @endforeach
                            @else
                                <option value="Bandung" {{ request('wilayah') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                                <option value="Jakarta" {{ request('wilayah') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                                <option value="Surabaya" {{ request('wilayah') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                            @endif
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-ink-soft">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </div>

                    {{-- Toggle Pro Bono --}}
                    <label class="flex items-center gap-2 rounded-full border border-line bg-paper px-4 py-2 text-xs font-semibold text-ink cursor-pointer hover:border-accent">
                        <input
                            type="checkbox"
                            name="pro_bono"
                            value="1"
                            {{ request()->boolean('pro_bono') ? 'checked' : '' }}
                            onchange="this.form.submit()"
                            class="h-3.5 w-3.5 rounded border-line text-accent focus:ring-accent-soft"
                        >
                        <span>Pro bono tersedia</span>
                    </label>

                    {{-- Urutkan --}}
                    <div class="relative">
                        <select
                            name="urutkan"
                            onchange="this.form.submit()"
                            class="rounded-full border border-line bg-paper px-4 py-2 pr-8 text-xs font-semibold text-ink appearance-none focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent-soft cursor-pointer"
                        >
                            <option value="terbaru" {{ request('urutkan') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="tarif_terendah" {{ request('urutkan') == 'tarif_terendah' ? 'selected' : '' }}>Tarif Terendah</option>
                            <option value="tarif_tertinggi" {{ request('urutkan') == 'tarif_tertinggi' ? 'selected' : '' }}>Tarif Tertinggi</option>
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-ink-soft">
                            <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </div>

                    @if (request()->hasAny(['bidang', 'wilayah', 'pro_bono', 'urutkan']))
                        <a
                            href="{{ route('home') }}"
                            class="rounded-full border border-line bg-ivory px-3.5 py-2 text-xs font-semibold text-ink-soft hover:text-accent transition"
                        >
                            Reset filter
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </section>

    {{-- 2. DAFTAR CARD ADVOKAT SESUAI baselineui.md --}}
    <section class="py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
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
                            $ratingAvg = $advokat->ulasan->avg('rating');
                            $totalUlasan = $advokat->ulasan->count();
                        @endphp

                        <div class="rounded-[20px] border border-line bg-paper p-5 shadow-card transition hover:-translate-y-0.5 flex flex-col justify-between">
                            <div>
                                {{-- Header Card: Foto / Inisial & Nama --}}
                                <div class="flex items-start gap-3.5">
                                    {{-- Fallback Inisial (Fraunces serif) --}}
                                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-[16px] bg-accent-soft text-accent font-display font-bold text-xl">
                                        {{ $inisial }}
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <h2 class="font-display text-base sm:text-lg font-semibold text-ink leading-snug">
                                                {{ $namaAdvokat }}
                                            </h2>
                                        </div>

                                        {{-- Badge Terverifikasi & Pro Bono --}}
                                        <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-semibold text-success border border-emerald-200">
                                                <svg class="h-3 w-3 text-success shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                </svg>
                                                <span>Terverifikasi</span>
                                            </span>

                                            @if ($advokat->kuota_probono > 0)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-accent-soft px-2 py-0.5 text-[11px] font-bold text-accent-dark">
                                                    <svg class="h-3 w-3 text-accent shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                    </svg>
                                                    <span>Pro Bono: Sisa {{ $advokat->kuota_probono }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Bidang Keahlian: Maksimal 2 Chip + '+n' --}}
                                <div class="mt-3.5 flex flex-wrap items-center gap-1.5">
                                    @foreach (array_slice($bidangNames, 0, 2) as $bidang)
                                        <span class="rounded-full bg-ivory border border-line px-2.5 py-0.5 text-xs text-ink-soft font-medium">
                                            {{ $bidang }}
                                        </span>
                                    @endforeach
                                    @if (count($bidangNames) > 2)
                                        <span class="rounded-full bg-ivory border border-line px-2 py-0.5 text-[11px] text-ink-soft font-medium">
                                            +{{ count($bidangNames) - 2 }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Wilayah Domisili / Praktik --}}
                                <p class="mt-2.5 text-xs text-ink-soft flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-ink-soft/70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <span class="truncate">{{ $wilayahText }}</span>
                                </p>

                                {{-- Rating --}}
                                <div class="mt-2 text-xs text-ink-soft flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-amber-500 fill-amber-500 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                                    </svg>
                                    @if ($totalUlasan > 0)
                                        <span class="font-semibold text-ink">{{ number_format($ratingAvg, 1) }}</span>
                                        <span>({{ $totalUlasan }} ulasan)</span>
                                    @else
                                        <span>Belum ada ulasan</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Footer Card: Tarif & Tombol Konsultasi --}}
                            <div class="mt-5 pt-3.5 border-t border-line flex items-center justify-between gap-2">
                                <div>
                                    <span class="block text-[11px] text-ink-soft leading-tight">Tarif</span>
                                    <span class="text-xs sm:text-sm font-bold text-ink">
                                        Rp {{ number_format($advokat->tarif_konsultasi, 0, ',', '.') }} / sesi
                                    </span>
                                </div>

                                <a
                                    href="{{ auth()->check() ? url('/pro-bono') : route('login') }}"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-4.5 py-2 text-xs font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                                >
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-[20px] border border-line bg-paper p-10 text-center max-w-md mx-auto shadow-card">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent-soft text-accent mb-3">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-semibold text-base text-ink">Belum ada advokat untuk kasus ini</h3>
                    <p class="text-xs text-ink-soft mt-1">Silakan sesuaikan pilihan kasus atau reset filter pencarian Anda.</p>
                    <div class="mt-4">
                        <a href="{{ route('home') }}" class="inline-flex rounded-full border border-line bg-ivory px-4 py-2 text-xs font-semibold text-ink hover:border-accent">
                            Reset filter
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- 3. PANDUAN SINGKAT WARGA (3 LANGKAH BESAR) SESUAI baselineui.md --}}
    <section class="py-12 sm:py-16 border-t border-line bg-paper">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-accent">Langkah Praktis</span>
                    <h2 class="mt-1 font-display text-2xl sm:text-3xl font-bold text-ink">
                        Panduan singkat warga
                    </h2>
                </div>
                <div>
                    <a href="{{ route('kamus.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-accent hover:text-accent-dark transition">
                        <span>Lihat Kamus Hukum</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Langkah 01 --}}
                <div class="border-t border-line pt-5">
                    <span class="font-display text-4xl sm:text-5xl font-bold text-accent">01</span>
                    <h3 class="mt-3 font-display text-base sm:text-lg font-bold text-ink">
                        Pilih advokat sesuai kasus
                    </h3>
                    <p class="mt-1.5 text-xs sm:text-sm text-ink-soft leading-relaxed">
                        Cari praktisi hukum berlisensi resmi berdasarkan keahlian perkara dan wilayah tempat tinggal Anda.
                    </p>
                </div>

                {{-- Langkah 02 --}}
                <div class="border-t border-line pt-5">
                    <span class="font-display text-4xl sm:text-5xl font-bold text-accent">02</span>
                    <h3 class="mt-3 font-display text-base sm:text-lg font-bold text-ink">
                        Ajukan konsultasi awal
                    </h3>
                    <p class="mt-1.5 text-xs sm:text-sm text-ink-soft leading-relaxed">
                        Kirimkan ringkasan kronologi masalah hukum Anda secara tertib, baik melalui jalur reguler maupun pro bono.
                    </p>
                </div>

                {{-- Langkah 03 --}}
                <div class="border-t border-line pt-5">
                    <span class="font-display text-4xl sm:text-5xl font-bold text-accent">03</span>
                    <h3 class="mt-3 font-display text-base sm:text-lg font-bold text-ink">
                        Mulai chat setelah disetujui
                    </h3>
                    <p class="mt-1.5 text-xs sm:text-sm text-ink-soft leading-relaxed">
                        Lakukan komunikasi konsultasi awal dua arah secara privat langsung dengan advokat terkait.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. TENTANG KONSULAW (RINGKAS) SESUAI baselineui.md --}}
    <section class="py-10 sm:py-12 border-t border-line bg-ivory">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="text-sm sm:text-base text-ink leading-relaxed">
                    <strong>Konsulaw</strong> menghubungkan warga dengan advokat terverifikasi. Informasi di sini bersifat edukasi dan bukan pengganti konsultasi advokat.
                </p>
            </div>
        </div>
    </section>

    {{-- INFORMASI BATASAN LAYANAN (PBI-08 & Bab 1.3 Proposal) --}}
    @include('components.scope-limitations')
@endsection
