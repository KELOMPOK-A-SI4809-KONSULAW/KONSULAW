@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] py-10 px-4 sm:px-6 lg:px-8 bg-neutral-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-4xl text-center mb-8">
        {{-- Ikon Lambang Profil Advokat --}}
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-red-800 text-white shadow-md">
            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
            </svg>
        </div>

        <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-950">
            Profil Profesional &amp; Spesialisasi Advokat
        </h1>
        <p class="mt-2 text-sm text-neutral-600 max-w-lg mx-auto">
            Kelola nomor lisensi, riwayat praktik, bidang keahlian hukum, dan tarif konsultasi Anda untuk ditampilkan di direktori Konsulaw.
        </p>
    </div>

    {{-- Navigasi Tab Advokat --}}
    <div class="sm:mx-auto sm:w-full sm:max-w-4xl mb-6">
        <div class="flex items-center gap-2 border-b border-neutral-200 pb-2">
            <a
                href="{{ route('advokat.dashboard') }}"
                class="rounded-xl px-4 py-2 text-xs sm:text-sm font-bold text-neutral-600 hover:text-red-800 hover:bg-neutral-100 transition"
            >
                Dasbor &amp; Pengaturan Layanan
            </a>
            <a
                href="{{ route('advokat.profil.edit') }}"
                class="rounded-xl bg-red-800 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-2xs"
            >
                Profil Profesional &amp; Spesialisasi
            </a>
        </div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-4xl">
        <div class="bg-white py-8 px-6 shadow-md rounded-3xl border border-neutral-200 sm:px-10">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="font-bold">Berhasil Diperbarui!</p>
                        <p class="text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <p class="font-bold">Mohon perbaiki data profil Anda:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Status Verifikasi Kredensial --}}
            <div class="mb-8 p-4 rounded-2xl border {{ $profil->status_verifikasi === 'disetujui' ? 'bg-emerald-50/70 border-emerald-200 text-emerald-900' : 'bg-amber-50/70 border-amber-200 text-amber-900' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    @if ($profil->status_verifikasi === 'disetujui')
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-800">Status: Terverifikasi Resmi</p>
                            <p class="text-xs text-emerald-700">Profil Anda aktif dan tampil di halaman Direktori Advokat Konsulaw.</p>
                        </div>
                    @else
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-wider text-amber-800">Status: Menunggu Peninjauan Admin</p>
                            <p class="text-xs text-amber-700">Lengkapi data lisensi di bawah ini agar admin dapat memverifikasi akun Anda.</p>
                        </div>
                    @endif
                </div>

                <div class="text-right shrink-0">
                    <span class="text-xs font-bold text-neutral-500 block">Akun Terdaftar:</span>
                    <span class="text-xs font-extrabold text-neutral-900">{{ $user->nama ?? $user->name }}</span>
                </div>
            </div>

            <form method="POST" action="{{ route('advokat.profil.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- BARIS 1: NO LISENSI & NO BAS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="no_kta" class="block text-sm font-bold text-neutral-900">
                            Nomor Lisensi Advokat / KTA <span class="text-red-700">*</span>
                        </label>
                        <div class="mt-1.5">
                            <input
                                id="no_kta"
                                name="no_kta"
                                type="text"
                                required
                                value="{{ old('no_kta', $profil->no_kta ?? $profil->no_lisensi) }}"
                                placeholder="Contoh: KTA-PERADI-2024-0891"
                                class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="no_bas" class="block text-sm font-bold text-neutral-900">
                            Nomor Berita Acara Sumpah (BAS)
                        </label>
                        <div class="mt-1.5">
                            <input
                                id="no_bas"
                                name="no_bas"
                                type="text"
                                value="{{ old('no_bas', $profil->no_bas) }}"
                                placeholder="Contoh: BAS-PT-BDG-2019-0412"
                                class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                            >
                        </div>
                    </div>
                </div>

                {{-- BARIS 2: TAHUN MULAI PRAKTIK & TARIF KONSULTASI REGULER --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tahun_mulai_praktik" class="block text-sm font-bold text-neutral-900">
                            Tahun Mulai Praktik <span class="text-red-700">*</span>
                        </label>
                        <div class="mt-1.5">
                            <input
                                id="tahun_mulai_praktik"
                                name="tahun_mulai_praktik"
                                type="number"
                                min="1970"
                                max="{{ date('Y') }}"
                                required
                                value="{{ old('tahun_mulai_praktik', $profil->tahun_mulai_praktik ?? date('Y')) }}"
                                placeholder="Contoh: 2019"
                                class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 font-bold text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                            >
                        </div>
                        <p class="mt-1 text-xs text-neutral-500">
                            Digunakan untuk menghitung akumulasi masa pengalaman terbangun.
                        </p>
                    </div>

                    <div>
                        <label for="tarif_konsultasi" class="block text-sm font-bold text-neutral-900">
                            Tarif Konsultasi Awal Reguler (Rp) <span class="text-red-700">*</span>
                        </label>
                        <div class="mt-1.5 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-xs font-bold text-neutral-500">
                                Rp
                            </div>
                            <input
                                id="tarif_konsultasi"
                                name="tarif_konsultasi"
                                type="number"
                                min="0"
                                step="1000"
                                required
                                value="{{ old('tarif_konsultasi', (int) ($profil->tarif_konsultasi ?? 0)) }}"
                                placeholder="Contoh: 150000"
                                class="block w-full rounded-xl border border-neutral-300 pl-10 pr-4 py-3 text-neutral-950 font-bold text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                            >
                        </div>
                        <p class="mt-1 text-xs text-neutral-500">
                            Tarif untuk masyarakat jalur reguler (bukan pemohon pro bono).
                        </p>
                    </div>
                </div>

                {{-- BARIS 3: SPESIALISASI BIDANG HUKUM (CHECKBOXES) --}}
                <div>
                    <label class="block text-sm font-bold text-neutral-900 mb-2">
                        Spesialisasi Bidang Hukum <span class="text-red-700">*</span>
                    </label>
                    <p class="text-xs text-neutral-600 mb-3">
                        Pilih satu atau lebih bidang hukum yang menjadi keahlian dan fokus pendampingan Anda:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach ($daftarBidang as $bidang)
                            <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition hover:border-red-800 has-checked:border-red-800 has-checked:bg-red-50/50">
                                <input
                                    type="checkbox"
                                    name="bidang_hukum_ids[]"
                                    value="{{ $bidang->id }}"
                                    class="h-4 w-4 rounded-sm border-neutral-300 text-red-800 focus:ring-red-800"
                                    {{ in_array($bidang->id, old('bidang_hukum_ids', $selectedBidangIds)) ? 'checked' : '' }}
                                >
                                <span class="ml-2.5 text-xs font-bold text-neutral-900">{{ $bidang->nama }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- BARIS 4: WILAYAH PRAKTIK / JANGKAUAN LAYANAN --}}
                <div>
                    <label class="block text-sm font-bold text-neutral-900 mb-2">
                        Wilayah Jangkauan Praktik (Kabupaten / Kota)
                    </label>
                    <p class="text-xs text-neutral-600 mb-3">
                        Pilih wilayah tempat Anda berpraktik atau bersedia mendampingi klien:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2.5 max-h-56 overflow-y-auto p-3 rounded-2xl border border-neutral-300 bg-neutral-50/50">
                        @foreach ($daftarWilayah as $w)
                            <label class="flex items-center gap-2 p-2 rounded-xl bg-white border border-neutral-200 cursor-pointer hover:border-red-700 transition">
                                <input
                                    type="checkbox"
                                    name="wilayah_ids[]"
                                    value="{{ $w->id }}"
                                    class="h-4 w-4 rounded-sm border-neutral-300 text-red-800 focus:ring-red-800"
                                    {{ in_array($w->id, old('wilayah_ids', $selectedWilayahIds)) ? 'checked' : '' }}
                                >
                                <span class="text-xs text-neutral-900 font-medium">
                                    {{ $w->nama }} <span class="text-[10px] text-neutral-500">({{ $w->tingkat }})</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- BARIS 5: BIOGRAFI & REKAM JEJAK ADVOKAT --}}
                <div>
                    <label for="biografi" class="block text-sm font-bold text-neutral-900">
                        Deskripsi Diri &amp; Rekam Jejak Advokat <span class="text-red-700">*</span>
                    </label>
                    <div class="mt-1.5">
                        <textarea
                            id="biografi"
                            name="biografi"
                            rows="4"
                            required
                            placeholder="Jelaskan latar belakang pendidikan, asosiasi advokat, dan pengalaman menangani kasus tanah desa, perdata, ketenagakerjaan, dll..."
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 text-neutral-950 placeholder-neutral-400 text-sm focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >{{ old('biografi', $profil->biografi ?? $profil->pengalaman) }}</textarea>
                    </div>
                    <p class="mt-1 text-xs text-neutral-500">
                        Teks ini ditampilkan pada kartu profil Anda di halaman Beranda Konsulaw.
                    </p>
                </div>

                {{-- Tombol Simpan --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 rounded-xl bg-red-800 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                    >
                        <span>Simpan Profil Profesional Advokat</span>
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
