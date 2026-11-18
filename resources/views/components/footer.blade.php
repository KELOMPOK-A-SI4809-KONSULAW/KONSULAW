{{-- Komponen Footer Utama Konsulaw — Tema Merah, Hitam, Putih (NFR-04 & PBI-08) --}}
{{-- Padding bawah ekstra (pb-24 md:pb-12) agar tidak tertutup Navigasi Bawah pada layar ponsel --}}
<footer class="border-t-4 border-red-800 bg-neutral-950 text-neutral-300 pb-24 md:pb-12">
    <div class="mx-auto max-w-7xl px-4 pt-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-12">
            {{-- Kolom 1: Tentang Konsulaw & SDGs 16 --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-800 text-white">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-white">Konsu<span class="text-red-500">law</span></span>
                        <p class="text-xs text-neutral-400">Platform Bantuan Hukum &amp; Keterbukaan Layanan Publik Kota dan Desa</p>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-neutral-400">
                    Konsulaw menjembatani masyarakat di wilayah pedesaan dan perkotaan dengan advokat profesional yang telah melalui proses verifikasi dokumen kualifikasi (Ijazah S.H., BAS, dan KTA), dilengkapi akses bantuan hukum cuma-cuma (<em class="italic text-white">pro bono</em>) serta edukasi literasi hukum berbahasa sederhana.
                </p>

                <div class="mt-5 inline-flex items-center gap-2 rounded-xl border border-neutral-800 bg-neutral-900 px-3.5 py-2 text-xs text-neutral-200">
                    <span class="h-2 w-2 rounded-full bg-red-600"></span>
                    <span>Mendukung <strong>SDGs Tujuan 16</strong>: <em>Peace, Justice, and Strong Institutions</em> (Target 16.3, 16.6, &amp; 16.10)</span>
                </div>
            </div>

            {{-- Kolom 2: Navigasi Layanan --}}
            <div class="lg:col-span-3">
                <h2 class="text-sm font-bold uppercase tracking-wider text-white">
                    Layanan Platform
                </h2>
                <ul class="mt-4 space-y-2.5 text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="text-neutral-400 transition hover:text-red-400">
                            Beranda Utama
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#direktori-advokat') }}" class="text-neutral-400 transition hover:text-red-400">
                            Direktori Advokat Terverifikasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/pro-bono') }}" class="text-neutral-400 transition hover:text-red-400">
                            Verifikasi Ekonomi &amp; Pro Bono
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#kamus-hukum') }}" class="text-neutral-400 transition hover:text-red-400">
                            Kamus Hukum Populer &amp; Regulasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#batasan-layanan') }}" class="text-neutral-400 transition hover:text-red-400">
                            Batasan Ruang Lingkup Layanan
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Landasan Regulasi & Kepatuhan (NFR-05) --}}
            <div class="lg:col-span-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-white">
                    Landasan Regulasi &amp; Privasi
                </h2>
                <ul class="mt-4 space-y-2.5 text-xs leading-5 text-neutral-400">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-600"></span>
                        <span><strong class="text-white">UU No. 16 Tahun 2011</strong> tentang Bantuan Hukum bagi masyarakat kurang mampu.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-600"></span>
                        <span><strong class="text-white">UU No. 18 Tahun 2003</strong> tentang Advokat sebagai standar verifikasi legalitas profesi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-600"></span>
                        <span><strong class="text-white">UU No. 14 Tahun 2008</strong> tentang Keterbukaan Informasi Publik.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-red-600"></span>
                        <span><strong class="text-white">UU Perlindungan Data Pribadi (NFR-05)</strong>: Dokumen verifikasi dan percakapan disimpan secara privat sesuai hak akses.</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Catatan Penafian Ringkas di Footer (PBI-08) --}}
        <div class="mt-10 rounded-2xl border border-red-900/60 bg-neutral-900 p-4 text-xs leading-relaxed text-neutral-300">
            <strong class="text-red-400">Catatan Batasan Layanan (PBI-08):</strong>
            Konsulaw memfasilitasi pencarian advokat terverifikasi, edukasi hukum umum, serta komunikasi konsultasi awal (<em>pra-litigasi</em>). Platform ini bukan lembaga peradilan, tidak menangani proses persidangan di pengadilan, dan konten edukasi di dalamnya tidak menggantikan nasihat hukum profesional atas perkara individual.
        </div>

        <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-neutral-800 pt-6 text-xs text-neutral-500 sm:flex-row">
            <p>&copy; {{ date('Y') }} Konsulaw — Kelompok A SI-48-09 Proyek Perangkat Lunak. Seluruh hak cipta dilindungi.</p>
            <p>Dirancang responsif untuk perangkat Komputer &amp; Ponsel (NFR-04)</p>
        </div>
    </div>
</footer>
