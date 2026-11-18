@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        {{-- Ikon Atur Ulang Sandi --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-800 text-white shadow-md">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
        </div>

        <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-950">
            Atur Ulang Kata Sandi Baru
        </h1>
        <p class="mt-2 text-sm text-neutral-600 max-w-sm mx-auto">
            Silakan masukkan kata sandi baru untuk akun Konsulaw Anda.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow-md rounded-3xl border border-neutral-200 sm:px-10">
            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mb-5 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <p class="font-bold">Mohon periksa data yang dimasukkan:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                @csrf

                {{-- Token Reset Password --}}
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Alamat Email --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-neutral-900">
                        Alamat Email
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $email) }}"
                            required
                            readonly
                            class="block w-full rounded-xl border border-neutral-200 bg-neutral-100 px-4 py-3 text-neutral-600 text-sm focus:outline-none"
                        >
                    </div>
                </div>

                {{-- Kata Sandi Baru --}}
                <div>
                    <label for="password" class="block text-sm font-bold text-neutral-900">
                        Kata Sandi Baru
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            placeholder="Minimal 8 karakter"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                </div>

                {{-- Konfirmasi Kata Sandi Baru --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-neutral-900">
                        Ulangi Kata Sandi Baru
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            placeholder="Ketik ulang kata sandi baru"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-xl bg-red-800 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                    >
                        <span>Simpan Kata Sandi Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
