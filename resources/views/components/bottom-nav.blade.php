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
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Beranda</span>
        </a>

        {{-- 2. Cari (Cari Advokat) --}}
        <a
            href="{{ route('advokat.index') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('advokat.*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Cari</span>
        </a>

        {{-- 3. Konsultasi (Layanan Bantuan / Pro Bono) --}}
        <a
            href="{{ url('/pro-bono') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->is('pro-bono*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Konsultasi</span>
        </a>

        {{-- 4. Kamus (Kamus Hukum Populer) --}}
        <a
            href="{{ route('kamus.index') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->routeIs('kamus.*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Kamus</span>
        </a>

        {{-- 5. Akun (Masuk / Profil Pengguna) --}}
        <a
            href="{{ auth()->check() ? url('/pro-bono') : route('login') }}"
            class="group flex flex-1 flex-col items-center justify-center rounded-full py-1 text-center transition {{ request()->is('login*') || request()->is('register*') ? 'text-white font-bold bg-accent shadow-btn' : 'hover:text-white hover:bg-white/10' }}"
        >
            <div class="flex h-6 w-6 items-center justify-center">
                <svg class="h-4.5 w-4.5 transition group-hover:scale-105" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <span class="text-[10px] leading-tight mt-0.5">Akun</span>
        </a>
    </div>
</nav>
