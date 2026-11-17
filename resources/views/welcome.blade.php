@extends('layouts.app')

@section('content')
    {{-- 1. BERANDA & DASHBOARD UTAMA (Responsif Mobile-ke-Desktop — Sesuai Sketsa Bab 4.5.1 Proposal & NFR-04) --}}
    <section
        id="kotak-pencarian-utama"
        class="relative overflow-hidden border-b border-neutral-200 bg-gradient-to-b from-white via-red-50/30 to-neutral-50 pt-6 pb-10 sm:pt-12 sm:pb-16"
    >
        {{-- Ornamen Lingkaran Sesuai Sketsa Wireframe Beranda di Laporan --}}
        <div
            class="pointer-events-none absolute -top-16 -right-16 h-64 w-64 rounded-full border border-red-200/80 bg-red-100/20 sm:h-96 sm:w-96"
            aria-hidden="true"
        ></div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Layout Mobile-First (1 Kolom di HP -> 12 Kolom Dashboard di Desktop) --}}
            <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-10">
                {{-- Bagian Atas pada Mobile / Kolom Kiri pada Desktop: Judul, Ajakan, & Kata Kunci Populer --}}
                <div class="lg:col-span-7">
                    <p class="inline-flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3.5 py-1 text-[11px] font-bold uppercase tracking-widest text-red-800 sm:text-xs">
                        <span class="h-2 w-2 rounded-full bg-red-700"></span>
                        BANTUAN HUKUM SAMPAI KE DESA
                    </p>

                    <h1 class="mt-4 text-2xl font-extrabold leading-tight tracking-tight text-neutral-950 sm:text-4xl lg:text-5xl lg:leading-[1.15]">
                        Masalah tanah, kerja, atau keluarga? Temukan advokat yang
                        <span class="text-red-700 underline decoration-red-600/60 decoration-2 underline-offset-4">benar-benar terverifikasi.</span>
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm leading-6 text-neutral-600 sm:mt-4 sm:text-lg sm:leading-7">
                        Bandingkan keahlian, wilayah praktik, dan tarif. Ajukan <strong class="text-neutral-950">pro bono</strong> bila memenuhi syarat.
                    </p>

                    {{-- Deretan Kata Kunci Populer (Sesuai Bab 4.5.1 Laporan Proposal) --}}
                    <div class="mt-5 sm:mt-6">
                        <p class="text-xs font-bold uppercase tracking-wider text-neutral-500">
                            Kata Kunci Populer:
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach (['Sengketa batas sawah', 'Tanah waris & Letter C', 'PHK & pesangon', 'Wanprestasi', 'Keluarga & waris', 'Bantuan pro bono'] as $kataKunci)
                                <span class="inline-flex items-center rounded-full border border-neutral-300 bg-white px-3 py-1 text-xs font-medium text-neutral-800 shadow-2xs">
                                    {{ $kataKunci }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Bagian Bawah pada Mobile / Kolom Kanan pada Desktop: Kotak Pencarian Sesuai Sketsa Mockup Bab 4.5.1 --}}
                <div class="lg:col-span-5">
                    <div class="rounded-2xl border-2 border-neutral-900 bg-white p-4 shadow-md sm:p-6">
                        <form action="#pintu-masuk-layanan" method="GET" class="space-y-3.5">
                            {{-- Kotak Input Pencarian ("Contoh: sengketa batas sawah") --}}
                            <div class="rounded-xl border border-neutral-300 bg-neutral-50 px-3.5 py-2.5 focus-within:border-red-700 focus-within:bg-white">
                                <label for="input-masalah" class="sr-only">Cari masalah hukum</label>
                                <div class="flex items-center gap-2.5">
                                    <svg class="h-4 w-4 shrink-0 text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                    </svg>
                                    <input
                                        id="input-masalah"
                                        type="text"
                                        name="q"
                                        placeholder="Contoh: sengketa batas sawah"
                                        class="w-full bg-transparent text-sm text-neutral-950 placeholder:text-neutral-500 focus:outline-none"
                                    >
                                </div>
                            </div>

                            {{-- Grid 2 Kolom: Wilayah (Kab. Karo) & Bidang (Pertanahan) Persis Sketsa Mockup --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-xl border border-neutral-300 bg-neutral-50 p-2.5">
                                    <label for="pilih-wilayah" class="block text-[11px] font-bold text-neutral-500">
                                        Wilayah
                                    </label>
                                    <select
                                        id="pilih-wilayah"
                                        name="wilayah"
                                        class="mt-0.5 block w-full bg-transparent text-sm font-bold text-neutral-950 focus:outline-none"
                                    >
                                        <option value="Kab. Karo" selected>Kab. Karo</option>
                                        <option value="Kabupaten Bandung">Kab. Bandung</option>
                                        <option value="Kota Jakarta Selatan">Jakarta Selatan</option>
                                        <option value="Kupang">Kupang</option>
                                        <option value="Kab. Garut">Kab. Garut</option>
                                    </select>
                                </div>

                                <div class="rounded-xl border border-neutral-300 bg-neutral-50 p-2.5">
                                    <label for="pilih-bidang" class="block text-[11px] font-bold text-neutral-500">
                                        Bidang
                                    </label>
                                    <select
                                        id="pilih-bidang"
                                        name="bidang"
                                        class="mt-0.5 block w-full bg-transparent text-sm font-bold text-neutral-950 focus:outline-none"
                                    >
                                        <option value="Pertanahan" selected>Pertanahan</option>
                                        <option value="Perdata">Perdata</option>
                                        <option value="Ketenagakerjaan">Ketenagakerjaan</option>
                                        <option value="Keluarga & Waris">Keluarga &amp; Waris</option>
                                        <option value="Pidana">Pidana</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Tombol Cari Advokat --}}
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-800 px-5 py-3 text-sm font-bold text-white shadow-xs transition hover:bg-neutral-950 cursor-pointer"
                            >
                                <span>Cari Advokat</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- 2. TIGA PINTU MASUK UTAMA & BANNER PRO BONO (Mobile 1 Kolom -> Desktop 3 Kolom Sesuai Bab 4.5.1 Proposal) --}}
            <div id="pintu-masuk-layanan" class="mt-10 scroll-mt-24 sm:mt-12">
                <div class="flex flex-col justify-between gap-1 sm:flex-row sm:items-end">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-red-700">
                            Akses Layanan Utama
                        </span>
                        <h2 class="mt-0.5 text-lg font-extrabold tracking-tight text-neutral-950 sm:text-2xl">
                            Tiga Pintu Masuk Utama Konsulaw
                        </h2>
                    </div>
                    <p class="text-xs text-neutral-500">
                        Responsif dari tampilan Mobile ke Desktop (NFR-04)
                    </p>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                    {{-- Pintu 1: Direktori Advokat --}}
                    <div class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-5 shadow-2xs transition hover:border-red-700 hover:shadow-md sm:p-6">
                        <div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-800 text-white sm:h-11 sm:w-11">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                            </div>
                            <h3 class="mt-3.5 text-base font-bold text-neutral-950">
                                1. Direktori Advokat
                            </h3>
                            <p class="mt-1 text-xs leading-5 text-neutral-600">
                                Daftar advokat terverifikasi (Ijazah S.H., BAS, dan KTA) beserta informasi bidang keahlian, wilayah praktik, rating, sisa kuota pro bono, dan tarif reguler.
                            </p>
                        </div>
                        <span class="mt-4 inline-flex items-center text-xs font-bold text-red-700">
                            Pintu Masuk Direktori &rarr;
                        </span>
                    </div>

                    {{-- Pintu 2: Kamus & Peraturan --}}
                    <div class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-5 shadow-2xs transition hover:border-red-700 hover:shadow-md sm:p-6">
                        <div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-950 text-white sm:h-11 sm:w-11">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <h3 class="mt-3.5 text-base font-bold text-neutral-950">
                                2. Kamus &amp; Peraturan
                            </h3>
                            <p class="mt-1 text-xs leading-5 text-neutral-600">
                                Penjelasan istilah hukum populer dan direktori peraturan perundang-undangan dengan ringkasan bahasa sehari-hari yang mudah dipahami masyarakat.
                            </p>
                        </div>
                        <span class="mt-4 inline-flex items-center text-xs font-bold text-red-700">
                            Pintu Masuk Literasi Hukum &rarr;
                        </span>
                    </div>

                    {{-- Pintu 3: Konsultasi Awal --}}
                    <div class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-5 shadow-2xs transition hover:border-red-700 hover:shadow-md sm:p-6">
                        <div>
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-800 sm:h-11 sm:w-11">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                            </div>
                            <h3 class="mt-3.5 text-base font-bold text-neutral-950">
                                3. Konsultasi Awal
                            </h3>
                            <p class="mt-1 text-xs leading-5 text-neutral-600">
                                Pengajuan permohonan konsultasi hukum pra-litigasi dua arah secara daring baik melalui jalur layanan reguler maupun bantuan pro bono.
                            </p>
                        </div>
                        <span class="mt-4 inline-flex items-center text-xs font-bold text-red-700">
                            Pintu Masuk Konsultasi &rarr;
                        </span>
                    </div>
                </div>

                {{-- Banner Informasi Pro Bono (Sesuai Bab 4.5.1 Proposal: Mengarahkan Masyarakat Mengunggah Dokumen Status Ekonomi) --}}
                <div class="mt-6 overflow-hidden rounded-3xl border-2 border-red-800 bg-gradient-to-r from-neutral-950 via-neutral-900 to-red-950 p-5 text-white shadow-md sm:mt-8 sm:p-8">
                    <div class="grid grid-cols-1 items-center gap-5 lg:grid-cols-12">
                        <div class="lg:col-span-8">
                            <span class="inline-flex items-center rounded-full bg-red-800 px-3 py-1 text-[11px] font-bold text-white sm:text-xs">
                                Informasi Layanan Bantuan Hukum Gratis (Pro Bono)
                            </span>
                            <h3 class="mt-2 text-lg font-extrabold tracking-tight sm:text-2xl">
                                Butuh Bantuan Hukum Cuma-Cuma? Unggah Dokumen Status Ekonomi
                            </h3>
                            <p class="mt-1.5 max-w-2xl text-xs leading-5 text-neutral-200 sm:text-sm sm:leading-6">
                                Masyarakat kurang mampu di wilayah desa maupun kota dapat mengajukan verifikasi status ekonomi dengan mengunggah dokumen pendukung untuk mengakses kuota konsultasi <em>pro bono</em>.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row lg:col-span-4 lg:justify-end">
                            <a
                                href="{{ url('/pro-bono') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-red-700 px-5 py-3 text-sm font-bold text-white shadow-xs transition hover:bg-white hover:text-neutral-950"
                            >
                                Ajukan Verifikasi Ekonomi &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 3. PENAYANGAN INFORMASI BATASAN RUANG LINGKUP LAYANAN DI BERANDA (PBI-08 & Bab 1.3 Proposal) --}}
    @include('components.scope-limitations')
@endsection
