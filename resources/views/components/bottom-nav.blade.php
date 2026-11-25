{{-- Komponen Navigasi Bawah Khusus Tampilan Mobile — Soft Editorial (Floating Dark Pill) sesuai baselineui.md --}}
<nav
    id="navigasi-bawah-mobile"
    aria-label="Navigasi Bawah Mobile"
    class="fixed inset-x-0 bottom-4 z-50 flex justify-center px-4 md:hidden pointer-events-none"
>
    <div class="pointer-events-auto flex items-center justify-between gap-1 rounded-full bg-dark px-2.5 py-1.5 text-white/70 shadow-2xl border border-white/10 max-w-sm w-full">
        {{-- 1. Beranda --}}
        <a
            href="{{ route('home') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('home') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Beranda</span>
        </a>

        @auth
            @if(auth()->user()->role === 'admin')
                {{-- Admin: Verifikasi --}}
                <a
                    href="{{ route('admin.profil.edit', ['tab' => 'verifikasi-advokat']) }}"
                    class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->is('admin/profil*') && request('tab') === 'verifikasi-advokat' ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
                >
                    <div class="flex h-6 w-6 items-center justify-center">
                        <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <span class="text-[10px] leading-tight mt-0.5">Verifikasi</span>
                </a>
            @elseif(auth()->user()->role === 'advokat')
                {{-- Advokat: Dasbor --}}
                <a
                    href="{{ route('advokat.dashboard') }}"
                    class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('advokat.dashboard') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
                >
                    <div class="flex h-6 w-6 items-center justify-center">
                        <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.143 2.143L18.75 4.5" />
                        </svg>
                    </div>
                    <span class="text-[10px] leading-tight mt-0.5">Dasbor</span>
                </a>
            @else
                {{-- Masyarakat: Cari Advokat --}}
                <a
                    href="{{ route('advokat.index') }}"
                    class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('advokat.*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
                >
                    <div class="flex h-6 w-6 items-center justify-center">
                        <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>
                    <span class="text-[10px] leading-tight mt-0.5">Cari</span>
                </a>
                {{-- Masyarakat: Konsultasi Saya (B4) --}}
                <a
                    href="{{ route('masyarakat.profil.edit', ['tab' => 'konsultasi-saya']) }}"
                    class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->is('masyarakat/profil*') && request('tab') === 'konsultasi-saya' ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
                >
                    <div class="flex h-6 w-6 items-center justify-center">
                        <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 5.942 5.942 0 0 0 .97-2.73A8.77 8.77 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </div>
                    <span class="text-[10px] leading-tight mt-0.5">Konsultasi</span>
                </a>
            @endif
        @else
            {{-- Guest: Cari & Pro Bono --}}
            <a
                href="{{ route('advokat.index') }}"
                class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('advokat.*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
            >
                <div class="flex h-6 w-6 items-center justify-center">
                    <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <span class="text-[10px] leading-tight mt-0.5">Cari</span>
            </a>
            <a
                href="{{ url('/pro-bono') }}"
                class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->is('pro-bono*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
            >
                <div class="flex h-6 w-6 items-center justify-center">
                    <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.51 48.51 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.583V21" />
                    </svg>
                </div>
                <span class="text-[10px] leading-tight mt-0.5">Pro Bono</span>
            </a>
        @endauth

        {{-- Kamus Hukum --}}
        <a
            href="{{ route('kamus.index') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('kamus.*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Kamus</span>
        </a>

        {{-- Profil / Akun --}}
        <a
            href="{{ auth()->check() ? route('profil.index') : route('login') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->is('profil*') || request()->is('*/profil*') || request()->is('login*') || request()->is('register*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">{{ auth()->check() ? (auth()->user()->role === 'admin' ? 'Admin' : 'Profil') : 'Masuk' }}</span>
        </a>
    </div>
</nav>