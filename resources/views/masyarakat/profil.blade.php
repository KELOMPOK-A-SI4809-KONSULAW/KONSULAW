@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] py-10 px-4 sm:px-6 lg:px-8 bg-neutral-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl text-center mb-8">
        {{-- Ikon Lambang Profil Masyarakat --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-800 text-white shadow-md">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-950">
            Pengelolaan Profil Masyarakat
        </h1>
        <p class="mt-2 text-sm text-neutral-600 max-w-md mx-auto">
            Perbarui data diri, nomor identitas kependudukan (NIK), serta alamat domisili untuk kelengkapan konsultasi hukum.
        </p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-8 px-6 shadow-md rounded-3xl border border-neutral-200 sm:px-10">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-bold">Berhasil!</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <p class="font-bold">Mohon periksa kembali isian formulir Anda:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Kartu Ringkasan Akun & Status Ekonomi (Pro Bono / Reguler) --}}
            <div class="mb-8 p-4 rounded-2xl bg-neutral-50 border border-neutral-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    @php
                        $namaUser = $user->nama ?? $user->name ?? 'Pengguna';
                        $bagianNama = preg_split('/\s+/', trim($namaUser)) ?: ['A', 'N'];
                        $inisialUser = mb_strtoupper(mb_substr($bagianNama[0] ?? 'A', 0, 1) . mb_substr($bagianNama[1] ?? 'N', 0, 1));
                    @endphp
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-red-800 text-white font-extrabold text-base shadow-sm">
                        {{ $inisialUser }}
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-neutral-950">{{ $user->nama ?? $user->name }}</h2>
                        <p class="text-xs text-neutral-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span>
                        Akun: {{ ucfirst($user->status_akun ?? 'Aktif') }}
                    </span>

                    @if (($user->status_ekonomi ?? 'reguler') === 'pro_bono')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                            ⭐ Pemohon Pro Bono (Gratis)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-neutral-200 text-neutral-800">
                            Jalur: Reguler
                        </span>
                    @endif
                </div>
            </div>

            {{-- Banner Bantuan Hukum Pro Bono jika statusnya masih Reguler --}}
            @if (($user->status_ekonomi ?? 'reguler') !== 'pro_bono')
                <div class="mb-8 p-4 rounded-2xl bg-red-50/70 border border-red-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-red-900">Perlu Bantuan Hukum Cuma-Cuma (Pro Bono)?</p>
                        <p class="text-xs text-red-800/80 mt-0.5">Warga tidak mampu dapat mengajukan verifikasi status ekonomi dengan mengunggah SKTM.</p>
                    </div>
                    <a href="{{ route('pro-bono.create') }}" class="inline-flex items-center justify-center rounded-xl bg-red-800 px-3.5 py-2 text-xs font-bold text-white transition hover:bg-neutral-950 shrink-0">
                        Ajukan Pro Bono
                        <svg class="ml-1.5 h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            @endif

            {{-- Formulir Pembaruan Data Diri --}}
            <form method="POST" action="{{ route('masyarakat.profil.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- 1. NAMA LENGKAP --}}
                <div>
                    <label for="nama" class="block text-sm font-bold text-neutral-900">
                        Nama Lengkap <span class="text-red-700">*</span>
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="nama"
                            name="nama"
                            type="text"
                            value="{{ old('nama', $user->nama ?? $user->name) }}"
                            required
                            placeholder="Contoh: Budi Santoso"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Gunakan nama lengkap resmi sesuai Kartu Tanda Penduduk (KTP).
                    </p>
                </div>

                {{-- 2. NIK (16 DIGIT) --}}
                <div>
                    <label for="nik" class="block text-sm font-bold text-neutral-900">
                        Nomor Induk Kependudukan (NIK)
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="nik"
                            name="nik"
                            type="text"
                            maxlength="16"
                            value="{{ old('nik', $user->nik) }}"
                            placeholder="Contoh: 3204123456780001 (16 digit angka)"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        NIK digunakan untuk validasi permohonan konsultasi serta verifikasi bantuan hukum gratis.
                    </p>
                </div>

                {{-- 3. NOMOR HANDPHONE / WHATSAPP --}}
                <div>
                    <label for="no_hp" class="block text-sm font-bold text-neutral-900">
                        Nomor Handphone / WhatsApp <span class="text-red-700">*</span>
                    </label>
                    <div class="mt-1.5">
                        <input
                            id="no_hp"
                            name="no_hp"
                            type="tel"
                            value="{{ old('no_hp', $user->no_hp) }}"
                            required
                            placeholder="081234567890"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Nomor aktif untuk menerima pemberitahuan tanggapan advokat dan ruang konsultasi.
                    </p>
                </div>

                {{-- 4. WILAYAH TEMPAT TINGGAL --}}
                <div>
                    <label for="wilayah_id" class="block text-sm font-bold text-neutral-900">
                        Wilayah Domisili (Kabupaten / Kota / Provinsi)
                    </label>
                    <div class="mt-1.5">
                        <select
                            id="wilayah_id"
                            name="wilayah_id"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 text-sm bg-white focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                            <option value="">-- Pilih Wilayah Domisili --</option>
                            @foreach ($wilayahList as $wilayah)
                                <option value="{{ $wilayah->id }}" {{ old('wilayah_id', $user->wilayah_id) == $wilayah->id ? 'selected' : '' }}>
                                    {{ $wilayah->nama }} ({{ ucwords(str_replace('_', ' ', $wilayah->tingkat)) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Membantu mencocokkan Anda dengan advokat yang memiliki wilayah praktik di daerah Anda.
                    </p>
                </div>

                {{-- 5. ALAMAT LENGKAP --}}
                <div>
                    <label for="alamat" class="block text-sm font-bold text-neutral-900">
                        Alamat Lengkap Tempat Tinggal
                    </label>
                    <div class="mt-1.5">
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Tuliskan nama jalan, RT/RW, Dusun, dan Desa/Kelurahan..."
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >{{ old('alamat', $user->alamat) }}</textarea>
                    </div>
                </div>

                {{-- 6. ALAMAT EMAIL (READONLY) --}}
                <div>
                    <label class="block text-sm font-bold text-neutral-900">
                        Alamat Email Akun
                    </label>
                    <div class="mt-1.5">
                        <input
                            type="email"
                            value="{{ $user->email }}"
                            disabled
                            class="block w-full rounded-xl border border-neutral-200 bg-neutral-100 px-4 py-3 text-neutral-500 text-sm cursor-not-allowed"
                        >
                    </div>
                    <p class="mt-1 text-xs text-neutral-400">
                        Alamat email terdaftar digunakan sebagai identitas akun utama.
                    </p>
                </div>

                {{-- Tombol Simpan --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-xl bg-red-800 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                    >
                        <span>Simpan Pembaruan Data Profil</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
