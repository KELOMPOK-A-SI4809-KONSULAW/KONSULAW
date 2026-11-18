{{-- Komponen Navbar Utama Responsif Konsulaw — Desktop & Mobile (NFR-04) --}}
<header class="sticky top-0 z-40 border-b border-neutral-200 bg-white/95 backdrop-blur-md">
    {{-- Bar Informasi Atas (Tampil penuh di Desktop, ringkas di Mobile) --}}
    <div class="bg-neutral-950 px-4 py-1.5 text-xs text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between gap-2">
            <div class="flex items-center gap-2 truncate">
                <span class="inline-flex shrink-0 items-center rounded-full bg-red-700 px-2.5 py-0.5 text-[11px] font-bold tracking-wide text-white">
                    SDGs 16
                </span>
                <span class="truncate font-medium text-neutral-200">
                    Platform Bantuan Hukum &amp; Keterbukaan Layanan Publik Kota dan Desa
                </span>
            </div>
            <div class="hidden shrink-0 items-center gap-4 md:flex">
                <a href="#batasan-layanan" class="text-neutral-300 underline-offset-4 transition hover:text-white hover:underline">
                    Batasan Ruang Lingkup Layanan (PBI-08)
                </a>
                <span class="text-neutral-700" aria-hidden="true">|</span>
                <a href="{{ url('/pro-bono') }}" class="font-semibold text-red-400 transition hover:text-red-300">
                    Verifikasi Status Pro Bono &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- Baris Navigasi Utama --}}
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8" aria-label="Navigasi Utama">
        {{-- Identitas Merek / Logo Konsulaw (Sesuai Sketsa Laporan Bab 4.5.1) --}}
        <a href="{{ url('/') }}" class="group flex items-center gap-3 focus:outline-none">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-800 bg-red-800 text-white shadow-xs transition group-hover:border-neutral-950 group-hover:bg-neutral-950">
                {{-- Ikon Perisai Terverifikasi (Shield Check) sesuai sketsa mockup --}}
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <span class="block text-xl font-extrabold tracking-tight text-neutral-950">
                    Konsu<span class="text-red-700 underline decoration-red-700 decoration-2 underline-offset-4">law</span>
                </span>
                <span class="hidden text-[11px] font-medium text-neutral-500 sm:block">
                    Advokat Terverifikasi &amp; Literasi Hukum
                </span>
            </div>
        </a>

        {{-- Menu Navigasi Desktop (Tampil pada layar Desktop md ke atas) --}}
        <div class="hidden items-center gap-1 md:flex lg:gap-2">
            <a
                href="{{ url('/') }}"
                class="rounded-lg px-3 py-2 text-sm font-semibold {{ request()->is('/') ? 'bg-red-50 text-red-800' : 'text-neutral-700 hover:bg-neutral-100 hover:text-neutral-950' }} transition"
            >
                Beranda
            </a>
            <a
                href="#kotak-pencarian-utama"
                class="rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 hover:text-red-800"
            >
                Cari Advokat
            </a>
            <a
                href="#pintu-masuk-layanan"
                class="rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 hover:text-red-800"
            >
                Konsultasi
            </a>
            <a
                href="#pintu-masuk-layanan"
                class="rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 hover:text-red-800"
            >
                Kamus &amp; Peraturan
            </a>
            <a
                href="{{ url('/pro-bono') }}"
                class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->is('pro-bono*') ? 'bg-red-50 text-red-800' : 'text-neutral-700 hover:bg-neutral-100 hover:text-red-800' }} transition"
            >
                Layanan Pro Bono
            </a>
            <a
                href="#batasan-layanan"
                class="rounded-lg px-3 py-2 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 hover:text-red-800"
            >
                Batasan Layanan
            </a>
        </div>

        {{-- Aksi Kanan: Ikon Notifikasi & Identitas Pengguna (Sesuai Mockup Laporan: Lonceng & Lingkaran "AN") --}}
        <div class="flex items-center gap-2.5 sm:gap-3">
            {{-- Tombol Notifikasi Dalam Aplikasi (TR-06) --}}
            <a
                href="#pintu-masuk-layanan"
                class="relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-neutral-300 bg-white text-neutral-800 transition hover:border-red-700 hover:bg-red-50 hover:text-red-800"
                aria-label="Notifikasi Status Konsultasi dan Verifikasi"
                title="Notifikasi Sistem"
            >
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <span class="absolute top-2 right-2.5 flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-500 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-red-700"></span>
                </span>
            </a>

            @auth
                @php
                    $namaUser = auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna';
                    $bagianNama = preg_split('/\s+/', trim($namaUser)) ?: ['A', 'N'];
                    $inisialUser = mb_strtoupper(mb_substr($bagianNama[0] ?? 'A', 0, 1) . mb_substr($bagianNama[1] ?? 'N', 0, 1));
                @endphp
                <div class="flex items-center gap-2.5 rounded-full border border-neutral-300 bg-neutral-100 py-1 pr-3 pl-1">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-800 text-xs font-bold text-white">
                        {{ $inisialUser }}
                    </div>
                    <div class="hidden text-left sm:block">
                        <p class="text-xs font-semibold text-neutral-950 leading-none">{{ $namaUser }}</p>
                        <p class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-red-700">
                            {{ auth()->user()->role ?? 'masyarakat' }}
                        </p>
                    </div>
                </div>
            @else
                <a
                    href="{{ url('/pro-bono') }}"
                    class="hidden items-center gap-1.5 rounded-xl bg-red-800 px-4 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-neutral-950 lg:inline-flex"
                >
                    <span>Ajukan Pro Bono</span>
                </a>
                {{-- Avatar Inisial Pengguna Sesuai Sketsa Mockup ("AN") --}}
                <a
                    href="{{ url('/pro-bono') }}"
                    class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-neutral-900 bg-neutral-950 text-xs font-bold text-white transition hover:border-red-700 hover:bg-red-800"
                    title="Akses Akun & Layanan Masyarakat"
                    aria-label="Profil Akun Pengguna"
                >
                    AN
                </a>
            @endauth
        </div>
    </nav>
</header>
