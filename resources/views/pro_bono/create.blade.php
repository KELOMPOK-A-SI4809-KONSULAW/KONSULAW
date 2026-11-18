@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] bg-neutral-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        {{-- Tombol Kembali ke Beranda --}}
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-red-800 hover:text-neutral-950 transition">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Beranda Utama</span>
        </a>

        {{-- Kartu Utama Pengajuan Pro Bono (Merah Maron & Putih Bersih) --}}
        <section class="mt-6 rounded-3xl border border-neutral-200 bg-white p-6 shadow-md sm:p-10">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-800">
                    Bantuan Hukum Cuma-Cuma (Pro Bono)
                </span>
                <span class="text-xs text-neutral-500">
                    Target SDGs 16.3
                </span>
            </div>

            <h1 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight text-neutral-950">
                Pengajuan Status Pro Bono
            </h1>

            <p class="mt-2 text-sm text-neutral-600 leading-relaxed">
                Khusus masyarakat kurang mampu di wilayah pedesaan dan perkotaan. Unggah dokumen keterangan ekonomi (seperti SKTM dari desa/kelurahan atau kartu bantuan sosial) agar admin dapat memverifikasi hak akses konsultasi gratis Anda.
            </p>

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="mt-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alert Peringatan / Error --}}
            @if (session('error'))
                <div class="mt-6 rounded-2xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-800 flex items-start gap-3">
                    <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi --}}
            @if ($errors->any())
                <div class="mt-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                    <p class="font-bold">Ada kendala pada pengajuan berkas:</p>
                    <ul class="mt-1 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Pengingat Jika Belum Masuk / Guest --}}
            @guest
                <div class="mt-6 rounded-2xl border border-red-200 bg-red-50/60 p-5 text-sm text-neutral-800">
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-red-800 text-white">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-neutral-950">
                                Untuk mengirim pengajuan, masyarakat harus login terlebih dahulu.
                            </p>
                            <p class="mt-1 text-xs text-neutral-600">
                                Masuk atau buat akun masyarakat agar status verifikasi pro bono tersimpan aman pada identitas Anda.
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-red-800 px-4 py-2 text-xs font-bold text-white transition hover:bg-neutral-950">
                                    Masuk ke Akun
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-red-800 bg-white px-4 py-2 text-xs font-bold text-red-800 transition hover:bg-red-50">
                                    Daftar Akun Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest

            {{-- Tampilan Status Terakhir Bila Sudah Pernah Mengajukan --}}
            @if ($verifikasi && in_array($verifikasi->status, ['pending', 'disetujui'], true))
                <div class="mt-8 rounded-2xl border border-neutral-200 bg-neutral-50 p-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-neutral-500">
                        Status Pengajuan Verifikasi Ekonomi
                    </p>

                    <div class="mt-2 flex items-center gap-3">
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-extrabold {{ $verifikasi->status === 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300' }}">
                            {{ $verifikasi->status === 'pending' ? 'Sedang Diproses (Pending)' : 'Telah Disetujui (Pro Bono Aktif)' }}
                        </span>
                    </div>

                    <p class="mt-3 text-sm text-neutral-700">
                        {{ $verifikasi->status === 'pending'
                            ? 'Dokumen Anda sedang dalam antrean pemeriksaan oleh Administrator Konsulaw. Notifikasi hasil verifikasi akan muncul otomatis di akun Anda.'
                            : 'Selamat! Akun Anda telah berstatus Pro Bono. Anda dapat mengajukan konsultasi gratis kepada advokat yang memiliki kuota pro bono.' }}
                    </p>

                    @if ($verifikasi->catatan_admin)
                        <div class="mt-4 rounded-xl bg-white p-3.5 border border-neutral-200 text-xs text-neutral-600">
                            <strong class="text-neutral-900">Catatan Admin:</strong> {{ $verifikasi->catatan_admin }}
                        </div>
                    @endif
                </div>
            @else
                {{-- Formulir Pengunggahan Dokumen SKTM / Bukti Ekonomi --}}
                <form
                    action="{{ route('pro-bono.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-8 space-y-6"
                >
                    @csrf

                    <div>
                        <label for="dokumen" class="block text-sm font-extrabold text-neutral-900">
                            Dokumen pendukung kondisi ekonomi
                        </label>

                        <p class="mt-1 text-xs text-neutral-500 leading-relaxed">
                            Contoh: Surat Keterangan Tidak Mampu (SKTM) dari Kelurahan/Desa, Kartu Indonesia Sehat (KIS), Kartu Keluarga Sejahtera (KKS), atau bukti pendapatan.
                        </p>

                        <div class="mt-3">
                            <input
                                id="dokumen"
                                name="dokumen"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                                class="block w-full rounded-2xl border border-neutral-300 bg-neutral-50 p-3.5 text-sm text-neutral-800 file:mr-4 file:rounded-xl file:border-0 file:bg-red-800 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white file:cursor-pointer hover:file:bg-neutral-950 focus:border-red-700 focus:outline-none"
                            >
                        </div>

                        <p class="mt-2 text-[11px] text-neutral-500">
                            Format yang didukung: <strong>PDF, JPG, JPEG, atau PNG</strong>. Ukuran berkas maksimal <strong>5 MB</strong>.
                        </p>
                    </div>

                    {{-- Kotak Jaminan Kerahasiaan Berkas (TR-04 & NFR-05) --}}
                    <div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 text-xs leading-relaxed text-neutral-600 flex items-start gap-3">
                        <svg class="h-5 w-5 text-red-800 shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <div>
                            <strong class="text-neutral-900">Jaminan Privasi &amp; Keamanan Berkas (TR-04):</strong>
                            Dokumen yang diunggah disimpan di server privat terenkripsi dan hanya dapat diperiksa oleh admin berwenang. Dokumen tidak akan dipublikasikan ke publik.
                        </div>
                    </div>

                    {{-- Tombol Kirim Pengajuan (Merah Maron Dominan) --}}
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center gap-2 rounded-xl bg-red-800 px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-neutral-950 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-800 focus:ring-offset-2"
                        >
                            <span>Ajukan Verifikasi</span>
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                        </button>
                    </div>
                </form>
            @endif
        </section>
    </div>
</div>
@endsection
