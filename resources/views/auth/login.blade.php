@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        {{-- Ikon Lambang Hukum --}}
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent text-white shadow-btn">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <h1 class="mt-4 font-display text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Masuk ke Akun Konsu<span class="text-accent">law</span>
        </h1>
        <p class="mt-2 text-xs sm:text-sm text-ink-soft">
            Akses layanan konsultasi hukum pra-litigasi dan bantuan pro bono untuk masyarakat &amp; advokat.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-10 shadow-card">
            {{-- Alert Notifikasi Sukses / Status --}}
            @if (session('success'))
                <div class="mb-5 rounded-[14px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-success flex items-start gap-3">
                    <svg class="h-5 w-5 text-success shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-5 rounded-[14px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-success flex items-start gap-3">
                    <svg class="h-5 w-5 text-success shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-[14px] bg-paper border border-danger p-4 text-xs sm:text-sm text-danger flex items-start gap-3">
                    <svg class="h-5 w-5 text-danger shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi (Outline Danger) --}}
            @if ($errors->any())
                <div class="mb-5 rounded-[14px] border border-danger bg-paper p-4 text-xs sm:text-sm text-danger">
                    <p class="font-bold flex items-center gap-1.5">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span>Mohon periksa kembali data isian Anda:</span>
                    </p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4.5">
                @csrf

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-semibold text-ink">
                        Alamat Email
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                    </div>
                </div>

                {{-- Input Kata Sandi --}}
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-ink">
                            Kata Sandi
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-accent hover:text-accent-dark">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <div class="mt-1.5">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="Masukkan kata sandi Anda"
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                    </div>
                </div>

                {{-- Ingat Saya --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-line text-accent focus:ring-accent-soft"
                        >
                        <span class="text-xs text-ink-soft font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                {{-- Tombol Masuk Utama (Pill Vermilion) --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-full bg-accent px-5 py-3.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark hover:-translate-y-0.5 active:scale-95 cursor-pointer focus:outline-none focus:ring-3 focus:ring-accent-soft"
                    >
                        <span>Masuk Sekarang</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Pembatas Pendaftaran --}}
            <div class="mt-6 pt-6 border-t border-line text-center">
                <p class="text-xs sm:text-sm text-ink-soft">
                    Belum memiliki akun Konsulaw?
                </p>
                <a
                    href="{{ route('register') }}"
                    class="mt-2.5 inline-flex items-center justify-center w-full rounded-full border border-line bg-paper px-5 py-2.5 text-xs sm:text-sm font-semibold text-ink transition hover:border-accent hover:text-accent"
                >
                    Daftar Akun Baru (Masyarakat / Advokat)
                </a>
            </div>
        </div>

        {{-- Jaminan Privasi --}}
        <p class="mt-6 text-center text-xs text-ink-soft">
            Dilindungi sistem keamanan sandi terenkripsi &amp; privasi data terjamin.
        </p>
    </div>
</div>
@endsection
