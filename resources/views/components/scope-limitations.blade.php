{{-- Komponen Informasi Batasan Ruang Lingkup Layanan — Soft Editorial sesuai baselineui.md (PBI-08) --}}
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
    class="scroll-mt-24 py-14 sm:py-18 border-t border-line"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Header Bagian Batasan Ruang Lingkup Layanan --}}
        <div class="mx-auto max-w-2xl text-center">
            <span class="inline-flex items-center gap-1.5 rounded-full border border-line bg-paper px-3.5 py-1 text-xs font-semibold uppercase tracking-wider text-ink-soft">
                Transparansi &amp; Ketentuan Layanan
            </span>

            <h2 id="judul-batasan-layanan" class="mt-4 font-display text-2xl font-bold tracking-tight text-ink sm:text-3xl">
                Informasi Batasan Ruang Lingkup Layanan Konsulaw
            </h2>

            <p class="mt-2.5 text-sm text-ink-soft leading-relaxed">
                Demi menjaga kejelasan informasi bagi masyarakat pencari keadilan maupun advokat, berikut adalah 5 batasan ruang lingkup operasional platform <strong>Konsulaw</strong>:
            </p>
        </div>

        {{-- Daftar 5 Poin Batasan Ruang Lingkup Layanan Sesuai Proposal Bab 1.3 --}}
        <div class="mt-10 grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($batasanLayanan as $item)
                <article class="flex flex-col justify-between rounded-[20px] border border-line bg-paper p-6 shadow-card transition hover:border-accent">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-display text-2xl font-bold text-accent">
                                {{ $item['nomor'] }}
                            </span>
                            <span class="rounded-full border border-line bg-ivory px-2.5 py-0.5 text-[11px] font-semibold text-ink-soft">
                                Batasan #{{ $loop->iteration }}
                            </span>
                        </div>

                        <h3 class="mt-3 font-display text-base font-bold text-ink">
                            {{ $item['judul'] }}
                        </h3>

                        <p class="mt-1 text-xs font-semibold text-accent">
                            {{ $item['ringkasan'] }}
                        </p>

                        <p class="mt-3 text-xs leading-relaxed text-ink-soft">
                            {{ $item['deskripsi'] }}
                        </p>
                    </div>
                </article>
            @endforeach

            {{-- Kartu ke-6: Ringkasan Perbandingan Cakupan Layanan --}}
            <article class="flex flex-col justify-between rounded-[20px] border border-line bg-paper p-6 shadow-card">
                <div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="font-display text-2xl font-bold text-ink">
                            06
                        </span>
                        <span class="rounded-full border border-line bg-accent-soft px-2.5 py-0.5 text-[11px] font-semibold text-accent-dark">
                            Panduan Singkat
                        </span>
                    </div>

                    <h3 class="mt-3 font-display text-base font-bold text-ink">
                        Ringkasan Cakupan Platform
                    </h3>

                    <ul class="mt-3.5 space-y-2.5 text-xs leading-relaxed text-ink-soft">
                        <li class="flex items-start gap-2">
                            <span class="rounded-full bg-emerald-100 text-success px-2 py-0.5 text-[10px] font-bold">TERCAKUP</span>
                            <span>Pencarian advokat terverifikasi, konsultasi awal (pra-litigasi), verifikasi ekonomi pro bono, dan edukasi hukum.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="rounded-full bg-rose-100 text-danger px-2 py-0.5 text-[10px] font-bold">DI LUAR</span>
                            <span>Pendampingan sidang peradilan, pendaftaran dokumen perkara ke pengadilan, serta garansi putusan.</span>
                        </li>
                    </ul>
                </div>

                <div class="mt-5 border-t border-line pt-3">
                    <a
                        href="{{ url('/pro-bono') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-bold text-accent hover:text-accent-dark transition"
                    >
                        <span>Ajukan verifikasi status ekonomi Pro Bono</span>
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>
