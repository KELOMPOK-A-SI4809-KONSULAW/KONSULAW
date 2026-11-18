@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        {{-- Ikon Lambang Hukum & Keadilan --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-800 text-white shadow-md">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-950">
            Masuk ke Akun Konsu<span class="text-red-700">law</span>
        </h1>
        <p class="mt-2 text-sm text-neutral-600">
            Akses layanan konsultasi hukum pra-litigasi dan bantuan pro bono untuk masyarakat &amp; advokat.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow-md rounded-3xl border border-neutral-200 sm:px-10">
            {{-- Alert Notifikasi Sukses / Pesan Status --}}
            @if (session('success'))
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <p class="font-bold">Mohon periksa kembali data isian Anda:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Input Email --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-neutral-900">
                        Alamat Email
                    </label>
                    <div class="mt-1.5 relative">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Email yang Anda daftarkan sebagai Masyarakat atau Advokat.
                    </p>
                </div>

                {{-- Input Kata Sandi --}}
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-bold text-neutral-900">
                            Kata Sandi
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-red-700 hover:text-red-900 hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <div class="mt-1.5 relative">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="Masukkan kata sandi Anda"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                </div>

                {{-- Ingat Saya --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-neutral-300 text-red-800 focus:ring-red-700"
                        >
                        <span class="text-xs font-medium text-neutral-700">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                {{-- Tombol Masuk Utama (Merah Maron Dominan) --}}
                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-xl bg-red-800 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                    >
                        <span>Masuk Sekarang</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Pembatas Pendaftaran --}}
            <div class="mt-6 pt-6 border-t border-neutral-200 text-center">
                <p class="text-sm text-neutral-600">
                    Belum memiliki akun Konsulaw?
                </p>
                <a
                    href="{{ route('register') }}"
                    class="mt-2 inline-flex items-center justify-center w-full rounded-xl border-2 border-red-800 bg-white px-5 py-3 text-sm font-bold text-red-800 transition hover:bg-red-50 hover:text-red-900"
                >
                    Daftar Akun Baru (Masyarakat / Advokat)
                </a>
            </div>
        </div>

        {{-- Jaminan Privasi Sesuai NFR-05 Proposal --}}
        <p class="mt-6 text-center text-xs text-neutral-500">
            Dilindungi sistem keamanan sandi terenkripsi (NFR-01) &amp; privasi data (NFR-05).
        </p>
    </div>
</div>
@endsection
