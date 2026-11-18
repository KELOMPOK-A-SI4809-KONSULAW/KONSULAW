@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-neutral-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-xl text-center">
        {{-- Ikon Lambang Pendaftaran --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-800 text-white shadow-md">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
        </div>

        <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-950">
            Daftar Akun Baru Konsu<span class="text-red-700">law</span>
        </h1>
        <p class="mt-2 text-sm text-neutral-600 max-w-md mx-auto">
            Satu pintu akses bantuan hukum untuk warga desa, masyarakat pencari keadilan, dan praktisi advokat terverifikasi.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-6 shadow-md rounded-3xl border border-neutral-200 sm:px-10">
            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <p class="font-bold">Mohon perbaiki data pendaftaran Anda:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                {{-- 1. PILIHAN PERAN PENGGUNA (Sederhana & Mudah Dipahami Warga Awam) --}}
                <div>
                    <label class="block text-sm font-extrabold text-neutral-900 mb-2">
                        Pilih Jenis Akun Anda:
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Kartu Pilihan: Masyarakat --}}
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition hover:border-red-800 has-checked:border-red-800 has-checked:bg-red-50/50">
                            <input
                                type="radio"
                                name="role"
                                value="masyarakat"
                                class="sr-only"
                                {{ old('role', 'masyarakat') === 'masyarakat' ? 'checked' : '' }}
                            >
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-neutral-950 text-sm">Masyarakat</span>
                                <span class="h-3 w-3 rounded-full border-2 border-red-800 bg-white inline-block"></span>
                            </div>
                            <p class="mt-1 text-xs text-neutral-600 leading-relaxed">
                                Warga pencari keadilan yang membutuhkan konsultasi hukum atau bantuan cuma-cuma (pro bono).
                            </p>
                        </label>

                        {{-- Kartu Pilihan: Advokat --}}
                        <label class="relative flex flex-col p-4 rounded-2xl border-2 cursor-pointer transition hover:border-red-800 has-checked:border-red-800 has-checked:bg-red-50/50">
                            <input
                                type="radio"
                                name="role"
                                value="advokat"
                                class="sr-only"
                                {{ old('role') === 'advokat' ? 'checked' : '' }}
                            >
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-neutral-950 text-sm">Advokat</span>
                                <span class="h-3 w-3 rounded-full border-2 border-red-800 bg-white inline-block"></span>
                            </div>
                            <p class="mt-1 text-xs text-neutral-600 leading-relaxed">
                                Praktisi hukum resmi pemilik Ijazah SH, BAS, &amp; KTA untuk melayani konsultasi.
                            </p>
                        </label>
                    </div>
                </div>

                {{-- 2. NAMA LENGKAP --}}
                <div>
                    <label for="nama" class="block text-sm font-bold text-neutral-900">
                        Nama Lengkap
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            value="{{ old('nama') }}"
                            required
                            placeholder="Contoh: Budi Santoso atau Siti Aminah, S.H."
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Gunakan nama sesuai KTP (masyarakat) atau nama lengkap beserta gelar (advokat).
                    </p>
                </div>

                {{-- 3. ALAMAT EMAIL --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-neutral-900">
                        Alamat Email
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="nama@email.com"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                </div>

                {{-- 4. NOMOR HP / WHATSAPP --}}
                <div>
                    <label for="no_hp" class="block text-sm font-bold text-neutral-900">
                        Nomor Handphone / WhatsApp
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="no_hp"
                            name="no_hp"
                            type="tel"
                            value="{{ old('no_hp') }}"
                            required
                            placeholder="081234567890"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Nomor aktif untuk menerima pemberitahuan tanggapan atau panggilan konsultasi.
                    </p>
                </div>

                {{-- 5. PILIHAN WILAYAH DOMISILI / PRAKTIK --}}
                <div>
                    <label for="wilayah_id" class="block text-sm font-bold text-neutral-900">
                        Wilayah Tempat Tinggal / Praktik (Opsional)
                    </label>
                    <div class="mt-1.5">
                        <select
                            id="wilayah_id"
                            name="wilayah_id"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 text-sm bg-white focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                            <option value="">-- Pilih Kabupaten / Kota / Provinsi --</option>
                            @foreach ($wilayahList as $wilayah)
                                <option value="{{ $wilayah->id }}" {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>
                                    {{ $wilayah->nama }} ({{ ucwords(str_replace('_', ' ', $wilayah->tingkat)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Membantu mencocokkan Anda dengan advokat di wilayah terdekat.
                    </p>
                </div>

                {{-- 6. KATA SANDI & KONFIRMASI --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-bold text-neutral-900">
                            Kata Sandi
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

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-neutral-900">
                            Ulangi Kata Sandi
                        </label>
                        <div class="mt-1.5">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                placeholder="Ketik ulang kata sandi"
                                class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                            >
                        </div>
                    </div>
                </div>

                {{-- Tombol Daftar Utama (Merah Maron Dominan) --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-xl bg-red-800 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                    >
                        <span>Daftar Akun Konsulaw</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Tautan ke Halaman Masuk --}}
            <div class="mt-6 pt-6 border-t border-neutral-200 text-center">
                <p class="text-sm text-neutral-600">
                    Sudah pernah mendaftar akun Konsulaw?
                </p>
                <a
                    href="{{ route('login') }}"
                    class="mt-2 inline-flex items-center justify-center w-full rounded-xl border-2 border-red-800 bg-white px-5 py-3 text-sm font-bold text-red-800 transition hover:bg-red-50 hover:text-red-900"
                >
                    Masuk ke Akun Anda
                </a>
            </div>
        </div>

        {{-- Jaminan Privasi & Perlindungan Data Pribadi (NFR-05) --}}
        <p class="mt-6 text-center text-xs text-neutral-500">
            Dengan mendaftar, data Anda terlindungi sesuai ketentuan UU Perlindungan Data Pribadi (NFR-05).
        </p>
    </div>
</div>
@endsection
