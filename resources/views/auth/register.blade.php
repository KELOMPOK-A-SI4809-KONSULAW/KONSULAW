@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-xl text-center">
        {{-- Ikon Lambang Pendaftaran --}}
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent text-white shadow-btn">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
        </div>

        <h1 class="mt-4 font-display text-2xl sm:text-3xl font-bold tracking-tight text-ink">
            Daftar Akun Baru Konsu<span class="text-accent">law</span>
        </h1>
        <p class="mt-2 text-xs sm:text-sm text-ink-soft max-w-md mx-auto">
            Satu pintu akses bantuan hukum untuk warga desa, masyarakat pencari keadilan, dan praktisi advokat terverifikasi.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-10 shadow-card">
            {{-- Alert Kesalahan Validasi (Outline Danger) --}}
            @if ($errors->any())
                <div class="mb-6 rounded-[14px] border border-danger bg-paper p-4 text-xs sm:text-sm text-danger">
                    <p class="font-bold flex items-center gap-1.5">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span>Mohon perbaiki data pendaftaran Anda:</span>
                    </p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- 1. PILIHAN PERAN PENGGUNA --}}
                <div>
                    <label class="block text-xs sm:text-sm font-semibold text-ink mb-2">
                        Pilih Jenis Akun Anda:
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        {{-- Kartu Pilihan: Masyarakat --}}
                        <label class="relative flex flex-col p-4 rounded-[14px] border cursor-pointer transition hover:border-accent has-checked:border-accent has-checked:bg-accent-soft/50 border-line bg-ivory">
                            <input
                                type="radio"
                                name="role"
                                value="masyarakat"
                                class="sr-only"
                                {{ old('role', 'masyarakat') === 'masyarakat' ? 'checked' : '' }}
                            >
                            <div class="flex items-center justify-between">
                                <span class="font-display font-semibold text-ink text-sm">Masyarakat</span>
                                <span class="h-3.5 w-3.5 rounded-full border-2 border-accent bg-paper inline-block"></span>
                            </div>
                            <p class="mt-1 text-xs text-ink-soft leading-relaxed">
                                Warga yang membutuhkan konsultasi hukum atau bantuan cuma-cuma (pro bono).
                            </p>
                        </label>

                        {{-- Kartu Pilihan: Advokat --}}
                        <label class="relative flex flex-col p-4 rounded-[14px] border cursor-pointer transition hover:border-accent has-checked:border-accent has-checked:bg-accent-soft/50 border-line bg-ivory">
                            <input
                                type="radio"
                                name="role"
                                value="advokat"
                                class="sr-only"
                                {{ old('role') === 'advokat' ? 'checked' : '' }}
                            >
                            <div class="flex items-center justify-between">
                                <span class="font-display font-semibold text-ink text-sm">Advokat</span>
                                <span class="h-3.5 w-3.5 rounded-full border-2 border-accent bg-paper inline-block"></span>
                            </div>
                            <p class="mt-1 text-xs text-ink-soft leading-relaxed">
                                Praktisi hukum resmi pemilik Ijazah SH, BAS, &amp; KTA untuk melayani konsultasi.
                            </p>
                        </label>
                    </div>
                </div>

                {{-- 2. NAMA LENGKAP --}}
                <div>
                    <label for="nama" class="block text-xs sm:text-sm font-semibold text-ink">
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
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                    </div>
                </div>

                {{-- 3. ALAMAT EMAIL --}}
                <div>
                    <label for="email" class="block text-xs sm:text-sm font-semibold text-ink">
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
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                    </div>
                </div>

                {{-- 4. NOMOR HP / WHATSAPP --}}
                <div>
                    <label for="no_hp" class="block text-xs sm:text-sm font-semibold text-ink">
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
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                    </div>
                </div>

                {{-- 5. PILIHAN WILAYAH DOMISILI / PRAKTIK --}}
                <div>
                    <label for="wilayah_id" class="block text-xs sm:text-sm font-semibold text-ink">
                        Wilayah Tempat Tinggal / Praktik (Opsional)
                    </label>
                    <div class="mt-1.5">
                        <select
                            id="wilayah_id"
                            name="wilayah_id"
                            class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                        >
                            <option value="">-- Pilih Kabupaten / Kota / Provinsi --</option>
                            @foreach ($wilayahList as $wilayah)
                                <option value="{{ $wilayah->id }}" {{ old('wilayah_id') == $wilayah->id ? 'selected' : '' }}>
                                    {{ $wilayah->nama }} ({{ ucwords(str_replace('_', ' ', $wilayah->tingkat)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 6. KATA SANDI & KONFIRMASI --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs sm:text-sm font-semibold text-ink">
                            Kata Sandi
                        </label>
                        <div class="mt-1.5">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                placeholder="Minimal 8 karakter"
                                class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-sm font-semibold text-ink">
                            Ulangi Kata Sandi
                        </label>
                        <div class="mt-1.5">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                placeholder="Ketik ulang kata sandi"
                                class="block w-full rounded-[14px] border border-line bg-paper px-4 py-3 min-h-[48px] text-ink placeholder-ink-soft/60 text-xs sm:text-sm focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                            >
                        </div>
                    </div>
                </div>

                {{-- Tombol Daftar Utama --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-full bg-accent px-5 py-3.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark hover:-translate-y-0.5 active:scale-95 cursor-pointer focus:outline-none focus:ring-3 focus:ring-accent-soft"
                    >
                        <span>Daftar Akun Konsulaw</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>

            {{-- Tautan ke Halaman Masuk --}}
            <div class="mt-6 pt-6 border-t border-line text-center">
                <p class="text-xs sm:text-sm text-ink-soft">
                    Sudah pernah mendaftar akun Konsulaw?
                </p>
                <a
                    href="{{ route('login') }}"
                    class="mt-2.5 inline-flex items-center justify-center w-full rounded-full border border-line bg-paper px-5 py-2.5 text-xs sm:text-sm font-semibold text-ink transition hover:border-accent hover:text-accent"
                >
                    Masuk ke Akun Anda
                </a>
            </div>
        </div>

        {{-- Jaminan Privasi & Perlindungan Data Pribadi (NFR-05) --}}
        <p class="mt-6 text-center text-xs text-ink-soft">
            Dengan mendaftar, data Anda terlindungi sesuai ketentuan UU Perlindungan Data Pribadi (NFR-05).
        </p>
    </div>
</div>
@endsection
