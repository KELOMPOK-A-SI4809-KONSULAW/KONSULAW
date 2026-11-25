@extends('layouts.app')

@section('title', 'Profil Administrator & Pusat Kendali — Konsulaw')

@section('content')
{{-- ============================================================ --}}
{{-- HEADER PROFIL ADMINISTRATOR --}}
{{-- ============================================================ --}}
<div class="bg-ivory border-b border-line">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex flex-col sm:flex-row sm:items-start gap-5">
            {{-- Avatar / Foto Profil Interaktif --}}
            <div class="shrink-0">
                <div class="relative group">
                    <button type="button" onclick="document.getElementById('avatar-file-input').click()" class="relative block rounded-full focus:outline-none focus:ring-4 focus:ring-accent-soft cursor-pointer transition" title="Klik untuk mengganti foto profil">
                        @if($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama }}" class="h-20 w-20 rounded-full object-cover shadow-btn ring-4 ring-accent-soft group-hover:brightness-90 transition">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-accent text-2xl font-bold text-white shadow-btn ring-4 ring-accent-soft group-hover:bg-accent-dark transition">
                                {{ $inisial }}
                            </div>
                        @endif
                        {{-- Hover Badge / Camera Overlay --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center rounded-full bg-black/45 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="h-5 w-5 mb-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                            <span class="text-[10px] font-bold">Ubah</span>
                        </div>
                    </button>
                    {{-- Mini Camera Badge at bottom-right --}}
                    <button type="button" onclick="document.getElementById('avatar-file-input').click()" class="absolute -bottom-1 -right-1 flex h-7 w-7 items-center justify-center rounded-full bg-paper border border-line shadow-sm text-ink-soft hover:text-accent hover:border-accent transition cursor-pointer" title="Unggah foto profil">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </button>
                </div>

                {{-- Hidden Form for Instant Avatar Upload --}}
                <form id="avatar-form" action="{{ route('admin.profil.foto') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatar-file-input" name="foto_profil" accept="image/png,image/jpeg,image/jpg,image/webp" onchange="document.getElementById('avatar-form').submit()">
                </form>
            </div>

            {{-- Info Utama --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="font-display text-xl sm:text-2xl font-bold text-ink truncate">
                        {{ $user->nama ?? $user->name ?? 'Administrator Konsulaw' }}
                    </h1>
                    <span class="inline-flex items-center gap-1 rounded-full bg-accent-soft border border-accent/20 px-3 py-0.5 text-xs font-bold text-accent">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                        Administrator Sistem
                    </span>
                </div>

                <div class="mt-1 flex flex-wrap items-center gap-3 text-xs sm:text-sm text-ink-soft">
                    <span>{{ $user->email }}</span>
                    <span>&bull;</span>
                    <span>{{ $user->no_hp ?? 'Belum ada no. HP' }}</span>
                    @if($user->wilayah)
                        <span>&bull;</span>
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 text-accent" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            {{ $user->wilayah->nama }}
                        </span>
                    @endif
                </div>

                <p class="mt-2 text-xs text-ink-soft leading-relaxed max-w-2xl">
                    Bertanggung jawab atas verifikasi kualifikasi advokat, validasi kelayakan layanan pro bono masyarakat, pemeliharaan konten hukum, dan pengawasan operasional platform Konsulaw.
                </p>
            </div>
        </div>

        {{-- Statistik Ringkas Administrator (Proposal Bab 3) --}}
        <div class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            {{-- Antrean Advokat --}}
            <a href="{{ route('admin.profil.edit', ['tab' => 'verifikasi-advokat']) }}" class="rounded-[14px] border border-line bg-paper p-3.5 sm:p-4 shadow-card hover:border-accent transition group">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-ink-soft">Advokat Pending</span>
                    @if($totalAdvokatPending > 0)
                        <span class="flex h-2 w-2 rounded-full bg-warning animate-pulse"></span>
                    @endif
                </div>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="font-display text-2xl font-bold {{ $totalAdvokatPending > 0 ? 'text-warning' : 'text-ink' }}">
                        {{ $totalAdvokatPending }}
                    </span>
                    <span class="text-xs text-ink-soft">antrean</span>
                </div>
            </a>

            {{-- Advokat Aktif --}}
            <div class="rounded-[14px] border border-line bg-paper p-3.5 sm:p-4 shadow-card">
                <span class="text-xs font-semibold text-ink-soft">Advokat Terverifikasi</span>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="font-display text-2xl font-bold text-success">{{ $totalAdvokatAktif }}</span>
                    <span class="text-xs text-ink-soft">aktif</span>
                </div>
            </div>

            {{-- Pro Bono Pending --}}
            <a href="{{ route('admin.profil.edit', ['tab' => 'verifikasi-probono']) }}" class="rounded-[14px] border border-line bg-paper p-3.5 sm:p-4 shadow-card hover:border-accent transition group">
                <span class="text-xs font-semibold text-ink-soft">Pengajuan Pro Bono</span>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="font-display text-2xl font-bold {{ $totalProBonoPending > 0 ? 'text-info' : 'text-ink' }}">
                        {{ $totalProBonoPending }}
                    </span>
                    <span class="text-xs text-ink-soft">menunggu</span>
                </div>
            </a>

            {{-- Total Pengguna --}}
            <div class="rounded-[14px] border border-line bg-paper p-3.5 sm:p-4 shadow-card">
                <span class="text-xs font-semibold text-ink-soft">Total Pengguna</span>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="font-display text-2xl font-bold text-ink">{{ $totalUser }}</span>
                    <span class="text-xs text-ink-soft">akun</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- KONTEN UTAMA: NAVIGASI TAB & FORMULIR --}}
{{-- ============================================================ --}}
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-8">
    {{-- Notifikasi Flash --}}
    @if(session('success'))
        <div class="mb-6 flex items-start gap-3 rounded-[14px] border border-emerald-300 bg-emerald-50 p-4 text-sm text-success">
            <svg class="h-5 w-5 shrink-0 mt-0.5 text-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <div class="flex-1 font-medium">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 flex items-start gap-3 rounded-[14px] border border-red-300 bg-red-50 p-4 text-sm text-danger">
            <svg class="h-5 w-5 shrink-0 mt-0.5 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            <div class="flex-1 font-medium">{{ session('error') }}</div>
        </div>
    @endif

    @if(isset($errors) && $errors->any())
        <div class="mb-6 rounded-[14px] border border-danger bg-red-50 p-4 text-sm text-danger">
            <p class="font-bold mb-1">Terdapat kesalahan pengisian formulir:</p>
            <ul class="list-disc list-inside space-y-0.5 text-xs">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    {{-- Layout Tab: Sidebar + Konten --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Navigasi Sidebar Tab --}}
        <div class="md:col-span-1">
            <nav class="space-y-1.5 rounded-[20px] border border-line bg-paper p-3 shadow-card sticky top-24">
                <a href="{{ route('admin.profil.edit', ['tab' => 'informasi-pribadi']) }}"
                   class="flex items-center gap-3 rounded-[12px] px-3.5 py-2.5 text-xs font-semibold transition {{ $tabAktif === 'informasi-pribadi' ? 'bg-accent text-white shadow-btn' : 'text-ink-soft hover:bg-ivory hover:text-ink' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Informasi Akun</span>
                </a>

                <a href="{{ route('admin.profil.edit', ['tab' => 'verifikasi-advokat']) }}"
                   class="flex items-center justify-between rounded-[12px] px-3.5 py-2.5 text-xs font-semibold transition {{ $tabAktif === 'verifikasi-advokat' ? 'bg-accent text-white shadow-btn' : 'text-ink-soft hover:bg-ivory hover:text-ink' }}">
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                        </svg>
                        <span>Verifikasi Advokat</span>
                    </div>
                    @if($totalAdvokatPending > 0)
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $tabAktif === 'verifikasi-advokat' ? 'bg-white text-accent' : 'bg-warning text-white' }}">
                            {{ $totalAdvokatPending }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.profil.edit', ['tab' => 'verifikasi-probono']) }}"
                   class="flex items-center justify-between rounded-[12px] px-3.5 py-2.5 text-xs font-semibold transition {{ $tabAktif === 'verifikasi-probono' ? 'bg-accent text-white shadow-btn' : 'text-ink-soft hover:bg-ivory hover:text-ink' }}">
                    <div class="flex items-center gap-3">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.51 48.51 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.583V21" />
                        </svg>
                        <span>Verifikasi Pro Bono</span>
                    </div>
                    @if($totalProBonoPending > 0)
                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $tabAktif === 'verifikasi-probono' ? 'bg-white text-accent' : 'bg-info text-white' }}">
                            {{ $totalProBonoPending }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.profil.edit', ['tab' => 'manajemen-pengguna']) }}"
                   class="flex items-center gap-3 rounded-[12px] px-3.5 py-2.5 text-xs font-semibold transition {{ $tabAktif === 'manajemen-pengguna' ? 'bg-accent text-white shadow-btn' : 'text-ink-soft hover:bg-ivory hover:text-ink' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                    <span>Daftar Pengguna</span>
                </a>

                <a href="{{ route('admin.profil.edit', ['tab' => 'keamanan']) }}"
                   class="flex items-center gap-3 rounded-[12px] px-3.5 py-2.5 text-xs font-semibold transition {{ $tabAktif === 'keamanan' ? 'bg-accent text-white shadow-btn' : 'text-ink-soft hover:bg-ivory hover:text-ink' }}">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <span>Keamanan Sandi</span>
                </a>
            </nav>
        </div>

        {{-- Panel Konten Tab --}}
        <div class="md:col-span-3">
            {{-- ======================================================== --}}
            {{-- TAB 1: INFORMASI AKUN ADMINISTRATOR --}}
            {{-- ======================================================== --}}
            @if($tabAktif === 'informasi-pribadi')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-1">Informasi Akun Administrator</h2>
                    <p class="text-xs text-ink-soft mb-6">Kelola data identitas dan kontak operasional akun admin platform Konsulaw.</p>

                    {{-- Card Foto Profil --}}
                    <div class="rounded-[14px] border border-line bg-ivory/60 p-4 mb-6">
                        <label class="block text-sm font-semibold text-ink mb-2">Foto Profil Administrator</label>
                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <div class="shrink-0">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->nama }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-accent shadow-sm">
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-accent text-xl font-bold text-white shadow-sm">
                                        {{ $inisial }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <div class="flex flex-wrap items-center gap-2 justify-center sm:justify-start">
                                    <button type="button" onclick="document.getElementById('avatar-file-input').click()" class="rounded-full bg-paper border border-line px-4 py-2 text-xs font-semibold text-ink hover:border-accent hover:text-accent transition shadow-xs cursor-pointer">
                                        <svg class="h-3.5 w-3.5 inline mr-1 text-ink-soft" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                        </svg>
                                        Pilih Foto Baru
                                    </button>
                                    @if($user->foto_profil)
                                        <form action="{{ route('admin.profil.hapus-foto') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-full bg-red-50 border border-red-200 px-3.5 py-2 text-xs font-semibold text-danger hover:bg-red-100 transition cursor-pointer">
                                                Hapus Foto
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="mt-1.5 text-xs text-ink-soft">Foto profil akan tampil pada panel administrasi dan log verifikasi. Format JPG, PNG, atau WebP (maks. 2 MB).</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.profil.update-info') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PUT')

                        <div>
                            <label for="nama" class="block text-sm font-semibold text-ink mb-1.5">
                                Nama Lengkap Administrator <span class="text-danger">*</span>
                            </label>
                            <input id="nama" name="nama" type="text"
                                value="{{ old('nama', $user->nama ?? $user->name) }}"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('nama') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ink mb-1.5">Alamat Email Administrator</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="block w-full rounded-[14px] border border-line bg-line/40 px-4 py-3 text-sm text-ink-soft cursor-not-allowed">
                            <p class="mt-1 text-xs text-ink-soft">Email administrator utama ditetapkan oleh sistem.</p>
                        </div>

                        <div>
                            <label for="no_hp" class="block text-sm font-semibold text-ink mb-1.5">
                                Nomor Kontak Operasional / WhatsApp <span class="text-danger">*</span>
                            </label>
                            <input id="no_hp" name="no_hp" type="tel"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                placeholder="08xx-xxxx-xxxx"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('no_hp') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="wilayah_id" class="block text-sm font-semibold text-ink mb-1.5">
                                Kantor Wilayah / Basis Operasional
                            </label>
                            <select id="wilayah_id" name="wilayah_id"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                                <option value="">— Pilih Wilayah —</option>
                                @foreach($daftarWilayah as $w)
                                    <option value="{{ $w->id }}" {{ old('wilayah_id', $user->wilayah_id) == $w->id ? 'selected' : '' }}>
                                        {{ $w->nama }} ({{ ucwords(str_replace('_', ' ', $w->tingkat)) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-semibold text-ink mb-1.5">
                                Alamat Kantor Operasional Platform
                            </label>
                            <textarea id="alamat" name="alamat" rows="3"
                                placeholder="Alamat kantor sekretariat Konsulaw"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark cursor-pointer">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 2: VERIFIKASI DOKUMEN ADVOKAT (Use Case C Hal 25-27 Proposal) --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'verifikasi-advokat')
                <div class="space-y-6">
                    <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                            <div>
                                <h2 class="font-display text-lg font-bold text-ink">Antrean Verifikasi Kredensial Advokat</h2>
                                <p class="text-xs text-ink-soft">Pemeriksaan keabsahan Ijazah Sarjana Hukum (S.H.), Berita Acara Sumpah (BAS), dan Kartu Tanda Anggota (KTA).</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-bold text-warning">
                                {{ $antreanAdvokat->count() }} Menunggu Tinjauan
                            </span>
                        </div>

                        @if($antreanAdvokat->isEmpty())
                            <div class="rounded-[14px] border border-line bg-ivory/50 p-8 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-success mb-3">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </div>
                                <h3 class="font-display text-base font-bold text-ink">Semua Dokumen Telah Diverifikasi</h3>
                                <p class="mt-1 text-xs text-ink-soft">Tidak ada permohonan verifikasi advokat yang sedang menunggu peninjauan saat ini.</p>
                            </div>
                        @else
                            <div class="space-y-6">
                                @foreach($antreanAdvokat as $adv)
                                    @php
                                        $uAdv = $adv->user;
                                        $advInisial = mb_strtoupper(mb_substr($uAdv->nama ?? $uAdv->name ?? 'A', 0, 1) . mb_substr(explode(' ', $uAdv->nama ?? $uAdv->name ?? 'A')[1] ?? 'D', 0, 1));
                                    @endphp
                                    <div class="rounded-[16px] border border-amber-200 bg-amber-50/30 p-5 sm:p-6 transition hover:border-amber-300">
                                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5">
                                            {{-- Info Advokat --}}
                                            <div class="flex items-start gap-4 flex-1">
                                                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full bg-accent text-white font-bold text-lg shadow-sm">
                                                    {{ $advInisial }}
                                                </div>
                                                <div class="min-w-0">
                                                    <h3 class="font-display text-base font-bold text-ink">{{ $uAdv->nama ?? $uAdv->name }}</h3>
                                                    <p class="text-xs text-ink-soft">{{ $uAdv->email }} &bull; {{ $uAdv->no_hp }}</p>
                                                    <div class="mt-2 flex flex-wrap gap-2 text-xs">
                                                        <span class="rounded-md bg-paper border border-line px-2 py-0.5 font-medium text-ink">
                                                            No. KTA: <strong>{{ $adv->no_kta ?? '—' }}</strong>
                                                        </span>
                                                        <span class="rounded-md bg-paper border border-line px-2 py-0.5 font-medium text-ink">
                                                            No. BAS: <strong>{{ $adv->no_bas ?? '—' }}</strong>
                                                        </span>
                                                        @if($adv->tahun_mulai_praktik)
                                                            <span class="rounded-md bg-paper border border-line px-2 py-0.5 font-medium text-ink">
                                                                Praktik sejak: <strong>{{ $adv->tahun_mulai_praktik }}</strong>
                                                            </span>
                                                        @endif
                                                    </div>

                                                    @if($adv->bidangHukum->isNotEmpty())
                                                        <div class="mt-2 flex flex-wrap gap-1">
                                                            @foreach($adv->bidangHukum as $bh)
                                                                <span class="rounded-full bg-accent-soft text-accent text-[10px] font-semibold px-2 py-0.5">
                                                                    {{ $bh->nama }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Berkas Dokumen --}}
                                            <div class="w-full lg:w-72 bg-paper rounded-[12px] border border-line p-3.5 space-y-2">
                                                <p class="text-xs font-bold text-ink">Dokumen Pendukung ({{ $adv->dokumen->count() }}/3):</p>
                                                @forelse($adv->dokumen as $dok)
                                                    <div class="flex items-center justify-between text-xs py-1 border-b border-line/60 last:border-b-0">
                                                        <span class="font-semibold text-ink">{{ $dok->jenis }}</span>
                                                        <a href="{{ route('admin.dokumen.unduh', $dok->id) }}" class="inline-flex items-center gap-1 text-accent hover:text-accent-dark font-medium underline">
                                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                                            </svg>
                                                            Unduh Berkas
                                                        </a>
                                                    </div>
                                                @empty
                                                    <p class="text-xs text-danger">Belum ada dokumen yang diunggah.</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        {{-- Tombol Aksi Verifikasi (Setujui / Tolak) --}}
                                        <div class="mt-5 pt-4 border-t border-amber-200/80 flex flex-wrap items-center justify-end gap-3">
                                            {{-- Tombol Tolak (Buka Modal) --}}
                                            <button type="button" onclick="openModalTolak('{{ $adv->id }}', '{{ addslashes($uAdv->nama ?? $uAdv->name) }}')"
                                                    class="rounded-full bg-red-50 border border-red-200 px-4 py-2 text-xs font-bold text-danger hover:bg-red-100 transition cursor-pointer">
                                                Tolak Verifikasi...
                                            </button>

                                            {{-- Form Setujui --}}
                                            <form method="POST" action="{{ route('admin.verifikasi.setujui', $adv->id) }}" onsubmit="return confirm('Apakah Anda yakin telah memeriksa dokumen dan menyetujui advokat {{ addslashes($uAdv->nama ?? $uAdv->name) }}?')">
                                                @csrf
                                                <button type="submit" class="rounded-full bg-emerald-700 px-5 py-2 text-xs font-bold text-white shadow-btn hover:bg-emerald-800 transition cursor-pointer">
                                                    Setujui Verifikasi
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Riwayat Verifikasi Terakhir --}}
                    @if($riwayatVerifikasi->isNotEmpty())
                        <div class="rounded-[20px] border border-line bg-paper p-6 shadow-card">
                            <h3 class="font-display text-base font-bold text-ink mb-4">Riwayat Keputusan Verifikasi Terakhir</h3>
                            <div class="divide-y divide-line">
                                @foreach($riwayatVerifikasi as $rw)
                                    @php $uRw = $rw->user; @endphp
                                    <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                        <div>
                                            <span class="font-bold text-ink">{{ $uRw->nama ?? $uRw->name }}</span>
                                            <span class="text-ink-soft ml-2">({{ $uRw->email }})</span>
                                            @if($rw->catatan_verifikasi)
                                                <p class="mt-0.5 text-danger font-medium">Catatan: {{ $rw->catatan_verifikasi }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            @if(in_array($rw->status_verifikasi, ['disetujui', 'terverifikasi']))
                                                <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-bold text-success">
                                                    Terverifikasi
                                                </span>
                                            @elseif($rw->status_verifikasi === 'ditolak')
                                                <span class="rounded-full bg-red-50 border border-red-200 px-2.5 py-0.5 text-[11px] font-bold text-danger">
                                                    Ditolak
                                                </span>
                                            @endif
                                            <span class="text-ink-soft text-[11px]">{{ $rw->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Dialog Tolak Verifikasi (Sesuai Use Case C Hal 26 Proposal) --}}
                <div id="modal-tolak" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                    <div class="bg-paper rounded-[20px] border border-line p-6 max-w-md w-full shadow-2xl animate-in fade-in zoom-in-95 duration-150">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-display text-base font-bold text-danger">Tolak Verifikasi Advokat</h3>
                            <button type="button" onclick="closeModalTolak()" class="text-ink-soft hover:text-ink">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        <p class="text-xs text-ink-soft mb-3">
                            Sistem mewajibkan Administrator memasukkan alasan penolakan secara jelas agar advokat yang bersangkutan dapat memperbaiki dokumen legalitasnya.
                        </p>
                        <form id="form-tolak-advokat" method="POST" action="">
                            @csrf
                            <div class="mb-4">
                                <label for="alasan_penolakan" class="block text-xs font-bold text-ink mb-1.5">
                                    Catatan / Alasan Penolakan <span class="text-danger">*</span>
                                </label>
                                <textarea id="alasan_penolakan" name="alasan_penolakan" rows="4" required
                                    placeholder="Contoh: Foto Kartu Tanda Anggota (KTA) buram dan nomor BAS tidak sesuai dengan wilayah pengadilan."
                                    class="block w-full rounded-[14px] border border-line bg-ivory px-3.5 py-2.5 text-xs text-ink placeholder-ink-soft/60 focus:border-danger focus:outline-none focus:ring-2 focus:ring-danger/20"></textarea>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <button type="button" onclick="closeModalTolak()" class="rounded-full bg-paper border border-line px-4 py-2 text-xs font-semibold text-ink-soft hover:bg-ivory cursor-pointer">
                                    Batal
                                </button>
                                <button type="submit" class="rounded-full bg-danger px-4 py-2 text-xs font-bold text-white shadow-btn hover:bg-red-800 cursor-pointer">
                                    Kirim Penolakan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function openModalTolak(advId, nama) {
                        const modal = document.getElementById('modal-tolak');
                        const form = document.getElementById('form-tolak-advokat');
                        form.action = "{{ url('/admin/verifikasi-advokat') }}/" + advId + "/tolak";
                        modal.classList.remove('hidden');
                    }
                    function closeModalTolak() {
                        document.getElementById('modal-tolak').classList.add('hidden');
                    }
                </script>

            {{-- ======================================================== --}}
            {{-- TAB 3: VERIFIKASI PRO BONO (FR-04, PBI-09) --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'verifikasi-probono')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-1">Verifikasi Kelayakan Layanan Pro Bono</h2>
                    <p class="text-xs text-ink-soft mb-6">Pemeriksaan dokumen SKTM / pendukung kondisi ekonomi masyarakat untuk akses konsultasi hukum pro bono (cuma-cuma).</p>

                    @if($antreanProBono->isEmpty())
                        <div class="rounded-[14px] border border-line bg-ivory/50 p-8 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-info mb-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.51 48.51 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.583V21" />
                                </svg>
                            </div>
                            <h3 class="font-display text-base font-bold text-ink">Tidak Ada Antrean Pro Bono</h3>
                            <p class="mt-1 text-xs text-ink-soft">Seluruh permohonan status ekonomi masyarakat telah selesai diproses.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($antreanProBono as $pb)
                                <div class="rounded-[14px] border border-line bg-ivory p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <p class="font-bold text-sm text-ink">{{ $pb->nama_user }}</p>
                                        <p class="text-xs text-ink-soft">{{ $pb->email_user }} &bull; {{ $pb->no_hp_user }}</p>
                                        <p class="mt-1 text-xs text-info font-medium">Diajukan: {{ \Carbon\Carbon::parse($pb->created_at)->translatedFormat('d M Y H:i') }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.probono.setujui', $pb->id) }}">
                                            @csrf
                                            <button type="submit" class="rounded-full bg-emerald-700 px-4 py-1.5 text-xs font-bold text-white hover:bg-emerald-800 cursor-pointer">
                                                Setujui Pro Bono
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 4: DAFTAR PENGGUNA PLATFORM (FR-15) --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'manajemen-pengguna')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="font-display text-lg font-bold text-ink">Daftar Pengguna Konsulaw</h2>
                            <p class="text-xs text-ink-soft">Data seluruh akun masyarakat, advokat, dan administrator yang terdaftar.</p>
                        </div>
                        <span class="rounded-full bg-ivory border border-line px-3 py-1 text-xs font-semibold text-ink">
                            Total: {{ $totalUser }} Akun
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b border-line bg-ivory/80 text-ink-soft">
                                <tr>
                                    <th class="py-2.5 px-3 font-semibold">Pengguna</th>
                                    <th class="py-2.5 px-3 font-semibold">Peran</th>
                                    <th class="py-2.5 px-3 font-semibold">Kontak</th>
                                    <th class="py-2.5 px-3 font-semibold">Domisili</th>
                                    <th class="py-2.5 px-3 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-line">
                                @foreach($daftarPengguna as $p)
                                    <tr class="hover:bg-ivory/40">
                                        <td class="py-3 px-3">
                                            <p class="font-bold text-ink">{{ $p->nama ?? $p->name }}</p>
                                            <p class="text-[11px] text-ink-soft">{{ $p->email }}</p>
                                        </td>
                                        <td class="py-3 px-3">
                                            @if($p->role === 'admin')
                                                <span class="rounded-full bg-accent-soft text-accent px-2 py-0.5 font-bold text-[10px]">ADMIN</span>
                                            @elseif($p->role === 'advokat')
                                                <span class="rounded-full bg-amber-100 text-amber-800 px-2 py-0.5 font-bold text-[10px]">ADVOKAT</span>
                                            @else
                                                <span class="rounded-full bg-blue-50 text-blue-700 px-2 py-0.5 font-bold text-[10px]">MASYARAKAT</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-ink-soft">{{ $p->no_hp ?? '—' }}</td>
                                        <td class="py-3 px-3 text-ink-soft">{{ $p->wilayah?->nama ?? '—' }}</td>
                                        <td class="py-3 px-3">
                                            <span class="rounded-full bg-emerald-50 text-success border border-emerald-200 px-2 py-0.5 text-[10px] font-bold">
                                                {{ ucfirst($p->status_akun ?? 'aktif') }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $daftarPengguna->appends(['tab' => 'manajemen-pengguna'])->links() }}
                    </div>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 5: KEAMANAN KATA SANDI --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'keamanan')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-1">Keamanan Akun Administrator</h2>
                    <p class="text-xs text-ink-soft mb-6">Perbarui kata sandi akun administrator secara berkala untuk menjaga keamanan platform.</p>

                    <form method="POST" action="{{ route('admin.profil.password') }}" class="space-y-5 max-w-md">
                        @csrf @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-ink mb-1.5">
                                Kata Sandi Saat Ini <span class="text-danger">*</span>
                            </label>
                            <input id="current_password" name="current_password" type="password" required
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('current_password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-ink mb-1.5">
                                Kata Sandi Baru <span class="text-danger">*</span>
                            </label>
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            <p class="mt-1 text-xs text-ink-soft">Minimal 8 karakter kombinasi huruf dan angka.</p>
                            @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-ink mb-1.5">
                                Konfirmasi Kata Sandi Baru <span class="text-danger">*</span>
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark cursor-pointer">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
