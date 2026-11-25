@extends('layouts.app')

@php
    $nama = $user->nama ?? $user->name ?? 'Advokat';
    $parts = preg_split('/\s+/', trim($nama)) ?: ['A', 'D'];
    $inisial = mb_strtoupper(mb_substr($parts[0] ?? 'A', 0, 1) . mb_substr($parts[1] ?? 'D', 0, 1));
    $status  = $profil->status_verifikasi ?? 'belum_diajukan';

    // Tentukan kondisi banner (1-5 sesuai README)
    if ($status === 'belum_diajukan') {
        $kondisi = $persen === 0 ? 1 : ($persen === 100 ? '1-siap' : 2);
    } elseif ($status === 'pending') {
        $kondisi = 3;
    } elseif (in_array($status, ['disetujui', 'terverifikasi'])) {
        $kondisi = 4;
    } else {
        $kondisi = 5; // ditolak
    }

    // Tabs list
    $tabs = [
        'informasi-pribadi'  => 'Informasi Pribadi',
        'data-profesional'   => 'Data Profesional',
        'bidang-wilayah'     => 'Bidang & Wilayah',
        'dokumen-verifikasi' => 'Dokumen Verifikasi',
        'tarif-ketersediaan' => 'Tarif & Ketersediaan',
        'kuota-probono'      => 'Kuota Pro Bono',
        'ulasan'             => 'Ulasan Saya',
        'keamanan-akun'      => 'Keamanan Akun',
    ];

    // Cek progres per komponen untuk checklist
    $checkUser     = !empty($user->no_hp) && !empty($user->wilayah_id);
    $checkProfesi  = !empty($profil->no_kta) && !empty($profil->no_bas) && !empty($profil->pengalaman);
    $checkBidang   = count($selectedBidangIds) > 0 && count($selectedWilayahIds) > 0;
    $dokumenJenis  = collect($dokumenMap)->keys()->toArray();
    $checkDokumen  = in_array('KTA', $dokumenJenis) && in_array('BAS', $dokumenJenis) && in_array('Ijazah', $dokumenJenis);
@endphp

@section('content')
{{-- ============================================================ --}}
{{-- HEADER PROFIL ADVOKAT (selalu tampil di atas) --}}
{{-- ============================================================ --}}
<div class="bg-ivory border-b border-line">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="flex flex-col sm:flex-row sm:items-start gap-5">
            {{-- Avatar / Foto Profil Interaktif --}}
            <div class="shrink-0">
                <div class="relative group">
                    <button type="button" onclick="document.getElementById('avatar-file-input').click()" class="relative block rounded-full focus:outline-none focus:ring-4 focus:ring-accent-soft cursor-pointer transition" title="Klik untuk mengganti foto profil">
                        @if($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $nama }}" class="h-20 w-20 rounded-full object-cover shadow-btn ring-4 ring-accent-soft group-hover:brightness-90 transition">
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
                            <span class="text-[10px] font-bold tracking-tight">Ubah</span>
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
                <form id="avatar-form" action="{{ route('advokat.profil.foto') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" id="avatar-file-input" name="foto_profil" accept="image/png,image/jpeg,image/jpg,image/webp" onchange="document.getElementById('avatar-form').submit()">
                </form>
            </div>

            {{-- Info Utama --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="font-display text-xl sm:text-2xl font-bold text-ink truncate">
                        {{ $nama }}
                    </h1>
                    {{-- Badge Status Verifikasi --}}
                    @if(in_array($status, ['disetujui', 'terverifikasi']))
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-xs font-bold text-success">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                            Terverifikasi
                        </span>
                    @elseif($status === 'pending')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                            <svg class="h-3.5 w-3.5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            Menunggu Verifikasi
                        </span>
                    @elseif($status === 'ditolak')
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 border border-danger px-2.5 py-0.5 text-xs font-bold text-danger">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                            Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-paper border border-line px-2.5 py-0.5 text-xs font-semibold text-ink-soft">
                            Belum Terverifikasi
                        </span>
                    @endif
                </div>

                {{-- Rating & Ulasan --}}
                <div class="mt-1 flex items-center gap-3 text-sm text-ink-soft">
                    @if($jumlahUlasan > 0)
                        <span class="flex items-center gap-1">
                            <svg class="h-4 w-4 text-amber-400 fill-amber-400" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span class="font-semibold text-ink">{{ number_format($ratingAvg, 1) }}</span>
                            <span>({{ $jumlahUlasan }} ulasan)</span>
                        </span>
                    @else
                        <span>Belum ada ulasan</span>
                    @endif

                    @if(!empty($user->wilayah))
                        <span class="flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            {{ $user->wilayah->nama }}
                        </span>
                    @endif
                </div>

                {{-- Progress Bar Kelengkapan --}}
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-semibold text-ink-soft">Kelengkapan Profil</span>
                        <span class="text-xs font-bold {{ $persen === 100 ? 'text-success' : 'text-accent' }}">{{ $persen }}%</span>
                    </div>
                    <div class="h-2 w-full max-w-xs rounded-full bg-line overflow-hidden">
                        <div
                            class="h-full rounded-full transition-all {{ $persen === 100 ? 'bg-success' : 'bg-accent' }}"
                            style="width: {{ $persen }}%"
                        ></div>
                    </div>
                </div>
            </div>

            {{-- Tombol Lihat Tampilan Publik --}}
            <div class="shrink-0">
                @if(in_array($status, ['disetujui', 'terverifikasi']))
                    <a href="{{ route('home') }}" target="_blank"
                       class="inline-flex items-center gap-2 rounded-full border border-line bg-paper px-4 py-2 text-xs font-semibold text-ink transition hover:border-accent hover:text-accent">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        Lihat Tampilan Publik
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 rounded-full border border-line bg-paper px-4 py-2 text-xs font-semibold text-ink-soft cursor-not-allowed opacity-60">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        Pratinjau (belum tayang)
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- BANNER STATUS VERIFIKASI (5 kondisi sesuai README 2.1) --}}
{{-- ============================================================ --}}
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 pt-6">

    {{-- Flash message sukses / error --}}
    @if(session('success'))
        <div class="mb-4 rounded-[14px] border border-emerald-200 bg-emerald-50 p-4 flex items-start gap-3 text-sm text-success">
            <svg class="h-5 w-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-[14px] border border-danger bg-red-50 p-4 flex items-start gap-3 text-sm text-danger">
            <svg class="h-5 w-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Kondisi 1: Baru Daftar (belum_diajukan + 0%) --}}
    @if($kondisi == 1)
        <div class="mb-6 rounded-[14px] border border-info bg-blue-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-info text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-info">Selamat datang, {{ $nama }}!</p>
                    <p class="mt-0.5 text-sm text-info/80">Profil Anda belum tampil di direktori. Selesaikan 4 langkah berikut untuk diverifikasi.</p>

                    {{-- Checklist Onboarding --}}
                    <div class="mt-4 space-y-2.5">
                        @foreach([
                            ['tab' => 'informasi-pribadi',  'label' => 'Lengkapi informasi pribadi (nama, no. HP, domisili)', 'done' => $checkUser],
                            ['tab' => 'data-profesional',   'label' => 'Isi data profesional (No. KTA, No. BAS, pengalaman)',  'done' => $checkProfesi],
                            ['tab' => 'bidang-wilayah',     'label' => 'Pilih bidang keahlian dan wilayah praktik',           'done' => $checkBidang],
                            ['tab' => 'dokumen-verifikasi', 'label' => 'Unggah Ijazah S.H., BAS, dan KTA, lalu kirim verifikasi', 'done' => $checkDokumen],
                        ] as $i => $step)
                            <a href="{{ route('advokat.profil.edit', ['tab' => $step['tab']]) }}"
                               class="flex items-center gap-3 rounded-[10px] border {{ $step['done'] ? 'border-emerald-200 bg-emerald-50/60' : 'border-info/30 bg-white hover:border-info' }} px-4 py-2.5 transition group">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-bold {{ $step['done'] ? 'bg-success text-white' : 'bg-info/10 text-info' }}">
                                    {{ $step['done'] ? '✓' : ($i + 1) }}
                                </span>
                                <span class="text-sm font-medium {{ $step['done'] ? 'text-success line-through' : 'text-ink group-hover:text-info' }}">
                                    {{ $step['label'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('advokat.profil.edit', ['tab' => 'informasi-pribadi']) }}"
                       class="mt-4 inline-flex items-center gap-2 rounded-full bg-info px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Mulai Lengkapi Profil →
                    </a>
                </div>
            </div>
        </div>

    {{-- Kondisi 2: Belum Lengkap (belum_diajukan + 1-99%) --}}
    @elseif($kondisi == 2)
        <div class="mb-6 rounded-[14px] border border-amber-300 bg-amber-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-warning text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" /></svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-warning">Profil baru {{ $persen }}% lengkap. Lanjutkan untuk bisa mengirim verifikasi.</p>
                    <div class="mt-2 h-2 w-full max-w-sm rounded-full bg-amber-200">
                        <div class="h-full rounded-full bg-warning transition-all" style="width: {{ $persen }}%"></div>
                    </div>
                    <p class="mt-1.5 text-xs text-warning/80">Bagian yang masih perlu dilengkapi:</p>
                    <ul class="mt-1 space-y-1 text-xs text-warning">
                        @unless($checkUser)    <li class="flex items-center gap-1.5"><span class="inline-block w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span> Informasi pribadi (no. HP dan domisili)</li> @endunless
                        @unless($checkProfesi) <li class="flex items-center gap-1.5"><span class="inline-block w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span> Data profesional (No. KTA, No. BAS, pengalaman)</li> @endunless
                        @unless($checkBidang)  <li class="flex items-center gap-1.5"><span class="inline-block w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span> Bidang keahlian dan wilayah praktik</li> @endunless
                        @unless($checkDokumen) <li class="flex items-center gap-1.5"><span class="inline-block w-1.5 h-1.5 rounded-full bg-warning shrink-0"></span> Dokumen verifikasi (Ijazah, BAS, KTA)</li> @endunless
                    </ul>
                    <a href="{{ route('advokat.profil.edit', ['tab' => !$checkUser ? 'informasi-pribadi' : (!$checkProfesi ? 'data-profesional' : (!$checkBidang ? 'bidang-wilayah' : 'dokumen-verifikasi'))]) }}"
                       class="mt-3 inline-flex items-center gap-2 rounded-full bg-warning px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
                        Lanjutkan Mengisi →
                    </a>
                </div>
            </div>
        </div>

    {{-- Kondisi 1-siap: Belum_diajukan + 100% = siap kirim --}}
    @elseif($kondisi === '1-siap')
        <div class="mb-6 rounded-[14px] border border-success bg-emerald-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-success text-white">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-success">Profil 100% lengkap — siap dikirim untuk verifikasi!</p>
                    <p class="mt-0.5 text-sm text-success/80">Setelah dikirim, admin akan memeriksa dokumen Anda. Proses biasanya 1-3 hari kerja.</p>
                    <form action="{{ route('advokat.profil.kirim-verifikasi') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-full bg-success px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
                            Kirim untuk Verifikasi →
                        </button>
                    </form>
                </div>
            </div>
        </div>

    {{-- Kondisi 3: Pending --}}
    @elseif($kondisi == 3)
        <div class="mb-6 rounded-[14px] border border-amber-300 bg-amber-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-amber-500 text-white shadow-sm">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-amber-900">Dokumen sedang diperiksa admin.</p>
                    <p class="mt-0.5 text-sm text-amber-800/85 leading-relaxed">Notifikasi hasil verifikasi akan muncul otomatis. Anda tetap dapat memperbarui foto profil dan biografi selama proses berlangsung.</p>
                </div>
            </div>
        </div>

    {{-- Kondisi 4: Terverifikasi --}}
    @elseif($kondisi == 4)
        <div class="mb-6 rounded-[14px] border border-emerald-200 bg-emerald-50 p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-success shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                <p class="text-sm font-medium text-success">Profil Anda aktif dan tampil di direktori Konsulaw. Perubahan KTA/BAS akan memicu verifikasi ulang.</p>
            </div>
        </div>

    {{-- Kondisi 5: Ditolak --}}
    @elseif($kondisi == 5)
        <div class="mb-6 rounded-[14px] border border-danger bg-red-50 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-danger text-white">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-danger">Verifikasi ditolak.</p>
                    @if($profil->catatan_verifikasi)
                        <div class="mt-2 rounded-[10px] bg-white border border-danger/30 px-3 py-2 text-sm text-ink">
                            <span class="font-semibold text-danger">Catatan Admin:</span> {{ $profil->catatan_verifikasi }}
                        </div>
                    @endif
                    <a href="{{ route('advokat.profil.edit', ['tab' => 'dokumen-verifikasi']) }}"
                       class="mt-3 inline-flex items-center gap-2 rounded-full bg-danger px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:opacity-90">
                        Perbaiki &amp; Kirim Ulang →
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- ============================================================ --}}
{{-- LAYOUT UTAMA: Tab Nav (kiri) + Konten Tab (kanan) --}}
{{-- ============================================================ --}}
<div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8 pb-24">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- ---- SIDEBAR NAVIGASI TAB (desktop kiri / mobile scroll horisontal) ---- --}}
        <aside class="lg:w-56 shrink-0">
            {{-- Mobile: scroll horizontal --}}
            <div class="lg:hidden flex gap-2 overflow-x-auto pb-2 scrollbar-none">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('advokat.profil.edit', ['tab' => $key]) }}"
                       class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition
                           {{ $tabAktif === $key ? 'bg-accent text-white shadow-btn' : 'bg-paper border border-line text-ink-soft hover:border-accent hover:text-accent' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Desktop: stacked nav --}}
            <nav class="hidden lg:flex flex-col gap-1 sticky top-20">
                @foreach($tabs as $key => $label)
                    <a href="{{ route('advokat.profil.edit', ['tab' => $key]) }}"
                       class="flex items-center gap-2.5 rounded-[10px] px-3.5 py-2.5 text-sm font-semibold transition
                           {{ $tabAktif === $key ? 'bg-accent-soft text-accent' : 'text-ink-soft hover:text-accent hover:bg-accent-soft/50' }}">
                        @if($tabAktif === $key)
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-accent shrink-0"></span>
                        @else
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-transparent shrink-0"></span>
                        @endif
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- ---- KONTEN AREA ---- --}}
        <div class="flex-1 min-w-0">

            {{-- Tampilkan error validasi --}}
            @if(isset($errors) && $errors->any())
                <div class="mb-5 rounded-[14px] border border-danger bg-red-50 p-4 text-sm text-danger">
                    <p class="font-bold mb-1">Mohon perbaiki data berikut:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            {{-- ======================================================== --}}
            {{-- TAB 1: INFORMASI PRIBADI --}}
            {{-- ======================================================== --}}
            @if($tabAktif === 'informasi-pribadi')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Informasi Pribadi</h2>

                    {{-- Card Foto Profil --}}
                    <div class="rounded-[14px] border border-line bg-ivory/60 p-4 mb-6">
                        <label class="block text-sm font-semibold text-ink mb-2">Foto Profil Resmi</label>
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
                                        <form action="{{ route('advokat.profil.hapus-foto') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus foto profil ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-full bg-red-50 border border-red-200 px-3.5 py-2 text-xs font-semibold text-danger hover:bg-red-100 transition cursor-pointer">
                                                Hapus Foto
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="mt-1.5 text-xs text-ink-soft">Foto resmi untuk direktori dan konsultasi. Format: JPG, PNG, atau WebP (maks. 2 MB).</p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('advokat.profil.info-pribadi') }}" enctype="multipart/form-data" class="space-y-5">
                        @csrf @method('PUT')

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-ink mb-1.5">
                                Nama Lengkap (beserta gelar) <span class="text-danger">*</span>
                            </label>
                            <input id="nama" name="nama" type="text"
                                value="{{ old('nama', $user->nama ?? $user->name) }}"
                                placeholder="Contoh: Bambang Prakoso, S.H."
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('nama') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email (read-only) --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink mb-1.5">Alamat Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="block w-full rounded-[14px] border border-line bg-line/40 px-4 py-3 text-sm text-ink-soft cursor-not-allowed">
                            <p class="mt-1 text-xs text-ink-soft">Email tidak dapat diubah dari halaman ini.</p>
                        </div>

                        {{-- No. HP --}}
                        <div>
                            <label for="no_hp" class="block text-sm font-semibold text-ink mb-1.5">
                                Nomor HP / WhatsApp Aktif <span class="text-danger">*</span>
                            </label>
                            <input id="no_hp" name="no_hp" type="tel"
                                value="{{ old('no_hp', $user->no_hp) }}"
                                placeholder="08xx-xxxx-xxxx atau +62xxx"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('no_hp') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Wilayah Domisili --}}
                        <div>
                            <label for="wilayah_id" class="block text-sm font-semibold text-ink mb-1.5">
                                Domisili (Provinsi / Kabupaten / Kota) <span class="text-danger">*</span>
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
                            @error('wilayah_id') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                Simpan Informasi Pribadi
                            </button>
                        </div>
                    </form>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 2: DATA PROFESIONAL --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'data-profesional')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Data Profesional</h2>
                    <form method="POST" action="{{ route('advokat.profil.data-profesional') }}" class="space-y-5">
                        @csrf @method('PUT')

                        {{-- No. KTA --}}
                        <div>
                            <label for="no_kta" class="block text-sm font-semibold text-ink mb-1.5">
                                No. KTA (Kartu Tanda Anggota) <span class="text-danger">*</span>
                            </label>
                            <input id="no_kta" name="no_kta" type="text"
                                value="{{ old('no_kta', $profil->no_kta) }}"
                                placeholder="Contoh: KTA-PERADI-2024-0891"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('no_kta') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- No. BAS --}}
                        <div>
                            <label for="no_bas" class="block text-sm font-semibold text-ink mb-1.5">
                                No. BAS (Berita Acara Sumpah) <span class="text-danger">*</span>
                            </label>
                            <input id="no_bas" name="no_bas" type="text"
                                value="{{ old('no_bas', $profil->no_bas) }}"
                                placeholder="Contoh: BAS-PT-BDG-2019-0412"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('no_bas') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Pengalaman (Tahun) --}}
                        <div>
                            <label for="pengalaman" class="block text-sm font-semibold text-ink mb-1.5">
                                Pengalaman (tahun) <span class="text-danger">*</span>
                            </label>
                            <input id="pengalaman" name="pengalaman" type="number" min="0" max="50"
                                value="{{ old('pengalaman', (int) filter_var($profil->pengalaman ?? 0, FILTER_SANITIZE_NUMBER_INT)) }}"
                                placeholder="Contoh: 5"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            <p class="mt-1 text-xs text-ink-soft">Jumlah tahun berpraktik sebagai advokat.</p>
                            @error('pengalaman') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Bio / Tentang Saya --}}
                        <div>
                            <label for="biografi" class="block text-sm font-semibold text-ink mb-1.5">
                                Bio Singkat / Tentang Saya
                                <span class="text-xs font-normal text-ink-soft">(opsional, maks 500 karakter)</span>
                            </label>
                            <textarea id="biografi" name="biografi" rows="4" maxlength="500"
                                placeholder="Ceritakan spesialisasi, pendekatan, dan rekam jejak kasus Anda..."
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">{{ old('biografi', $profil->biografi) }}</textarea>
                            <p class="mt-1 text-xs text-ink-soft">Tampil di kartu profil publik Anda.</p>
                            @error('biografi') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Nama Organisasi / Kantor --}}
                        <div>
                            <label for="nama_organisasi" class="block text-sm font-semibold text-ink mb-1.5">
                                Nama Organisasi Advokat / Kantor Hukum
                                <span class="text-xs font-normal text-ink-soft">(opsional)</span>
                            </label>
                            <input id="nama_organisasi" name="nama_organisasi" type="text"
                                value="{{ old('nama_organisasi', $profil->nama_organisasi) }}"
                                placeholder="Contoh: PERADI / Kantor Hukum Prakoso & Partners"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                Simpan Data Profesional
                            </button>
                        </div>
                    </form>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 3: BIDANG & WILAYAH --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'bidang-wilayah')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Bidang Keahlian &amp; Wilayah Praktik</h2>
                    <form method="POST" action="{{ route('advokat.profil.bidang-wilayah') }}" class="space-y-6">
                        @csrf @method('PUT')

                        {{-- Bidang Keahlian --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink mb-1">
                                Bidang Keahlian Hukum <span class="text-danger">*</span>
                            </label>
                            <p class="text-xs text-ink-soft mb-3">Pilih minimal satu bidang. Dipakai untuk filter di halaman Cari Advokat.</p>

                            @if($daftarBidang->isEmpty())
                                <p class="text-sm text-ink-soft italic">Belum ada bidang keahlian. Pilih minimal satu agar masyarakat dapat menemukan Anda.</p>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    @foreach($daftarBidang as $bidang)
                                        <label class="relative flex items-center gap-3 p-3.5 rounded-[12px] border-2 cursor-pointer transition
                                            {{ in_array($bidang->id, old('bidang_hukum_ids', $selectedBidangIds)) ? 'border-accent bg-accent-soft/40' : 'border-line bg-ivory hover:border-accent/50' }}">
                                            <input type="checkbox" name="bidang_hukum_ids[]" value="{{ $bidang->id }}"
                                                class="h-4 w-4 rounded border-line text-accent focus:ring-accent"
                                                {{ in_array($bidang->id, old('bidang_hukum_ids', $selectedBidangIds)) ? 'checked' : '' }}>
                                            <span class="text-sm font-medium text-ink">{{ $bidang->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                            @error('bidang_hukum_ids') <p class="mt-2 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Wilayah Praktik --}}
                        <div>
                            <label class="block text-sm font-semibold text-ink mb-1">
                                Wilayah Praktik <span class="text-danger">*</span>
                            </label>
                            <p class="text-xs text-ink-soft mb-3">Pilih minimal satu wilayah (kota/kabupaten). Digunakan untuk filter "Semua Wilayah" di Cari Advokat.</p>
                            <div class="max-h-64 overflow-y-auto rounded-[12px] border border-line bg-ivory/50 p-3 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($daftarWilayah as $w)
                                    <label class="flex items-center gap-2.5 p-2.5 rounded-[10px] bg-paper border border-line cursor-pointer hover:border-accent/50 transition
                                        {{ in_array($w->id, old('wilayah_ids', $selectedWilayahIds)) ? 'border-accent' : '' }}">
                                        <input type="checkbox" name="wilayah_ids[]" value="{{ $w->id }}"
                                            class="h-4 w-4 rounded border-line text-accent focus:ring-accent"
                                            {{ in_array($w->id, old('wilayah_ids', $selectedWilayahIds)) ? 'checked' : '' }}>
                                        <span class="text-xs font-medium text-ink leading-snug">
                                            {{ $w->nama }}
                                            <span class="text-ink-soft font-normal">({{ ucwords(str_replace('_', ' ', $w->tingkat)) }})</span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('wilayah_ids') <p class="mt-2 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                Simpan Bidang &amp; Wilayah
                            </button>
                        </div>
                    </form>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 4: DOKUMEN VERIFIKASI --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'dokumen-verifikasi')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-2">Dokumen Verifikasi</h2>
                    <div class="mb-5 flex items-start gap-2 rounded-[10px] border border-line bg-ivory/80 px-3.5 py-3 text-xs text-ink-soft">
                        <svg class="h-4 w-4 shrink-0 mt-0.5 text-info" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                        <span>Dokumen disimpan di server privat terenkripsi dan hanya dapat ditinjau oleh tim verifikator admin untuk validasi kredensial. Berkas tidak akan ditampilkan ke publik.</span>
                    </div>

                    @php
                        $docsConfig = [
                            'Ijazah' => ['label' => 'Ijazah Sarjana Hukum (S.H.)', 'desc' => 'PDF/JPG/PNG, maks 5 MB'],
                            'BAS'    => ['label' => 'Berita Acara Sumpah (BAS)', 'desc' => 'PDF/JPG/PNG, maks 5 MB'],
                            'KTA'    => ['label' => 'Kartu Tanda Anggota (KTA)', 'desc' => 'PDF/JPG/PNG, maks 5 MB'],
                        ];
                        $isPending = in_array($status, ['pending']);
                    @endphp

                    <div class="space-y-4">
                        @foreach($docsConfig as $jenis => $cfg)
                            @php $dok = $dokumenMap[$jenis] ?? null; @endphp
                            <div class="rounded-[14px] border border-line bg-ivory p-5">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-sm text-ink">{{ $cfg['label'] }}</p>
                                        @if($dok)
                                            <div class="mt-1 flex items-center gap-2">
                                                @if($dok->status === 'valid')
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-success">
                                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                                                        Disetujui
                                                    </span>
                                                @elseif($dok->status === 'tidak_valid')
                                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-danger">
                                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                                                        Ditolak
                                                    </span>
                                                @else
                                                    <span class="text-xs font-semibold text-warning">⏳ Menunggu pemeriksaan</span>
                                                @endif
                                                <span class="text-xs text-ink-soft">· {{ basename($dok->file) }}</span>
                                            </div>
                                            @if($dok->catatan_admin)
                                                <p class="mt-1 text-xs text-danger">Catatan Admin: {{ $dok->catatan_admin }}</p>
                                            @endif
                                            <p class="mt-0.5 text-xs text-ink-soft">Diunggah: {{ $dok->updated_at->translatedFormat('d M Y, H:i') }}</p>
                                        @else
                                            <p class="mt-1 text-xs text-ink-soft italic">Belum diunggah — {{ $cfg['desc'] }}</p>
                                        @endif
                                    </div>

                                    {{-- Upload form per dokumen —- Hanya aktif jika bukan Pending --}}
                                    @if(!$isPending || $dok?->status === 'tidak_valid')
                                        <form method="POST" action="{{ route('advokat.profil.upload-dokumen') }}" enctype="multipart/form-data"
                                              class="shrink-0 flex items-end gap-2">
                                            @csrf
                                            <input type="hidden" name="jenis" value="{{ $jenis }}">
                                            <div>
                                                <label class="block text-xs font-semibold text-ink-soft mb-1">
                                                    {{ $dok ? 'Ganti File' : 'Pilih File' }}
                                                </label>
                                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                                                    class="block text-xs text-ink-soft file:mr-3 file:rounded-full file:border-0 file:bg-accent-soft file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-accent hover:file:bg-accent hover:file:text-white file:cursor-pointer file:transition">
                                            </div>
                                            <button type="submit"
                                                class="rounded-full bg-accent px-3 py-1.5 text-xs font-bold text-white shadow-btn transition hover:bg-accent-dark whitespace-nowrap">
                                                Unggah
                                            </button>
                                        </form>
                                    @elseif($isPending)
                                        <span class="text-xs text-ink-soft italic shrink-0">Dikunci saat Pending</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Kirim untuk Verifikasi --}}
                    @if(in_array($status, ['belum_diajukan', 'ditolak']))
                        <div class="mt-6 pt-5 border-t border-line">
                            @if($profil->siapDikirim())
                                <form action="{{ route('advokat.profil.kirim-verifikasi') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full sm:w-auto rounded-full bg-success px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:opacity-90">
                                        ✓ Kirim untuk Verifikasi
                                    </button>
                                    <p class="mt-2 text-xs text-ink-soft">Proses verifikasi 1-3 hari kerja. Anda akan menerima notifikasi hasilnya.</p>
                                </form>
                            @else
                                <button type="button" disabled
                                    class="w-full sm:w-auto rounded-full bg-line px-6 py-3 text-sm font-bold text-ink-soft cursor-not-allowed opacity-70">
                                    Kirim untuk Verifikasi (Lengkapi data &amp; dokumen terlebih dahulu)
                                </button>
                                <p class="mt-2 text-xs text-ink-soft">Kelengkapan saat ini: {{ $persen }}%. Butuh 100% untuk mengirim.</p>
                            @endif
                        </div>
                    @endif
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 5: TARIF & KETERSEDIAAN --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'tarif-ketersediaan')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Tarif &amp; Ketersediaan</h2>
                    <form method="POST" action="{{ route('advokat.profil.tarif-ketersediaan') }}" class="space-y-6">
                        @csrf @method('PUT')

                        {{-- Tarif --}}
                        <div>
                            <label for="tarif_konsultasi" class="block text-sm font-semibold text-ink mb-1.5">
                                Tarif Konsultasi Reguler (Rp / sesi) <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-semibold text-ink-soft pointer-events-none">Rp</span>
                                <input id="tarif_konsultasi" name="tarif_konsultasi" type="number" min="0" step="1000"
                                    value="{{ old('tarif_konsultasi', (int)($profil->tarif_konsultasi ?? 0)) }}"
                                    placeholder="150000"
                                    class="block w-full rounded-[14px] border border-line bg-ivory pl-10 pr-4 py-3 text-sm font-semibold text-ink placeholder-ink-soft/60 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            </div>
                            <p class="mt-1 text-xs text-ink-soft">Tampil di kartu profil publik sebagai "Rp {{ number_format($profil->tarif_konsultasi ?? 0, 0, ',', '.') }} / sesi".</p>
                            @error('tarif_konsultasi') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        {{-- Toggle Ketersediaan --}}
                        <div class="p-5 rounded-[14px] border-2 {{ $profil->ketersediaan ? 'border-emerald-200 bg-emerald-50/40' : 'border-line bg-ivory' }} transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-sm text-ink">Ketersediaan Menerima Konsultasi Baru</p>
                                    <p class="mt-0.5 text-xs text-ink-soft leading-relaxed">
                                        Jika dimatikan, profil tetap tampil di direktori dengan label <strong>"Sedang tidak menerima"</strong>, tetapi tidak bisa diajukan konsultasi baru.
                                    </p>
                                </div>
                                <div class="shrink-0">
                                    <input type="hidden" name="ketersediaan" value="0">
                                    <label class="relative inline-flex items-center cursor-pointer select-none">
                                        <input type="checkbox" name="ketersediaan" id="toggle-ketersediaan" value="1"
                                            class="sr-only peer"
                                            {{ old('ketersediaan', $profil->ketersediaan) ? 'checked' : '' }}>
                                        <div class="w-14 h-7 bg-line rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[4px] after:bg-white after:border-line after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-success"></div>
                                        <span id="label-ketersediaan" class="ml-3 text-xs font-semibold {{ $profil->ketersediaan ? 'text-success' : 'text-ink-soft' }}">
                                            {{ $profil->ketersediaan ? 'Aktif Menerima' : 'Tidak Menerima' }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                Simpan Tarif &amp; Ketersediaan
                            </button>
                        </div>
                    </form>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 6: KUOTA PRO BONO --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'kuota-probono')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Kuota Pro Bono</h2>

                    @if(!in_array($status, ['disetujui', 'terverifikasi']))
                        <div class="mb-5 rounded-[12px] border border-line bg-ivory/80 p-4 text-sm text-ink-soft">
                            Atur kuota setelah profil Anda terverifikasi. Field dapat diisi sebagai persiapan.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('advokat.profil.kuota') }}" class="space-y-5">
                        @csrf @method('PUT')

                        <div>
                            <label for="kuota_probono" class="block text-sm font-semibold text-ink mb-1.5">
                                Kuota Pro Bono per Bulan <span class="text-danger">*</span>
                            </label>
                            <input id="kuota_probono" name="kuota_probono" type="number" min="0" max="10"
                                value="{{ old('kuota_probono', $profil->kuota_probono ?? 0) }}"
                                class="block w-32 rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm font-bold text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            <p class="mt-1 text-xs text-ink-soft">Nilai 0 = tidak menyediakan pro bono. Maks 10 kasus/bulan.</p>
                            @error('kuota_probono') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div class="rounded-[12px] border border-line bg-ivory/60 p-4">
                            <p class="text-xs font-semibold text-ink-soft uppercase tracking-wider">Sisa Kuota Bulan Ini</p>
                            <p class="mt-1 text-2xl font-black text-accent">{{ $profil->kuota_probono ?? 0 }}
                                <span class="text-sm font-semibold text-ink-soft">kasus</span>
                            </p>
                            <p class="mt-1 text-xs text-ink-soft">Kuota berkurang saat Anda <strong>menerima</strong> permintaan pro bono. Kuota direset tiap awal bulan.</p>
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                Simpan Kuota Pro Bono
                            </button>
                        </div>
                    </form>
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 7: ULASAN SAYA (read-only) --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'ulasan')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Ulasan Saya</h2>
                    @if($profil->ulasan->isEmpty())
                        <div class="rounded-[12px] border border-line bg-ivory/60 p-10 text-center text-sm text-ink-soft">
                            <svg class="h-10 w-10 mx-auto mb-3 text-line" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" /></svg>
                            <p class="font-semibold text-ink">Belum ada ulasan</p>
                            <p class="mt-1 text-xs">Ulasan dari klien akan muncul di sini setelah konsultasi selesai.</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($profil->ulasan->sortByDesc('created_at') as $ulasan)
                                <div class="rounded-[14px] border border-line bg-ivory p-5">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-1">
                                            @for($i=1; $i<=5; $i++)
                                                <svg class="h-4 w-4 {{ $i <= $ulasan->rating ? 'text-amber-400 fill-amber-400' : 'text-line fill-line' }}" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            @endfor
                                            <span class="ml-1.5 text-xs font-bold text-ink">{{ $ulasan->rating }}/5</span>
                                        </div>
                                        <span class="text-xs text-ink-soft">{{ $ulasan->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($ulasan->komentar)
                                        <p class="text-sm text-ink leading-relaxed">{{ $ulasan->komentar }}</p>
                                    @endif
                                    @if($ulasan->masyarakat)
                                        <p class="mt-2 text-xs text-ink-soft">— Klien dari {{ $ulasan->masyarakat->wilayah?->nama ?? 'lokasi tidak diketahui' }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            {{-- ======================================================== --}}
            {{-- TAB 8: KEAMANAN AKUN --}}
            {{-- ======================================================== --}}
            @elseif($tabAktif === 'keamanan-akun')
                <div class="rounded-[20px] border border-line bg-paper p-6 sm:p-8 shadow-card">
                    <h2 class="font-display text-lg font-bold text-ink mb-5">Keamanan Akun</h2>
                    <form method="POST" action="{{ route('advokat.profil.password') }}" class="space-y-5">
                        @csrf @method('PUT')

                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-ink mb-1.5">
                                Kata Sandi Saat Ini <span class="text-danger">*</span>
                            </label>
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('current_password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-ink mb-1.5">
                                Kata Sandi Baru <span class="text-danger">*</span>
                            </label>
                            <input id="password" name="password" type="password" autocomplete="new-password"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            <p class="mt-1 text-xs text-ink-soft">Minimal 8 karakter.</p>
                            @error('password') <p class="mt-1 text-xs text-danger">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-ink mb-1.5">
                                Konfirmasi Kata Sandi Baru <span class="text-danger">*</span>
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                                class="block w-full rounded-[14px] border border-line bg-ivory px-4 py-3 text-sm text-ink focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                        </div>

                        <div class="pt-2 flex items-center gap-4">
                            <button type="submit"
                                class="rounded-full bg-accent px-6 py-2.5 text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>

                    {{-- Logout --}}
                    <div class="mt-8 pt-6 border-t border-line">
                        <h3 class="font-semibold text-sm text-ink mb-2">Keluar dari Akun</h3>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="rounded-full border border-danger px-5 py-2 text-sm font-semibold text-danger transition hover:bg-danger hover:text-white">
                                Keluar dari Semua Perangkat
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>{{-- /.flex-1 konten --}}
    </div>{{-- /.flex container --}}
</div>

{{-- Script interaktif untuk toggle label --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('toggle-ketersediaan');
        const label  = document.getElementById('label-ketersediaan');
        if (toggle && label) {
            toggle.addEventListener('change', function () {
                if (this.checked) {
                    label.textContent = 'Aktif Menerima';
                    label.className = 'ml-3 text-xs font-semibold text-success';
                } else {
                    label.textContent = 'Tidak Menerima';
                    label.className = 'ml-3 text-xs font-semibold text-ink-soft';
                }
            });
        }
    });
</script>
@endsection
