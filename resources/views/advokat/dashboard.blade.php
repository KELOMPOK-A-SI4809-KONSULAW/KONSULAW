@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] py-10 px-4 sm:px-6 lg:px-8 bg-neutral-50">
    {{-- Header Dasbor Advokat --}}
    <div class="sm:mx-auto sm:w-full sm:max-w-4xl mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 sm:p-8 rounded-3xl border border-neutral-200 shadow-md">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-red-800 text-white shadow-sm">
                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971Z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-neutral-950">Dasbor Praktik Advokat</h1>
                        @if ($profil->status_verifikasi === 'disetujui')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <svg class="w-3.5 h-3.5 mr-1 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Terverifikasi
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                Status: Menunggu Verifikasi
                            </span>
                        @endif
                    </div>
                    <p class="text-xs sm:text-sm text-neutral-600 mt-0.5">
                        Selamat datang, <span class="font-bold text-neutral-900">{{ $user->nama ?? $user->name }}</span>. Kelola ketersediaan layanan dan kuota konsultasi pro bono Anda.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a
                    href="{{ route('advokat.profil.edit') }}"
                    class="rounded-xl border border-neutral-300 bg-white px-4 py-2.5 text-xs font-bold text-neutral-800 transition hover:border-red-800 hover:text-red-800 shadow-2xs"
                >
                    Kelola Profil &amp; Spesialisasi &rarr;
                </a>
            </div>
        </div>
    </div>

    {{-- Navigasi Tab Advokat --}}
    <div class="sm:mx-auto sm:w-full sm:max-w-4xl mb-6">
        <div class="flex items-center gap-2 border-b border-neutral-200 pb-2">
            <a
                href="{{ route('advokat.dashboard') }}"
                class="rounded-xl bg-red-800 px-4 py-2 text-xs sm:text-sm font-bold text-white shadow-2xs"
            >
                Dasbor &amp; Pengaturan Layanan
            </a>
            <a
                href="{{ route('advokat.profil.edit') }}"
                class="rounded-xl px-4 py-2 text-xs sm:text-sm font-bold text-neutral-600 hover:text-red-800 hover:bg-neutral-100 transition"
            >
                Profil Profesional &amp; Spesialisasi
            </a>
        </div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-4xl space-y-6">
        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-bold">Berhasil Diperbarui!</p>
                    <p class="text-xs mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- 3 Kartu Statistik Ringkas --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Kartu 1: Status Ketersediaan --}}
            <div class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-neutral-500">Status Konsultasi</span>
                <div class="mt-2 flex items-center gap-2">
                    @if ($profil->ketersediaan)
                        <span class="flex h-3 w-3 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-base font-extrabold text-emerald-700">Aktif Menerima</span>
                    @else
                        <span class="flex h-3 w-3 rounded-full bg-neutral-400"></span>
                        <span class="text-base font-extrabold text-neutral-600">Sedang Ditutup</span>
                    @endif
                </div>
                <p class="mt-1 text-xs text-neutral-500">Tampil di Beranda Konsulaw</p>
            </div>

            {{-- Kartu 2: Kuota Pro Bono Bulan Ini --}}
            <div class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-neutral-500">Sisa Kuota Pro Bono</span>
                <p class="mt-1 text-2xl font-black text-red-800">
                    {{ $profil->kuota_probono ?? 0 }} <span class="text-xs font-semibold text-neutral-500">Kasus / Bulan</span>
                </p>
                <p class="mt-1 text-xs text-neutral-500">Bantuan hukum tanpa biaya</p>
            </div>

            {{-- Kartu 3: Total Konsultasi --}}
            <div class="rounded-3xl border border-neutral-200 bg-white p-5 shadow-xs">
                <span class="text-xs font-bold uppercase tracking-wider text-neutral-500">Riwayat Konsultasi</span>
                <p class="mt-1 text-2xl font-black text-neutral-950">
                    {{ $totalKonsultasi }} <span class="text-xs font-semibold text-neutral-500">Total Kasus</span>
                </p>
                <p class="mt-1 text-xs text-neutral-500">{{ $konsultasiAktif }} konsultasi sedang diproses</p>
            </div>
        </div>

        {{-- FORM PENGATURAN KETERSEDIAAN & KUOTA PRO BONO --}}
        <div class="bg-white py-8 px-6 shadow-md rounded-3xl border border-neutral-200 sm:px-10">
            <div class="border-b border-neutral-200 pb-4 mb-6">
                <h2 class="text-lg font-extrabold text-neutral-950">
                    Pengaturan Ketersediaan Konsultasi &amp; Kuota Pro Bono
                </h2>
                <p class="text-xs text-neutral-600 mt-1">
                    Atur apakah Anda sedang bersedia menerima permintaan konsultasi awal dari masyarakat serta tentukan batasan kuota cuma-cuma (pro bono) untuk bulan ini.
                </p>
            </div>

            <form method="POST" action="{{ route('advokat.layanan.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- 1. TOGGLE SWITCH KETERSEDIAAN LAYANAN --}}
                <div class="p-5 rounded-2xl border-2 {{ $profil->ketersediaan ? 'border-emerald-300 bg-emerald-50/40' : 'border-neutral-300 bg-neutral-50' }} transition">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-extrabold text-sm text-neutral-950">Ketersediaan Menerima Konsultasi Baru</span>
                                @if ($profil->ketersediaan)
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                                        Online / Menerima
                                    </span>
                                @else
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-full bg-neutral-200 text-neutral-700">
                                        Offline / Sibuk
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-neutral-600 max-w-xl leading-relaxed">
                                Bila opsi ini diaktifkan, profil Anda akan ditampilkan di direktori Beranda utama dengan tombol <strong>"Konsultasi"</strong> aktif. Bila dimatikan, sistem akan menyembunyikan status ketersediaan agar Anda tidak menerima konsultasi baru sementara waktu.
                            </p>
                        </div>

                        {{-- Toggle Switcher Element --}}
                        <div class="shrink-0 flex items-center">
                            <input type="hidden" name="ketersediaan" value="0">
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input
                                    type="checkbox"
                                    name="ketersediaan"
                                    id="toggle-ketersediaan"
                                    value="1"
                                    class="sr-only peer"
                                    {{ old('ketersediaan', $profil->ketersediaan) ? 'checked' : '' }}
                                >
                                <div class="w-14 h-7 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[4px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-800"></div>
                                <span id="label-ketersediaan" class="ml-3 text-xs font-bold text-neutral-800 peer-checked:text-red-800">
                                    {{ old('ketersediaan', $profil->ketersediaan) ? 'Aktif Menerima' : 'Tidak Menerima' }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- 2. INPUT KUOTA MAKSIMAL PRO BONO --}}
                <div>
                    <label for="kuota_probono" class="block text-sm font-bold text-neutral-900">
                        Kuota Maksimal Konsultasi Pro Bono (Bulan Berjalan) <span class="text-red-700">*</span>
                    </label>
                    <div class="mt-1.5 relative max-w-xs">
                        <input
                            id="kuota_probono"
                            name="kuota_probono"
                            type="number"
                            min="0"
                            max="100"
                            required
                            value="{{ old('kuota_probono', $profil->kuota_probono ?? 5) }}"
                            class="block w-full rounded-xl border border-neutral-300 px-4 py-3 pr-24 text-neutral-950 font-bold text-base focus:border-red-700 focus:outline-none focus:ring-2 focus:ring-red-800/20"
                        >
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-xs font-bold text-neutral-500">
                            Kasus / Bln
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-neutral-500 leading-relaxed">
                        Sesuai komitmen SDG 16, kuota ini diperuntukkan bagi warga pedesaan dan masyarakat tidak mampu. Kuota akan otomatis dipotong setiap kali Anda menyetujui permohonan konsultasi jalur pro bono.
                    </p>
                </div>

                {{-- Tombol Simpan Pengaturan --}}
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full sm:w-auto flex justify-center items-center gap-2 rounded-xl bg-red-800 px-6 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                    >
                        <span>Simpan Pengaturan Layanan &amp; Kuota</span>
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('toggle-ketersediaan');
        const label = document.getElementById('label-ketersediaan');
        if (toggle && label) {
            toggle.addEventListener('change', function () {
                label.textContent = this.checked ? 'Aktif Menerima' : 'Tidak Menerima';
            });
        }
    });
</script>
@endsection
