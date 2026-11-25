{{-- Komponen Footer Utama Konsulaw — Soft Editorial (Dark #1A1412) sesuai baselineui.md --}}
<footer class="border-t border-line bg-dark text-white/80 pb-28 md:pb-12 pt-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-12">
            {{-- Kolom 1: Tentang Konsulaw & SDGs 16 --}}
            <div class="lg:col-span-5">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-accent text-white shadow-btn">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0 0 12 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 0 1-2.031.352 5.988 5.988 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971Zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0 2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 0 1-2.031.352 5.989 5.989 0 0 1-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 5.491Z" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-display text-2xl font-bold tracking-tight text-white">Konsu<span class="text-accent">law</span></span>
                    </div>
                </div>

                <p class="mt-4 text-sm leading-6 text-white/70">
                    Konsulaw menghubungkan masyarakat di wilayah pedesaan dan perkotaan dengan advokat profesional yang telah melalui proses verifikasi kualifikasi, dilengkapi akses bantuan hukum cuma-cuma (<em class="italic text-white">pro bono</em>) dan edukasi literasi hukum.
                </p>

                <div class="mt-5 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-1.5 text-xs text-white/90">
                    <span class="h-2 w-2 rounded-full bg-accent"></span>
                    <span>Mendukung <strong>SDGs Tujuan 16</strong>: <em>Peace, Justice, and Strong Institutions</em></span>
                </div>
            </div>

            {{-- Kolom 2: Navigasi Layanan --}}
            <div class="lg:col-span-3">
                <h2 class="font-display text-base font-bold text-white">
                    Layanan Platform
                </h2>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li>
                        <a href="{{ url('/') }}" class="transition hover:text-white">
                            Beranda Utama
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('advokat.index') }}" class="transition hover:text-white">
                            Direktori Advokat
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/pro-bono') }}" class="transition hover:text-white">
                            Verifikasi Pro Bono
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kamus.index') }}" class="transition hover:text-white">
                            Kamus &amp; Regulasi
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#batasan-layanan') }}" class="transition hover:text-white">
                            Batasan Ruang Lingkup Layanan
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Landasan Regulasi --}}
            <div class="lg:col-span-4">
                <h2 class="font-display text-base font-bold text-white">
                    Landasan Regulasi &amp; Privasi
                </h2>
                <ul class="mt-4 space-y-2.5 text-xs leading-5 text-white/70">
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-accent"></span>
                        <span><strong class="text-white">UU No. 16 Tahun 2011</strong> tentang Bantuan Hukum bagi masyarakat kurang mampu.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-accent"></span>
                        <span><strong class="text-white">UU No. 18 Tahun 2003</strong> tentang Advokat sebagai standar verifikasi legalitas.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-accent"></span>
                        <span><strong class="text-white">UU Perlindungan Data Pribadi</strong>: Berkas dan percakapan dilindungi secara privat.</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Catatan Penafian Ringkas di Footer --}}
        <div class="mt-10 rounded-[14px] border border-white/10 bg-white/5 p-4 text-xs leading-relaxed text-white/80">
            <strong class="text-white">Catatan Batasan Layanan:</strong>
            Konsulaw memfasilitasi pencarian advokat terverifikasi, edukasi hukum umum, serta komunikasi konsultasi awal (<em>pra-litigasi</em>). Platform ini bukan lembaga peradilan, tidak menangani proses persidangan di pengadilan, dan konten edukasi di dalamnya tidak menggantikan nasihat hukum profesional atas perkara individual.
        </div>

        <div class="mt-8 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 text-xs text-white/50 sm:flex-row">
            <p>&copy; {{ date('Y') }} Konsulaw — Kelompok A SI-48-09 Proyek Perangkat Lunak.</p>
            <p>Dirancang responsif untuk perangkat komputer maupun ponsel</p>
        </div>
    </div>
</footer>
