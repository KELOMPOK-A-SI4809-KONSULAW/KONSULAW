{{-- Komponen Navbar Utama Konsulaw — Merah Maron & Putih Bersih (NFR-04) --}}
<header class="sticky top-0 z-40 border-b border-neutral-300 bg-white">
    {{-- Baris Navigasi Utama --}}
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3.5 sm:px-6 lg:px-8" aria-label="Navigasi Utama">
        {{-- Logo Konsulaw --}}
        <a href="{{ url('/') }}" class="group flex items-center gap-3 focus:outline-none">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-800 text-white shadow-xs transition group-hover:bg-neutral-950">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <span class="block text-xl font-extrabold tracking-tight text-neutral-950">
                    Konsu<span class="text-red-800">law</span>
                </span>
                <span class="hidden text-[11px] font-medium text-neutral-500 sm:block">
                    Bantuan Hukum &amp; Layanan Publik
                </span>
            </div>
        </a>

        {{-- Menu Navigasi Sederhana: Beranda, Cari Advokat, Kamus Hukum, Pro Bono --}}
        <div class="hidden items-center gap-1 sm:flex lg:gap-3">
            <a
                href="{{ route('home') }}"
                class="rounded-xl px-3.5 py-2 text-sm font-bold {{ request()->routeIs('home') ? 'text-red-800 bg-red-50' : 'text-neutral-700 hover:text-red-800 hover:bg-neutral-100' }} transition"
            >
                Beranda
            </a>
            <a
                href="{{ route('advokat.index') }}"
                class="rounded-xl px-3.5 py-2 text-sm font-bold {{ request()->routeIs('advokat.*') ? 'text-red-800 bg-red-50' : 'text-neutral-700 hover:text-red-800 hover:bg-neutral-100' }} transition"
            >
                Cari Advokat
            </a>
            <a
                href="{{ route('kamus.index') }}"
                class="rounded-xl px-3.5 py-2 text-sm font-bold {{ request()->routeIs('kamus.*') ? 'text-red-800 bg-red-50' : 'text-neutral-700 hover:text-red-800 hover:bg-neutral-100' }} transition"
            >
                Kamus Hukum
            </a>
            <a
                href="{{ url('/pro-bono') }}"
                class="rounded-xl px-3.5 py-2 text-sm font-bold {{ request()->is('pro-bono*') ? 'text-red-800 bg-red-50' : 'text-neutral-700 hover:text-red-800 hover:bg-neutral-100' }} transition"
            >
                Layanan Pro Bono
            </a>
        </div>

        {{-- Sisi Kanan: Tombol Masuk / Daftar (Tamu) atau Akun Pengguna (Login) --}}
        <div class="flex items-center gap-2">
            @auth
                @php
                    $namaUser = auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna';
                    $bagianNama = preg_split('/\s+/', trim($namaUser)) ?: ['A', 'N'];
                    $inisialUser = mb_strtoupper(mb_substr($bagianNama[0] ?? 'A', 0, 1) . mb_substr($bagianNama[1] ?? 'N', 0, 1));
                @endphp
                <div class="flex items-center gap-2 rounded-full border border-neutral-300 bg-neutral-100 py-1 pr-2.5 pl-1.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-800 text-xs font-bold text-white">
                        {{ $inisialUser }}
                    </div>
                    <div class="hidden text-left sm:block">
                        <p class="text-xs font-bold text-neutral-950 leading-none">{{ $namaUser }}</p>
                        <p class="mt-0.5 text-[10px] font-extrabold uppercase tracking-wider text-red-800">
                            {{ auth()->user()->role ?? 'masyarakat' }}
                        </p>
                    </div>

                    {{-- Tombol Logout / Keluar --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                        @csrf
                        <button
                            type="submit"
                            class="flex h-7 w-7 items-center justify-center rounded-full text-neutral-500 transition hover:bg-red-100 hover:text-red-800 cursor-pointer"
                            title="Keluar dari akun Konsulaw"
                            aria-label="Keluar / Logout"
                        >
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('login') }}"
                        class="rounded-xl border border-neutral-300 bg-white px-3.5 py-2 text-xs sm:text-sm font-bold text-neutral-800 transition hover:text-red-800 hover:border-red-800"
                    >
                        Masuk
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl bg-red-800 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-xs transition hover:bg-neutral-950"
                    >
                        Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </nav>
</header>
