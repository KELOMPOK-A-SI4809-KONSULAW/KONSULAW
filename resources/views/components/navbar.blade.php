{{-- Komponen Top Bar / Navbar Utama — Soft Editorial sesuai baselineui.md --}}
<header class="sticky top-0 z-40 bg-ivory/90 backdrop-blur-md border-b border-line transition-all">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="Navigasi Utama">
        {{-- Logo Konsulaw --}}
        <a href="{{ url('/') }}" class="group flex items-center gap-2.5 focus:outline-none">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white shadow-btn transition group-hover:bg-accent-dark">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 5.491Z" />
                </svg>
            </div>
            <div>
                <span class="font-display text-2xl font-bold tracking-tight text-ink">
                    Konsu<span class="text-accent">law</span>
                </span>
            </div>
        </a>

        {{-- Menu Navigasi Desktop: berbeda untuk advokat vs tamu/masyarakat --}}
        <div class="hidden items-center gap-1.5 md:flex lg:gap-3">
            <a
                href="{{ route('home') }}"
                class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('home') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
            >
                Beranda
            </a>

            @auth
                @if(auth()->user()->role === 'admin')
                    {{-- Navigasi khusus Administrator --}}
                    <a
                        href="{{ route('admin.profil.edit', ['tab' => 'verifikasi-advokat']) }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->is('admin/profil*') && request('tab') === 'verifikasi-advokat' ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Verifikasi Advokat
                    </a>
                    <a
                        href="{{ route('kamus.index') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('kamus.*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Kamus Hukum
                    </a>
                    <a
                        href="{{ route('admin.profil.edit') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->is('admin/profil*') && request('tab') !== 'verifikasi-advokat' ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Profil Admin
                    </a>
                @elseif(auth()->user()->role === 'advokat')
                    {{-- Navigasi khusus Advokat --}}
                    <a
                        href="{{ route('advokat.dashboard') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('advokat.dashboard') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Dasbor
                    </a>
                    <a
                        href="{{ route('kamus.index') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('kamus.*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Kamus Hukum
                    </a>
                    <a
                        href="{{ route('advokat.profil.edit') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->is('advokat/profil*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Profil Saya
                    </a>
                @else
                    {{-- Navigasi untuk Masyarakat (B4) --}}
                    <a
                        href="{{ route('advokat.index') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('advokat.*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Cari Advokat
                    </a>
                    <a
                        href="{{ route('kamus.index') }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('kamus.*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Kamus Hukum
                    </a>
                    <a
                        href="{{ route('masyarakat.profil.edit', ['tab' => 'konsultasi-saya']) }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->is('masyarakat/profil*') && request('tab') === 'konsultasi-saya' ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Konsultasi Saya
                    </a>
                    <a
                        href="{{ route('masyarakat.profil.edit', ['tab' => 'status-layanan']) }}"
                        class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->is('masyarakat/profil*') && request('tab') === 'status-layanan' ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                    >
                        Status Layanan
                    </a>
                @endif
            @else
                {{-- Navigasi Tamu --}}
                <a
                    href="{{ route('advokat.index') }}"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('advokat.*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                >
                    Cari Advokat
                </a>
                <a
                    href="{{ route('kamus.index') }}"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->routeIs('kamus.*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                >
                    Kamus Hukum
                </a>
                <a
                    href="{{ url('/pro-bono') }}"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition {{ request()->is('pro-bono*') ? 'text-accent bg-accent-soft' : 'text-ink-soft hover:text-accent hover:bg-white/60' }}"
                >
                    Layanan Pro Bono
                </a>
            @endauth
        </div>

        {{-- Sisi Kanan: Status Pengguna --}}
        <div class="flex items-center gap-2">
            @auth
                @php
                    $namaUser = auth()->user()->nama ?? auth()->user()->name ?? 'Pengguna';
                    $bagianNama = preg_split('/\s+/', trim($namaUser)) ?: ['A', 'N'];
                    $inisialUser = mb_strtoupper(mb_substr($bagianNama[0] ?? 'A', 0, 1) . mb_substr($bagianNama[1] ?? 'N', 0, 1));
                @endphp
                <div class="flex items-center gap-2 rounded-full border border-neutral-300 bg-neutral-100 py-1 pr-2.5 pl-1.5 shadow-2xs">
                    <a href="{{ route('profil.index') }}" class="flex items-center gap-2 hover:opacity-85 transition" title="Buka Profil Saya">
                        @if(auth()->user()->foto_profil)
                            <img src="{{ asset('storage/' . auth()->user()->foto_profil) }}" alt="{{ $namaUser }}" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-accent text-xs font-bold text-white">
                                {{ $inisialUser }}
                            </div>
                        @endif
                        <div class="hidden text-left sm:block">
                            <p class="text-xs font-bold text-neutral-950 leading-none">{{ $namaUser }}</p>
                            <p class="mt-0.5 text-[10px] font-extrabold uppercase tracking-wider {{ auth()->user()->role === 'admin' ? 'text-accent font-black' : (auth()->user()->role === 'advokat' ? 'text-amber-800' : 'text-ink-soft') }}">
                                {{ auth()->user()->role ?? 'masyarakat' }}
                            </p>
                        </div>
                    </a>

                    {{-- Tombol Logout / Keluar --}}
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-1">
                        @csrf
                        <button
                            type="submit"
                            class="flex h-7 w-7 items-center justify-center rounded-full text-ink-soft transition hover:bg-accent-soft hover:text-accent cursor-pointer"
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
                        class="rounded-full border border-line bg-paper px-4 py-1.5 text-xs sm:text-sm font-semibold text-ink transition hover:border-accent hover:text-accent"
                    >
                        Masuk
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="rounded-full bg-accent px-4.5 py-1.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark"
                    >
                        Daftar Akun
                    </a>
                </div>
            @endauth
        </div>
    </nav>
</header>
