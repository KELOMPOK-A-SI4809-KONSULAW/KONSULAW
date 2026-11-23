@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] py-10 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        {{-- Tombol Kembali ke Beranda --}}
        <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-accent hover:text-accent-dark transition">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Beranda Utama</span>
        </a>

        {{-- Kartu Utama Pengajuan Pro Bono Sesuai baselineui.md --}}
        <section class="mt-6 rounded-[20px] border border-line bg-paper p-6 sm:p-10 shadow-card">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full bg-accent-soft px-3 py-1 text-xs font-bold text-accent-dark">
                    Bantuan Hukum Cuma-Cuma (Pro Bono)
                </span>
                <span class="text-xs text-ink-soft">
                    Target SDGs 16.3
                </span>
            </div>

            <h1 class="mt-3 font-display text-2xl sm:text-3xl font-bold tracking-tight text-ink">
                Pengajuan Status Pro Bono
            </h1>

            <p class="mt-2 text-xs sm:text-sm text-ink-soft leading-relaxed">
                Khusus masyarakat kurang mampu di wilayah pedesaan dan perkotaan. Unggah dokumen keterangan ekonomi (seperti SKTM dari desa/kelurahan atau kartu bantuan sosial) agar admin dapat memverifikasi hak akses konsultasi gratis Anda.
            </p>

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div class="mt-6 rounded-[14px] bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-success flex items-start gap-3">
                    <svg class="h-5 w-5 text-success shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Alert Peringatan / Error --}}
            @if (session('error'))
                <div class="mt-6 rounded-[14px] bg-amber-50 border border-warning/20 p-4 text-xs sm:text-sm text-warning flex items-start gap-3">
                    <svg class="h-5 w-5 text-warning shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Alert Kesalahan Validasi (Outline Danger) --}}
            @if ($errors->any())
                <div class="mt-6 rounded-[14px] border border-danger bg-paper p-4 text-xs sm:text-sm text-danger">
                    <p class="font-bold flex items-center gap-1.5">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                        <span>Ada kendala pada pengajuan berkas:</span>
                    </p>
                    <ul class="mt-2 list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Pengingat Jika Belum Masuk / Guest --}}
            @guest
                <div class="mt-6 rounded-[14px] border border-line bg-ivory p-5 text-xs sm:text-sm text-ink">
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-accent text-white shadow-btn">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-ink">
                                Untuk mengirim pengajuan, masyarakat harus login terlebih dahulu.
                            </p>
                            <p class="mt-1 text-xs text-ink-soft">
                                Masuk atau buat akun masyarakat agar status verifikasi pro bono tersimpan aman pada identitas Anda.
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full bg-accent px-4 py-1.5 text-xs font-bold text-white shadow-btn transition hover:bg-accent-dark">
                                    Masuk ke Akun
                                </a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full border border-line bg-paper px-4 py-1.5 text-xs font-semibold text-ink transition hover:border-accent hover:text-accent">
                                    Daftar Akun Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest

            {{-- Tampilan Status Terakhir Bila Sudah Pernah Mengajukan --}}
            @if ($verifikasi && in_array($verifikasi->status, ['pending', 'disetujui'], true))
                <div class="mt-8 rounded-[14px] border border-line bg-ivory p-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-ink-soft">
                        Status Pengajuan Verifikasi Ekonomi
                    </p>

                    <div class="mt-2.5 flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $verifikasi->status === 'pending' ? 'bg-amber-50 text-warning border border-amber-200' : 'bg-emerald-50 text-success border border-emerald-200' }}">
                            @if ($verifikasi->status === 'pending')
                                <svg class="h-3.5 w-3.5 text-warning" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>Sedang Diproses (Pending)</span>
                            @else
                                <svg class="h-3.5 w-3.5 text-success" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span>Telah Disetujui (Pro Bono Aktif)</span>
                            @endif
                        </span>
                    </div>

                    <p class="mt-3 text-xs sm:text-sm text-ink-soft leading-relaxed">
                        {{ $verifikasi->status === 'pending'
                            ? 'Dokumen Anda sedang dalam antrean pemeriksaan oleh Administrator Konsulaw. Notifikasi hasil verifikasi akan muncul otomatis di akun Anda.'
                            : 'Selamat! Akun Anda telah berstatus Pro Bono. Anda dapat mengajukan konsultasi gratis kepada advokat yang memiliki kuota pro bono.' }}
                    </p>

                    @if ($verifikasi->catatan_admin)
                        <div class="mt-4 rounded-[14px] bg-paper p-3.5 border border-line text-xs text-ink-soft">
                            <strong class="text-ink">Catatan Admin:</strong> {{ $verifikasi->catatan_admin }}
                        </div>
                    @endif
                </div>
            @else
                {{-- Formulir Pengunggahan Dokumen SKTM / Bukti Ekonomi --}}
                <form
                    action="{{ route('pro-bono.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-8 space-y-5"
                >
                    @csrf

                    <div>
                        <label for="dokumen" class="block text-xs sm:text-sm font-semibold text-ink">
                            Dokumen pendukung kondisi ekonomi
                        </label>

                        <p class="mt-1 text-xs text-ink-soft leading-relaxed">
                            Contoh: Surat Keterangan Tidak Mampu (SKTM) dari Kelurahan/Desa, Kartu Indonesia Sehat (KIS), Kartu Keluarga Sejahtera (KKS), atau bukti pendapatan.
                        </p>

                        <div class="mt-3">
                            <input
                                id="dokumen"
                                name="dokumen"
                                type="file"
                                accept=".pdf,.jpg,.jpeg,.png"
                                required
                                class="block w-full rounded-[14px] border border-line bg-paper p-3 text-xs sm:text-sm text-ink file:mr-4 file:rounded-full file:border-0 file:bg-accent file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white file:cursor-pointer hover:file:bg-accent-dark focus:border-accent focus:outline-none focus:ring-3 focus:ring-accent-soft transition"
                            >
                        </div>

                        <p class="mt-2 text-[11px] text-ink-soft">
                            Format yang didukung: <strong>PDF, JPG, JPEG, atau PNG</strong>. Ukuran berkas maksimal <strong>5 MB</strong>.
                        </p>
                    </div>

                    {{-- Kotak Jaminan Kerahasiaan Berkas (TR-04 & NFR-05) --}}
                    <div class="rounded-[14px] border border-line bg-ivory p-4 text-xs leading-relaxed text-ink-soft flex items-start gap-3">
                        <svg class="h-4.5 w-4.5 text-accent shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <div>
                            <strong class="text-ink">Jaminan Privasi &amp; Keamanan Berkas (TR-04):</strong>
                            Dokumen yang diunggah disimpan di server privat terenkripsi dan hanya dapat diperiksa oleh admin berwenang. Dokumen tidak akan dipublikasikan ke publik.
                        </div>
                    </div>

                    {{-- Tombol Kirim Pengajuan --}}
                    <div>
                        <button
                            type="submit"
                            class="w-full flex justify-center items-center gap-2 rounded-full bg-accent px-5 py-3.5 text-xs sm:text-sm font-bold text-white shadow-btn transition hover:bg-accent-dark hover:-translate-y-0.5 active:scale-95 cursor-pointer focus:outline-none focus:ring-3 focus:ring-accent-soft"
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
