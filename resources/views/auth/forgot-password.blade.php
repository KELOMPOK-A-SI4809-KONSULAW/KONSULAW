@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        {{-- Ikon Kunci Pemulihan Sandi --}}
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent text-white shadow-btn">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>

        <h1 class="mt-4 font-display text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Pemulihan Kata Sandi
        </h1>
        <p class="mt-2 text-xs sm:text-sm text-ink-soft max-w-sm mx-auto">
            Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-10 shadow-card">
            {{-- Alert Status Pengiriman --}}
            @if (session('status'))
                <div class="mb-5 rounded-[14px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-success flex items-start gap-3">
                    <svg class="h-5 w-5 text-success shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mb-5 rounded-[14px] border border-danger bg-paper p-4 text-xs sm:text-sm text-danger">
                    <p class="font-bold">Perhatian:</p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4.5">
                @csrf

                <div>
                    <label for="email" class="block text-xs sm:text-sm font-semibold text-ink">
                        Alamat Email Terdaftar
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-full bg-accent px-5 py-3.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark hover:-translate-y-0.5 active:scale-95 cursor-pointer focus:outline-none focus:ring-3 focus:ring-accent-soft"
                    >
                        <span>Kirim Tautan Pemulihan</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-line text-center">
                <a
                    href="{{ route('login') }}"
                    class="text-xs sm:text-sm font-semibold text-accent hover:text-accent-dark transition flex items-center justify-center gap-1.5"
                >
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>Kembali ke Halaman Masuk</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
