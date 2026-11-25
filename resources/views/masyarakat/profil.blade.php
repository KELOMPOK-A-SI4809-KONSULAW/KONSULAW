@extends('layouts.app')

@section('title', 'Profil Saya & Layanan Hukum — Konsulaw')

@php
    $namaUser   = $user->nama ?? $user->name ?? 'Masyarakat';
    $statusEko  = $user->status_ekonomi ?? 'reguler';
    $tabAktif   = request('tab', $tabAktif ?? 'informasi-pribadi');

    $tabs = [
        'informasi-pribadi' => 'Informasi Pribadi',
        'status-layanan'    => 'Status Layanan Pro Bono',
        'konsultasi-saya'   => 'Konsultasi Saya',
        'keamanan'          => 'Keamanan Akun',
    ];
@endphp

@section('content')
{{-- ============================================================ --}}
{{-- HEADER PROFIL MASYARAKAT --}}
{{-- ============================================================ --}}
<div class="bg-ivory border-b border-line">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex flex-col sm:flex-row sm:items-start gap-5">
            {{-- Foto Profil Interaktif --}}
            <div class="shrink-0">
                <div class="relative group">
                    <button type="button" onclick="document.getElementById('avatar-file-input').click()" class="relative block rounded-full focus:outline-none focus:ring-4 focus:ring-accent-soft cursor-pointer transition" title="Klik untuk mengganti foto profil">
                        @if ($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $namaUser }}" class="h-20 w-20 rounded-full object-cover shadow-btn ring-4 ring-accent-soft group-hover:brightness-90 transition">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-accent text-2xl font-bold text-white shadow-btn ring-4 ring-accent-soft group-hover:bg-accent-dark transition font-display">
                                {{ $inisial }}
                            </div>
                        @endif
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-full bg-black/45 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="h-5 w-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                            <span class="text-[10px] font-bold">Ubah</span>
                        </div>
                    </button>
                    {{-- Mini Camera Badge --}}
                    <button type="button" onclick="document.getElementById('avatar-file-input').click()" class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full bg-paper border border-line shadow-sm text-ink-soft hover:text-accent hover:border-accent transition cursor-pointer" title="Unggah foto profil">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </button>
                </div>

                {{-- Hidden Form for Instant Avatar Upload --}}
                <form id="avatar-form" action="{{ route('masyarakat.profil.foto') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatar-file-input" name="foto_profil" accept="image/png,image/jpeg,image/jpg,image/webp" onchange="document.getElementById('avatar-form').submit()">
                </form>
            </div>

            {{-- Info Pengguna --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="font-display text-xl sm:text-2xl font-bold text-ink truncate">
                        {{ $namaUser }}
                    </h1>
                    <span class="rounded-full bg-paper border border-line px-2.5 py-0.5 text-xs font-semibold text-ink-soft">
                        Masyarakat
                    </span>

                    {{-- Badge Status Layanan Ekonomi (B4.1) --}}
                    @if ($statusEko === 'pro_bono')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3 py-0.5 text-xs font-bold text-success">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            Pro Bono Aktif
                        </span>
                    @elseif ($statusEko === 'pending')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3 py-0.5 text-xs font-bold text-amber-800">
                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Verifikasi Diproses
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-paper border border-line px-3 py-0.5 text-xs font-semibold text-ink-soft">
                            Reguler
                        </span>
                    @endif
                </div>

                <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-ink-soft">
                    <span>{{ $user->email }}</span>
                    <span>&bull;</span>
                    <span>{{ $user->no_hp ?? 'No. HP belum diatur' }}</span>
                    <span>&bull;</span>
                    <span>{{ $user->wilayah->nama ?? 'Domisili belum diatur' }}</span>
                </div>

                {{-- Ringkasan Sesi Konsultasi --}}
                <div class="mt-4 flex items-center gap-4 text-xs">
                    <div class="rounded-full bg-paper border border-line px-3.5 py-1 text-ink-soft">
                        <strong class="text-ink font-bold">{{ $totalBerjalan }}</strong> Konsultasi Berlangsung
                    </div>
                    <div class="rounded-full bg-paper border border-line px-3.5 py-1 text-ink-soft">
                        <strong class="text-ink font-bold">{{ $totalSelesai }}</strong> Konsultasi Selesai
                    </div>
                </div>
            </div>

            {{-- Tombol Utama CTA Ajukan Konsultasi --}}
            <div class="shrink-0 sm:self-center">
                <a
                    href="{{ route('konsultasi.create') }}"
                    class="rounded-full bg-accent hover:bg-accent-dark px-5 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95 flex items-center gap-2"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Ajukan Konsultasi
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- KONTEN UTAMA BER-TAB --}}
{{-- ============================================================ --}}
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
    {{-- Alerts --}}
    @if (session('success'))
        <div class="mb-6 rounded-[16px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 flex items-start gap-3">
            <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
            </svg>
            <div class="flex-1">
                <p class="font-bold">Berhasil</p>
                <p class="mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-[16px] bg-red-50 border border-red-200 p-4 text-xs sm:text-sm text-red-800 flex items-start gap-3">
            <svg class="h-5 w-5 text-red-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            <div class="flex-1">
                <p class="font-bold">Perhatian</p>
                <p class="mt-0.5">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-[16px] bg-red-50 border border-red-200 p-4 text-xs sm:text-sm text-red-800">
            <p class="font-bold">Mohon periksa kembali isian formulir:</p>
            <ul class="mt-1 list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Navigasi Tab (Pills) --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-4 border-b border-line mb-8">
        @foreach ($tabs as $key => $label)
            <a
                href="{{ route('masyarakat.profil.edit', ['tab' => $key]) }}"
                class="rounded-full px-4 py-2 text-xs sm:text-sm font-bold whitespace-nowrap transition {{ $tabAktif === $key ? 'bg-accent text-white shadow-btn' : 'bg-paper border border-line text-ink-soft hover:text-accent hover:border-accent' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- ============================================================ --}}
    {{-- TAB 1: INFORMASI PRIBADI --}}
    {{-- ============================================================ --}}
    @if ($tabAktif === 'informasi-pribadi')
        <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card max-w-3xl">
            <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                Informasi Pribadi & Kontak
            </h2>
            <p class="text-xs sm:text-sm text-ink-soft mt-1">
                Data diri Anda digunakan untuk mencocokkan advokat terdekat sesuai wilayah domisili perkara.
            </p>

            <form action="{{ route('masyarakat.profil.update') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Nama Lengkap --}}
                <div>
                    <label for="nama" class="block text-xs sm:text-sm font-bold text-ink">
                        Nama Lengkap <span class="text-accent">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        required
                        value="{{ old('nama', $user->nama ?? $user->name) }}"
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                    >
                </div>

                {{-- Email (Read-only) --}}
                <div>
                    <label class="block text-xs sm:text-sm font-bold text-ink">
                        Alamat Email
                    </label>
                    <input
                        type="email"
                        value="{{ $user->email }}"
                        disabled
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory/60 px-4 py-2.5 text-xs sm:text-sm text-ink-soft cursor-not-allowed"
                    >
                    <p class="mt-1 text-[11px] text-ink-soft">Alamat email terdaftar dan tidak dapat diubah secara langsung.</p>
                </div>

                {{-- NIK --}}
                <div>
                    <label for="nik" class="block text-xs sm:text-sm font-bold text-ink">
                        Nomor Induk Kependudukan (NIK) <span class="text-[11px] font-normal text-ink-soft">(16 Digit)</span>
                    </label>
                    <input
                        type="text"
                        name="nik"
                        id="nik"
                        maxlength="16"
                        value="{{ old('nik', $user->nik) }}"
                        placeholder="Contoh: 3204123456780001"
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none font-mono"
                    >
                </div>

                {{-- No. HP / WhatsApp --}}
                <div>
                    <label for="no_hp" class="block text-xs sm:text-sm font-bold text-ink">
                        Nomor Handphone / WhatsApp <span class="text-accent">*</span>
                    </label>
                    <input
                        type="text"
                        name="no_hp"
                        id="no_hp"
                        required
                        value="{{ old('no_hp', $user->no_hp) }}"
                        placeholder="Contoh: 081234567890"
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                    >
                </div>

                {{-- Wilayah Domisili --}}
                <div>
                    <label for="wilayah_id" class="block text-xs sm:text-sm font-bold text-ink">
                        Wilayah Domisili (Kota / Kabupaten) <span class="text-accent">*</span>
                    </label>
                    <div class="relative mt-1.5">
                        <select
                            name="wilayah_id"
                            id="wilayah_id"
                            class="w-full appearance-none rounded-[14px] border border-line bg-ivory px-4 py-2.5 pr-10 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                        >
                            <option value="">-- Pilih Wilayah Domisili --</option>
                            @foreach ($wilayahList as $wil)
                                <option value="{{ $wil->id }}" {{ old('wilayah_id', $user->wilayah_id) == $wil->id ? 'selected' : '' }}>
                                    {{ $wil->nama }} ({{ ucfirst(str_replace('_', ' ', $wil->tingkat)) }})
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-ink-soft">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Alamat Lengkap --}}
                <div>
                    <label for="alamat" class="block text-xs sm:text-sm font-bold text-ink">
                        Alamat Lengkap KTP / Tempat Tinggal
                    </label>
                    <textarea
                        name="alamat"
                        id="alamat"
                        rows="3"
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory p-3.5 text-xs sm:text-sm text-ink placeholder:text-ink-soft/60 focus:border-accent focus:bg-paper focus:outline-none"
                    >{{ old('alamat', $user->alamat) }}</textarea>
                </div>

                <div class="pt-4 border-t border-line flex justify-end">
                    <button
                        type="submit"
                        class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                    >
                        Simpan Perubahan Profil &rarr;
                    </button>
                </div>
            </form>
        </div>

    {{-- ============================================================ --}}
    {{-- TAB 2: STATUS LAYANAN PRO BONO (B4.3 4 Kondisi dengan Aksi) --}}
    {{-- ============================================================ --}}
    @elseif ($tabAktif === 'status-layanan')
        <div class="space-y-6 max-w-3xl">
            <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                <div class="flex items-start justify-between gap-4 border-b border-line pb-5">
                    <div>
                        <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                            Status Layanan Bantuan Hukum Pro Bono
                        </h2>
                        <p class="text-xs sm:text-sm text-ink-soft mt-1">
                            Layanan konsultasi cuma-cuma bagi masyarakat pencari keadilan yang memenuhi kriteria ekonomi.
                        </p>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @if ($statusEko === 'pro_bono')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-3.5 py-1 text-xs font-bold text-success">
                                <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                Pro Bono Aktif
                            </span>
                        @elseif ($statusEko === 'pending')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3.5 py-1 text-xs font-bold text-amber-800">
                                <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Sedang Diproses
                            </span>
                        @elseif ($verifikasiEkonomi && $verifikasiEkonomi->status === 'ditolak')
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 border border-red-200 px-3.5 py-1 text-xs font-bold text-danger">
                                Ditolak
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-paper border border-line px-3.5 py-1 text-xs font-semibold text-ink-soft">
                                Reguler (Belum Mengajukan)
                            </span>
                        @endif
                    </div>
                </div>

                {{-- KONDISI 1: BELUM PERNAH MENGAJUKAN --}}
                @if ($statusEko === 'reguler' && (!$verifikasiEkonomi || $verifikasiEkonomi->status !== 'ditolak'))
                    <div class="mt-6 space-y-6">
                        <div class="rounded-[16px] bg-ivory border border-line p-5 space-y-3">
                            <h3 class="font-display text-base font-bold text-ink">
                                Akun Anda Saat Ini Berstatus Reguler
                            </h3>
                            <p class="text-xs text-ink-soft leading-relaxed">
                                Jika Anda memerlukan bantuan hukum tanpa biaya (cuma-cuma), silakan ajukan verifikasi status ekonomi dengan mengunggah salah satu dokumen resmi:
                            </p>
                            <ul class="text-xs text-ink-soft list-disc list-inside space-y-1">
                                <li>Surat Keterangan Tidak Mampu (SKTM) dari Kelurahan / Desa</li>
                                <li>Kartu Indonesia Sehat (KIS) Penerima Bantuan Iuran</li>
                                <li>Kartu Keluarga Sejahtera (KKS) / PKH</li>
                                <li>Bukti slip pendapatan / surat keterangan penghasilan</li>
                            </ul>
                        </div>

                        {{-- Formulir Upload Verifikasi Ekonomi --}}
                        <form action="{{ route('masyarakat.profil.verifikasi-ekonomi') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="dokumen" class="block text-xs sm:text-sm font-bold text-ink">
                                    Unggah Dokumen Bukti Ekonomi <span class="text-accent">*</span>
                                </label>
                                <input
                                    type="file"
                                    name="dokumen"
                                    id="dokumen"
                                    required
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1.5 block w-full text-xs text-ink-soft file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-accent-soft file:text-accent-dark hover:file:bg-accent/20 cursor-pointer"
                                >
                                <p class="mt-1 text-[11px] text-ink-soft">
                                    Format: PDF, JPG, PNG (Maks. 5 MB).
                                </p>
                            </div>

                            <div class="rounded-[14px] bg-ivory border border-line p-3 text-[11px] text-ink-soft flex items-start gap-2">
                                <svg class="h-4 w-4 text-accent shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                                <span>
                                    <strong>Perlindungan Data Pribadi (TR-04):</strong> Berkas verifikasi ekonomi disimpan di penyimpanan privat dan hanya digunakan oleh administrator sistem untuk verifikasi kelayakan bantuan pro bono. Berkas tidak pernah ditampilkan ke publik atau pihak advokat.
                                </span>
                            </div>

                            <div class="pt-3 flex flex-wrap items-center justify-between gap-3">
                                <a href="{{ route('home') }}" class="rounded-full border border-line bg-paper px-4 py-2 text-xs font-semibold text-ink-soft hover:text-ink transition">
                                    &larr; Cari Advokat (Jalur Reguler)
                                </a>

                                <button
                                    type="submit"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                                >
                                    Ajukan Verifikasi Pro Bono &rarr;
                                </button>
                            </div>
                        </form>
                    </div>

                {{-- KONDISI 2: SEDANG DIPROSES (PENDING) --}}
                @elseif ($statusEko === 'pending')
                    <div class="mt-6 space-y-5">
                        <div class="rounded-[16px] bg-amber-50 border border-amber-200 p-5 flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-display text-base font-bold text-amber-900">
                                    Dokumen Verifikasi Sedang Diperiksa Admin
                                </h3>
                                <p class="text-xs text-amber-800 leading-relaxed">
                                    Permohonan status Pro Bono Anda telah diterima dan saat ini berada dalam antrean pemeriksaan administrator sistem.
                                </p>
                                @if ($verifikasiEkonomi)
                                    <p class="text-[11px] text-amber-800/80 font-mono mt-2">
                                        Tanggal pengajuan: {{ $verifikasiEkonomi->created_at->translatedFormat('d F Y, H:i') }} WIB
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 border-t border-line flex flex-wrap items-center justify-between gap-3">
                            <p class="text-xs text-ink-soft">
                                Anda tetap dapat mengajukan konsultasi berbayar lewat jalur reguler kapan saja.
                            </p>
                            <a href="{{ route('home') }}" class="rounded-full bg-accent hover:bg-accent-dark px-5 py-2 text-xs font-bold text-white shadow-btn transition">
                                Cari Advokat (Reguler) &rarr;
                            </a>
                        </div>
                    </div>

                {{-- KONDISI 3: DISETUJUI / PRO BONO AKTIF --}}
                @elseif ($statusEko === 'pro_bono')
                    <div class="mt-6 space-y-5">
                        <div class="rounded-[16px] bg-emerald-50 border border-emerald-200 p-5 flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-success">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="font-display text-base font-bold text-emerald-900">
                                    Status Pro Bono Anda Aktif & Terverifikasi
                                </h3>
                                <p class="text-xs text-emerald-800 leading-relaxed">
                                    Selamat! Akun Anda berhak menerima layanan konsultasi hukum cuma-cuma (gratis) dari advokat mitra yang masih memiliki alokasi kuota pro bono.
                                </p>
                            </div>
                        </div>

                        {{-- Tombol Aksi Lanjutan (B4.3) --}}
                        <div class="pt-4 border-t border-line flex flex-wrap items-center gap-3">
                            @if ($adaDraf)
                                <a
                                    href="{{ route('konsultasi.create') }}"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                                >
                                    Lanjutkan Pengajuan Konsultasi (Draf Tersimpan) &rarr;
                                </a>
                            @endif

                            <a
                                href="{{ route('home', ['pro_bono' => 1]) }}"
                                class="rounded-full bg-purple-50 hover:bg-purple-100 border border-purple-200 px-5 py-2.5 text-xs sm:text-sm font-bold text-purple-900 transition"
                            >
                                Cari Advokat Kuota Pro Bono Tersedia &rarr;
                            </a>

                            <a
                                href="{{ route('masyarakat.profil.edit', ['tab' => 'konsultasi-saya']) }}"
                                class="rounded-full border border-line bg-paper px-4 py-2.5 text-xs font-semibold text-ink-soft hover:text-ink transition"
                            >
                                Lihat Konsultasi Saya
                            </a>
                        </div>
                    </div>

                {{-- KONDISI 4: DITOLAK (DENGAN ALASAN & UNGGAH ULANG) --}}
                @elseif ($verifikasiEkonomi && $verifikasiEkonomi->status === 'ditolak')
                    <div class="mt-6 space-y-6">
                        <div class="rounded-[16px] bg-red-50 border border-red-200 p-5 space-y-2">
                            <div class="flex items-center gap-2 text-danger font-bold text-sm">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                                </svg>
                                Pengajuan Verifikasi Belum Disetujui
                            </div>
                            <p class="text-xs text-red-900 leading-relaxed">
                                <strong>Catatan Administrator:</strong> "{{ $verifikasiEkonomi->catatan_admin ?? 'Dokumen yang diunggah belum memenuhi persyaratan atau tidak terbaca dengan jelas.' }}"
                            </p>
                        </div>

                        {{-- Formulir Unggah Ulang Dokumen --}}
                        <form action="{{ route('masyarakat.profil.verifikasi-ekonomi') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label for="dokumen-ulang" class="block text-xs sm:text-sm font-bold text-ink">
                                    Unggah Ulang Dokumen Bukti yang Sesuai <span class="text-accent">*</span>
                                </label>
                                <input
                                    type="file"
                                    name="dokumen"
                                    id="dokumen-ulang"
                                    required
                                    accept=".pdf,.jpg,.jpeg,.png"
                                    class="mt-1.5 block w-full text-xs text-ink-soft file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-accent-soft file:text-accent-dark hover:file:bg-accent/20 cursor-pointer"
                                >
                            </div>

                            <div class="pt-3 flex flex-wrap items-center justify-between gap-3">
                                <a href="{{ route('home') }}" class="rounded-full border border-line bg-paper px-4 py-2 text-xs font-semibold text-ink-soft hover:text-ink transition">
                                    Lanjut Konsultasi Reguler
                                </a>

                                <button
                                    type="submit"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition"
                                >
                                    Kirim Ulang Dokumen &rarr;
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

    {{-- ============================================================ --}}
    {{-- TAB 3: KONSULTASI SAYA (FR-12, B4.4) --}}
    {{-- ============================================================ --}}
    @elseif ($tabAktif === 'konsultasi-saya')
        <div class="space-y-6">
            {{-- Filter Status Bar --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-1.5 overflow-x-auto pb-2">
                    @php
                        $statusQuery = request('status', 'semua');
                        $filters = [
                            'semua'               => 'Semua',
                            'menunggu_tanggapan'  => 'Menunggu Tanggapan',
                            'menunggu_pembayaran' => 'Menunggu Pembayaran',
                            'berlangsung'         => 'Berlangsung',
                            'selesai'             => 'Selesai',
                            'ditolak'             => 'Ditolak / Batal',
                        ];
                    @endphp
                    @foreach ($filters as $fKey => $fLabel)
                        <a
                            href="{{ route('masyarakat.profil.edit', ['tab' => 'konsultasi-saya', 'status' => $fKey]) }}"
                            class="rounded-full px-3 py-1.5 text-xs font-semibold whitespace-nowrap transition {{ $statusQuery === $fKey ? 'bg-ink text-white' : 'bg-paper border border-line text-ink-soft hover:text-ink' }}"
                        >
                            {{ $fLabel }}
                        </a>
                    @endforeach
                </div>

                <a
                    href="{{ route('konsultasi.create') }}"
                    class="rounded-full bg-accent hover:bg-accent-dark px-4 py-2 text-xs font-bold text-white shadow-btn transition shrink-0"
                >
                    + Ajukan Baru
                </a>
            </div>

            {{-- Daftar Kartu Konsultasi --}}
            <div class="space-y-4">
                @forelse ($daftarKonsultasi as $k)
                    @php
                        $advName = $k->advokat->nama ?? $k->advokat->name ?? 'Advokat';
                    @endphp
                    <div class="rounded-[20px] border border-line bg-paper p-5 sm:p-6 shadow-card hover:-translate-y-0.5 transition flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-y-2 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- Status Pill --}}
                                @if ($k->status === 'berlangsung')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-bold text-success">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success animate-pulse"></span>
                                        Berlangsung
                                    </span>
                                @elseif ($k->status === 'menunggu_pembayaran')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 border border-blue-200 px-2.5 py-0.5 text-xs font-bold text-blue-800">
                                        Menunggu Pembayaran
                                    </span>
                                @elseif ($k->status === 'menunggu_tanggapan')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                        Menunggu Tanggapan
                                    </span>
                                @elseif ($k->status === 'selesai')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 border border-neutral-300 px-2.5 py-0.5 text-xs font-bold text-neutral-800">
                                        Selesai
                                    </span>
                                @elseif ($k->status === 'ditolak')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-50 border border-red-200 px-2.5 py-0.5 text-xs font-bold text-danger">
                                        Ditolak
                                    </span>
                                @else
                                    <span class="rounded-full bg-paper border border-line px-2.5 py-0.5 text-xs font-semibold text-ink-soft">
                                        {{ ucfirst($k->status) }}
                                    </span>
                                @endif

                                {{-- Jenis Layanan --}}
                                @if ($k->jenis_layanan === 'pro_bono')
                                    <span class="rounded-full bg-purple-50 border border-purple-200 px-2 py-0.5 text-[10px] font-bold text-purple-800">
                                        Pro Bono
                                    </span>
                                @else
                                    <span class="rounded-full bg-accent-soft px-2 py-0.5 text-[10px] font-bold text-accent-dark">
                                        Reguler
                                    </span>
                                @endif

                                <span class="text-xs text-ink-soft">
                                    {{ $k->bidangHukum->nama ?? 'Hukum' }}
                                </span>
                            </div>

                            <h3 class="font-display text-base sm:text-lg font-bold text-ink leading-snug">
                                <a href="{{ route('konsultasi.show', $k->id) }}" class="hover:text-accent transition">
                                    {{ $k->judul }}
                                </a>
                            </h3>

                            <p class="text-xs text-ink-soft">
                                Advokat: <strong class="text-ink">{{ $advName }}</strong> &bull; Diajukan: {{ $k->created_at->translatedFormat('d M Y') }}
                            </p>
                        </div>

                        {{-- Tombol Aksi Cerdas (B4.4) --}}
                        <div class="flex items-center gap-2 shrink-0">
                            @if ($k->status === 'berlangsung')
                                <a
                                    href="{{ route('konsultasi.chat', $k->id) }}"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-4 py-2 text-xs font-bold text-white shadow-btn transition flex items-center gap-1.5"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a.75.75 0 0 1-.974-.94 5.942 5.942 0 0 0 .97-2.73A8.77 8.77 0 0 1 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                    </svg>
                                    Buka Chat &rarr;
                                </a>
                            @elseif ($k->status === 'menunggu_pembayaran')
                                <a
                                    href="{{ route('konsultasi.show', $k->id) }}"
                                    class="rounded-full bg-accent hover:bg-accent-dark px-4 py-2 text-xs font-bold text-white shadow-btn transition"
                                >
                                    Bayar Sekarang &rarr;
                                </a>
                            @elseif ($k->status === 'selesai')
                                <a
                                    href="{{ route('konsultasi.show', $k->id) }}"
                                    class="rounded-full border border-line bg-paper hover:bg-ivory px-3.5 py-1.5 text-xs font-bold text-ink transition shadow-2xs"
                                >
                                    {{ $k->ulasan ? 'Lihat Ulasan' : 'Beri Rating ★' }}
                                </a>
                            @else
                                <a
                                    href="{{ route('konsultasi.show', $k->id) }}"
                                    class="rounded-full border border-line bg-paper hover:bg-ivory px-3.5 py-1.5 text-xs font-semibold text-ink-soft hover:text-ink transition"
                                >
                                    Lihat Rincian &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-[20px] border border-line bg-paper p-10 text-center shadow-card">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-accent-soft text-accent">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <h3 class="font-display text-base font-bold text-ink mt-3">Belum Ada Sesi Konsultasi</h3>
                        <p class="text-xs text-ink-soft max-w-sm mx-auto mt-1 leading-relaxed">
                            Mulai konsultasikan permasalahan hukum Anda kepada advokat profesional terpercaya di platform Konsulaw.
                        </p>
                        <div class="mt-5">
                            <a href="{{ route('konsultasi.create') }}" class="rounded-full bg-accent hover:bg-accent-dark px-5 py-2 text-xs font-bold text-white shadow-btn transition">
                                Ajukan Konsultasi Sekarang &rarr;
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    {{-- ============================================================ --}}
    {{-- TAB 4: KEAMANAN AKUN --}}
    {{-- ============================================================ --}}
    @elseif ($tabAktif === 'keamanan')
        <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card max-w-xl">
            <h2 class="font-display text-lg sm:text-xl font-bold text-ink">
                Keamanan & Kata Sandi
            </h2>
            <p class="text-xs sm:text-sm text-ink-soft mt-1">
                Perbarui kata sandi secara berkala untuk menjaga kerahasiaan konsultasi hukum Anda.
            </p>

            <form action="{{ route('masyarakat.profil.password') }}" method="POST" class="mt-6 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs sm:text-sm font-bold text-ink">
                        Kata Sandi Saat Ini <span class="text-accent">*</span>
                    </label>
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        required
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs sm:text-sm font-bold text-ink">
                        Kata Sandi Baru <span class="text-accent">*</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        minlength="8"
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                    >
                    <p class="mt-1 text-[11px] text-ink-soft">Minimal 8 karakter.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs sm:text-sm font-bold text-ink">
                        Ulangi Kata Sandi Baru <span class="text-accent">*</span>
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        required
                        class="mt-1.5 w-full rounded-[14px] border border-line bg-ivory px-4 py-2.5 text-xs sm:text-sm text-ink focus:border-accent focus:bg-paper focus:outline-none"
                    >
                </div>

                <div class="pt-4 border-t border-line flex justify-end">
                    <button
                        type="submit"
                        class="rounded-full bg-accent hover:bg-accent-dark px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:-translate-y-0.5 active:scale-95"
                    >
                        Perbarui Kata Sandi &rarr;
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
