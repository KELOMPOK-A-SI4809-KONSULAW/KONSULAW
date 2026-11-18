{{-- Komponen Informasi Batasan Ruang Lingkup Layanan di Beranda — Tema Merah, Hitam, Putih (PBI-08 & Bab 1.3 Proposal) --}}
@php
    $batasanLayanan = $batasanLayanan ?? [
        [
            'nomor' => '01',
            'judul' => 'Cakupan Data & Partisipasi Advokat',
            'ringkasan' => 'Bergantung pada advokat yang terdaftar di platform.',
            'deskripsi' => 'Cakupan informasi dan layanan yang tersedia bergantung pada data serta partisipasi advokat yang terdaftar pada platform Konsulaw. Oleh karena itu, kelengkapan informasi dan jangkauan wilayahnya belum tentu mewakili seluruh advokat maupun layanan hukum di Indonesia.',
        ],
        [
            'nomor' => '02',
            'judul' => 'Mekanisme Verifikasi Kredensial Internal',
            'ringkasan' => 'Pemeriksaan dokumen dilakukan oleh admin sistem.',
            'deskripsi' => 'Proses verifikasi informasi profesional advokat (Ijazah Sarjana Hukum, Berita Acara Sumpah/BAS, dan Kartu Tanda Anggota/KTA) dilakukan berdasarkan mekanisme pemeriksaan dokumen pendukung di dalam sistem. Status verifikasi pada platform tidak secara otomatis menjadi pengesahan resmi dari pemerintah atau organisasi advokat, serta tidak menggantikan pemeriksaan lebih lanjut apabila diperlukan.',
        ],
        [
            'nomor' => '03',
            'judul' => 'Fokus pada Konsultasi Awal (Pra-Litigasi)',
            'ringkasan' => 'Tidak mencakup persidangan atau pengajuan berkas ke pengadilan.',
            'deskripsi' => 'Sistem berperan sebagai sarana komunikasi dua arah antara masyarakat dan advokat untuk penyampaian kebutuhan hukum serta konsultasi awal. Platform tidak mencakup penanganan perkara secara menyeluruh, pelaksanaan proses persidangan, maupun pengajuan dokumen hukum ke pengadilan. Tindak lanjut penanganan perkara berada di luar cakupan sistem sesuai kesepakatan masyarakat dan advokat.',
        ],
        [
            'nomor' => '04',
            'judul' => 'Sifat Edukasi & Informasi Hukum Umum',
            'ringkasan' => 'Bukan pengganti nasihat hukum formal atau putusan perkara.',
            'deskripsi' => 'Informasi pada Kamus Hukum Populer dan Direktori Peraturan ditujukan untuk edukasi serta literasi hukum masyarakat secara umum. Informasi tersebut tidak menggantikan konsultasi profesional dengan advokat, tidak memberikan jaminan atas hasil suatu perkara, dan tidak dimaksudkan sebagai penetapan keputusan hukum terhadap permasalahan individual.',
        ],
        [
            'nomor' => '05',
            'judul' => 'Ketentuan Kuota Layanan Bantuan Pro Bono',
            'ringkasan' => 'Mengikuti ketersediaan kuota bulanan masing-masing advokat.',
            'deskripsi' => 'Informasi mengenai ketersediaan layanan bantuan hukum cuma-cuma (pro bono) bergantung pada kuota yang ditetapkan oleh advokat serta hasil verifikasi status ekonomi masyarakat. Platform tidak menjamin bahwa setiap permintaan bantuan akan diterima atau bahwa layanan pro bono selalu tersedia di seluruh wilayah.',
        ],
    ];
@endphp

<section
    id="batasan-layanan"
    aria-labelledby="judul-batasan-layanan"
    class="scroll-mt-24 border-t border-neutral-200 bg-neutral-100 py-14 sm:py-16"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Header Bagian Batasan Ruang Lingkup Layanan --}}
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-red-300 bg-red-50 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-red-800">
                <svg class="h-4 w-4 text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                Transparansi &amp; Ketentuan Layanan (PBI-08)
            </span>

            <h2 id="judul-batasan-layanan" class="mt-3 text-2xl font-extrabold tracking-tight text-neutral-950 sm:text-3xl">
                Informasi Batasan Ruang Lingkup Layanan Konsulaw
            </h2>

            <p class="mt-3 text-sm leading-6 text-neutral-600 sm:text-base">
                Demi menjaga kejelasan informasi bagi masyarakat pencari keadilan maupun advokat, berikut adalah 5 (lima) batasan ruang lingkup operasional platform <strong>Konsulaw</strong> yang perlu dipahami sebelum menggunakan layanan:
            </p>
        </div>

        {{-- Daftar 5 Poin Batasan Ruang Lingkup Layanan Sesuai Proposal Bab 1.3 --}}
        <div class="mt-10 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($batasanLayanan as $item)
                <article class="flex flex-col justify-between rounded-2xl border border-neutral-200 bg-white p-6 shadow-xs transition hover:border-red-700 hover:shadow-md">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-800 text-sm font-bold text-white">
                                {{ $item['nomor'] }}
                            </span>
                            <span class="rounded-full border border-neutral-300 bg-neutral-50 px-2.5 py-1 text-[11px] font-semibold text-neutral-700">
                                Batasan #{{ $loop->iteration }}
                            </span>
                        </div>

                        <h3 class="mt-4 text-base font-bold text-neutral-950">
                            {{ $item['judul'] }}
                        </h3>

                        <p class="mt-1 text-xs font-semibold text-red-700">
                            {{ $item['ringkasan'] }}
                        </p>

                        <p class="mt-3 text-sm leading-6 text-neutral-600">
                            {{ $item['deskripsi'] }}
                        </p>
                    </div>
                </article>
            @endforeach

            {{-- Kartu ke-6: Ringkasan Perbandingan Cakupan Layanan (Hitam - Merah - Putih) --}}
            <article class="flex flex-col justify-between rounded-2xl border-2 border-red-800 bg-neutral-950 p-6 text-white shadow-xs">
                <div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-700 text-sm font-bold text-white">
                            &#9878;
                        </span>
                        <span class="rounded-full border border-red-700 bg-red-950 px-2.5 py-1 text-[11px] font-semibold text-red-200">
                            Panduan Singkat Warga
                        </span>
                    </div>

                    <h3 class="mt-4 text-base font-bold text-white">
                        Ringkasan Cakupan Platform
                    </h3>

                    <ul class="mt-3 space-y-2.5 text-xs leading-5 text-neutral-200">
                        <li class="flex items-start gap-2">
                            <span class="rounded bg-white px-1.5 py-0.5 text-[10px] font-extrabold text-neutral-950">TERCAKUP</span>
                            <span>Pencarian advokat terverifikasi, pengajuan konsultasi awal (<em>pra-litigasi</em>), verifikasi ekonomi pro bono, dan edukasi literasi hukum.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="rounded bg-red-700 px-1.5 py-0.5 text-[10px] font-extrabold text-white">DI LUAR SISTEM</span>
                            <span>Pendampingan sidang di pengadilan, pendaftaran berkas perkara ke pengadilan, serta jaminan kemenangan perkara.</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-5 border-t border-neutral-800 pt-4">
                    <a
                        href="{{ url('/pro-bono') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-red-400 hover:text-red-300"
                    >
                        <span>Ajukan verifikasi status ekonomi Pro Bono</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>
