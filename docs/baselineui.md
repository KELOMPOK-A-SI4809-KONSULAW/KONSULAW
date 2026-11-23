# baselineui.md — Baseline UI Konsulaw (Soft Editorial)

> Pengganti versi "Aurora". Stack: Laravel Blade + Tailwind CSS. Mobile-first, lalu desktop.
> Arah: **editorial web design** — serif besar, latar ivory, merah vermilion tegas, banyak ruang kosong. Modern tapi tidak kaku.
> Referensi rasa: poster/situs editorial (contoh Dribbble). Ambil **tipografi dan warna**, bukan layout collage.

## 1. Prinsip

1. **Sedikit teks.** Satu kalimat cukup. Detail ditaruh di halaman lain.
2. **Tipografi yang bekerja.** Judul serif besar jadi "wajah" desain, bukan dekorasi.
3. **Merah = aksi dan penekanan.** Dominan di tombol, kata kunci judul, dan state aktif. Latar tetap tenang.
4. **Garis, bukan kotak berat.** Pemisah pakai garis tipis dan jarak, bayangan seminimal mungkin.
5. **Ramah, bukan kaku.** Sudut membulat sedang, chip dan tombol berbentuk pill, bahasa awam.
6. **Kepercayaan terlihat.** Badge verifikasi dan disclaimer selalu jelas, tanpa klaim palsu.
7. **Ringan.** Tanpa blur berat dan animasi besar. Cocok untuk HP dan koneksi 4G.

## 2. Design Tokens

### Warna

| Token | Hex | Fungsi |
|---|---|---|
| `ivory` | `#FAF6EF` | latar halaman |
| `paper` | `#FFFFFF` | kartu, input |
| `ink` | `#1A1412` | teks utama, judul |
| `ink-soft` | `#6B5E57` | teks sekunder (±5.7:1 di atas ivory) |
| `line` | `#E7DED2` | garis dan border tipis |
| `accent` | `#C8321E` | tombol utama, kata penekanan, tab aktif (putih di atasnya ±5.3:1) |
| `accent-dark` | `#9E2415` | hover/pressed |
| `accent-soft` | `#FBE3DC` | latar chip aktif, focus ring (peach, bukan pink) |
| `glow-top` | `#F2542D` | ujung atas gradien header |
| `navy` | `#1E2A5E` | aksen sekunder opsional (maks ±5% tampilan, misal satu kata judul / link) |
| `dark` | `#1A1412` | bottom nav, footer |

### Gradien header (satu-satunya "glow")

```css
.header-glow {
  background: linear-gradient(180deg, rgba(242,84,45,.22) 0%, rgba(250,246,239,0) 100%);
}
```
Hanya dipakai di **area atas beranda** (tinggi maks ±240px). Halaman lain polos ivory.

### Warna status (ikon + teks wajib, jangan cuma warna)

| Token | Hex | Pakai untuk |
|---|---|---|
| `success` | `#15803D` | Terverifikasi, Selesai, Lunas |
| `info` | `#1D4ED8` | Diterima, Berlangsung |
| `warning` | `#B45309` | Pending, Menunggu |
| `danger` | `#8A1538` | Ditolak, error, hapus (**outline + ikon**, jangan solid, biar beda dari aksen merah) |
| `neutral` | `#6B7280` | Dibatalkan, nonaktif |

Badge khusus:
- **Pro Bono:** latar `accent-soft`, teks `accent-dark`, ikon hati/tangan.
- **Terverifikasi:** hijau + ikon centang.

### Tipografi

- **Display (judul): Fraunces** (Google Fonts, serif modern yang hangat, tidak kaku).
- **Body & UI: Plus Jakarta Sans.**
- Muat seperlunya:
  `family=Fraunces:opsz,wght@9..144,500..800&family=Plus+Jakarta+Sans:wght@400;500;600;700`
- Fallback: serif -> `Georgia, serif`; sans -> `system-ui, sans-serif`.

| Level | Mobile | Desktop | Font / bobot |
|---|---|---|---|
| Display / H1 | 30px | 52px | Fraunces 700, line-height 1.05, letter-spacing -0.02em |
| H2 | 22px | 32px | Fraunces 600 |
| H3 (nama advokat) | 18px | 20px | Fraunces 600 |
| Body | 15px | 16px | Jakarta 400, line-height 1.55 |
| Label / chip | 12-13px | 13px | Jakarta 600 |
| Angka langkah (01, 02, 03) | 36px | 48px | Fraunces 700, warna `accent` |

Gaya khas editorial:
- **Satu kata penekanan** di judul: *italic* Fraunces + warna `accent`.
- Garis tipis `line` sebagai pemisah antar bagian.
- Nomor besar untuk langkah.

### Bentuk, spasi, bayangan

| Token | Nilai |
|---|---|
| radius kartu | 20px |
| radius gambar | 16px |
| radius chip / tombol | 999px (pill) |
| radius input | 14px |
| skala spasi | 4, 8, 12, 16, 24, 32, 48, 64 px |
| padding halaman | 16px mobile, 32px desktop |
| bayangan kartu | `0 1px 0 rgba(26,20,18,.04)` (nyaris tanpa bayangan; pemisah utama = border `line`) |
| bayangan tombol utama | `0 6px 16px rgba(200,50,30,.25)` |

## 3. Tailwind config

```js
// tailwind.config.js
export default {
  content: ['./resources/**/*.blade.php', './resources/**/*.js'],
  theme: {
    extend: {
      fontFamily: {
        display: ['Fraunces', 'Georgia', 'serif'],
        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
      },
      colors: {
        ivory: '#FAF6EF', ink: '#1A1412', 'ink-soft': '#6B5E57', line: '#E7DED2',
        accent: { DEFAULT: '#C8321E', dark: '#9E2415', soft: '#FBE3DC' },
        glow: '#F2542D', navy: '#1E2A5E', dark: '#1A1412',
        success: '#15803D', info: '#1D4ED8', warning: '#B45309', danger: '#8A1538',
      },
      borderRadius: { card: '20px', img: '16px', input: '14px' },
      boxShadow: {
        card: '0 1px 0 rgba(26,20,18,.04)',
        btn: '0 6px 16px rgba(200,50,30,.25)',
      },
    },
  },
}
```

```css
/* resources/css/app.css */
body { @apply bg-ivory text-ink font-sans min-h-dvh; }
h1, h2, h3 { @apply font-display; }
.em { @apply italic text-accent; }          /* kata penekanan di judul */
.header-glow { background: linear-gradient(180deg, rgba(242,84,45,.22), rgba(250,246,239,0)); }
```

## 4. Struktur Beranda (halaman utama = direktori advokat)

Beranda **hanya** berisi direktori advokat. Kamus, peraturan, riwayat konsultasi, dan lainnya ada di halaman masing-masing, jangan ditumpuk di sini.

Urutan dari atas ke bawah:

| # | Bagian | Isi | Catatan |
|---|---|---|---|
| 1 | **Top bar** | logo Konsulaw, tombol Masuk / Daftar | ringkas, sticky |
| 2 | **Header + filter** (pakai `header-glow`) | judul 1 baris, **chip filter kasus** (scroll horizontal), filter wilayah, toggle "Pro bono tersedia", urutkan | **paling atas**, ini inti beranda |
| 3 | **Daftar card advokat** | grid card (lihat bagian 5), pagination / "muat lebih banyak" | mayoritas isi halaman |
| 4 | **Panduan singkat warga** | 3 langkah bernomor besar, 1 kalimat tiap langkah, tautan "Lihat Kamus Hukum" | di bawah card |
| 5 | **Tentang Konsulaw (ringkas)** | maks 2 kalimat + disclaimer kecil + tautan ke halaman Tentang | paling bawah, lalu footer |

### Wireframe mobile (teks)

```
[Logo Konsulaw]                [Masuk] [Daftar]

Cari advokat sesuai *kasus* Anda          <- judul 1 baris
( Semua )( Tanah & Desa )( Keluarga & Waris )( Kerja )( Pidana ) ->
[ Wilayah v ]  [ o Pro bono ]  [ Urutkan v ]

+---------------------------+
| foto  Nama Advokat   [v]  |
|       Tanah . Perdata     |
|       Kab. X . 4.8 (23)   |
|       Rp 75rb / sesi      |
|       [Pro bono]          |
|  [ Lihat Profil ]         |
+---------------------------+
... (card berikutnya)

------------------------------------
Panduan singkat
01  Pilih advokat sesuai kasus
02  Ajukan konsultasi
03  Chat setelah disetujui
Lihat Kamus Hukum ->

------------------------------------
Konsulaw menghubungkan warga dengan advokat
terverifikasi. Informasi di sini bersifat edukasi.
Selengkapnya ->
```

### Contoh teks (sengaja pendek)

| Elemen | Teks |
|---|---|
| H1 | Cari advokat sesuai *kasus* Anda |
| Langkah 1 | Pilih advokat sesuai kasus |
| Langkah 2 | Ajukan konsultasi awal |
| Langkah 3 | Mulai chat setelah disetujui |
| Tentang | Konsulaw menghubungkan warga dengan advokat terverifikasi. Informasi di sini bersifat edukasi dan bukan pengganti konsultasi advokat. |

### Filter kasus
- Chip diambil dari tabel `bidang_hukum` (dinamis, bukan hardcode).
- Chip pertama **"Semua"**, chip aktif: latar `accent`, teks putih. Tidak aktif: border `line`, latar `paper`.
- Filter wilayah dari tabel `wilayah`. Toggle **Pro bono** menyaring advokat dengan kuota tersisa.
- Hasil filter diperbarui tanpa pindah halaman (query string, bisa dibagikan).
- Kalau hasil kosong: tampilkan empty state "Belum ada advokat untuk kasus ini" + tombol "Reset filter".

## 5. Card Advokat

| Bagian | Spesifikasi |
|---|---|
| Foto | persegi / 4:5, radius 16px; **fallback inisial** nama (serif, di atas `accent-soft`) kalau belum ada foto |
| Nama | Fraunces 18px, 1-2 baris |
| Badge | **Terverifikasi** (selalu tampil) + **Pro Bono** (jika kuota > 0) |
| Bidang | maks 2 chip + "+n" |
| Wilayah | satu baris, ikon pin |
| Rating | bintang + angka + jumlah ulasan; tampilkan "Belum ada ulasan" kalau 0 (jangan tampil 0.0) |
| Tarif | "Rp xx / sesi" dari `tarif_konsultasi`; "Gratis (pro bono)" bukan klaim otomatis, hanya jika layanan pro bono dipilih |
| Aksi | satu tombol utama **Lihat Profil** (ajukan konsultasi ada di halaman detail) |
| Interaksi | seluruh card bisa disentuh; hover naik 2px di desktop |
| Gaya | latar `paper`, border 1px `line`, radius 20px, bayangan `card` |

Grid: 1 kolom (mobile), 2 kolom (`md`), 3 kolom (`lg`).

## 6. Komponen dasar

| Komponen | Spesifikasi |
|---|---|
| Button utama | bg `accent`, teks putih 700, pill, tinggi min 48px, bayangan `btn`, hover `accent-dark` |
| Button sekunder | border `line`, latar `paper`, teks `ink` |
| Button bahaya | outline `danger` + ikon |
| Chip filter | pill, tinggi 36px, aktif `accent`, tidak aktif border `line` |
| Input | radius 14px, tinggi min 48px, border `line`, focus ring 3px `accent-soft`, label di atas |
| Textarea kronologi | radius 14px, counter karakter |
| Badge status | pill, ikon + teks + warna (bagian 2) |
| Bottom nav | floating, bg `dark`, item aktif `accent`, 4 item (lihat bagian 7) |
| Modal | untuk alasan penolakan (wajib isi) dan konfirmasi aksi permanen |
| Tabel admin | rapat, garis `line`; mobile berubah jadi kartu |
| Empty state | ikon garis + 1 kalimat + 1 tombol |
| Skeleton | untuk card advokat dan chat saat memuat |
| Ikon | **Lucide / Heroicons (garis)**. Jangan emoji. |

## 7. Navigasi

Karena beranda sudah **menjadi direktori**, tab "Cari" terpisah tidak perlu.

| Item | Tujuan |
|---|---|
| Beranda | direktori advokat + filter |
| Konsultasi | riwayat dan status (butuh login) |
| Kamus | Kamus Hukum Populer + Daftar Peraturan |
| Akun | profil, verifikasi ekonomi, keluar / masuk |

- Mobile: bottom nav floating (4 item). Desktop (`lg`): berubah jadi top bar horizontal atau sidebar ramping.
- Halaman diberi `padding-bottom` agar tidak tertutup bottom nav.

## 8. Pola halaman lain (ringkas)

| Halaman | Isi utama | Catatan |
|---|---|---|
| Detail advokat | profil, bidang, wilayah, tarif, kuota pro bono, ulasan, tombol **Ajukan Konsultasi** (sticky di mobile) | tanpa glow |
| Form konsultasi | pilih layanan (reguler / pro bono), kategori, kronologi | tenang, 1 kolom |
| Pembayaran | ringkasan tarif + tombol bayar (Midtrans Snap) | status jelas |
| Chat | bubble pesan, header lawan bicara + status | hanya 2 pihak |
| Riwayat konsultasi | daftar + badge status | |
| Verifikasi ekonomi | status saat ini, upload dokumen, catatan admin | |
| Dashboard advokat | permintaan masuk, kuota pro bono, rating, status verifikasi | |
| Dashboard admin | antrean verifikasi, laporan, akun, konten | tabel padat |
| Kamus & peraturan | pencarian, ringkasan awam, unduh PDF | fokus keterbacaan |
| Auth | registrasi (pilih peran), login, lupa sandi | 1 kolom |

Glow header **hanya beranda**. Halaman lain polos ivory.

## 9. Konten dan tone

- Bahasa Indonesia awam, sapaan "Anda", hangat tapi tidak lebay.
- Tombol berupa kata kerja: "Lihat Profil", "Ajukan Konsultasi", "Terima Permintaan".
- Pesan error: **apa yang salah + apa yang harus dilakukan**.
- **Jangan pasang angka palsu** ("500+ advokat") kalau data belum ada. Pakai angka dinamis dari database atau jangan tampilkan.
- Disclaimer wajib (di bagian "Tentang" beranda dan form konsultasi): informasi bersifat edukasi, bukan pengganti konsultasi advokat, status verifikasi bukan pengesahan resmi pemerintah / organisasi advokat.

## 10. Performa dan aksesibilitas

- Kontras teks normal minimal 4.5:1 (cek ulang tiap kombinasi warna baru).
- Target sentuh minimal 44x44px.
- Focus ring terlihat di semua elemen interaktif.
- Setiap input punya `<label>`, error dibacakan (`aria-live`).
- Status tidak cuma warna: selalu ikon + teks.
- Font: preload hanya bobot yang dipakai. Fraunces dibatasi 500-800.
- Gambar advokat: lazy-load, ukuran dioptimalkan, atribut `alt`.
- Hormati `prefers-reduced-motion`.
- Uji minimal: 2 ukuran HP, 1 tablet, 1 desktop, 2 browser (NFR-04, NFR-06). Target muat beranda rata-rata < 3 detik (NFR-02).

## 11. Struktur Blade (usulan)

```
resources/views/
  beranda.blade.php
  components/
    top-bar.blade.php
    filter-kasus.blade.php        # chip kasus + wilayah + pro bono + urutkan
    advokat-card.blade.php
    panduan-singkat.blade.php     # 3 langkah
    tentang-ringkas.blade.php     # 2 kalimat + disclaimer
    badge-status.blade.php
    bottom-nav.blade.php
```

## 12. Checklist sebelum merge

- [ ] Memakai token Tailwind (tidak ada hex liar di Blade)
- [ ] Judul memakai `font-display`, body memakai sans
- [ ] Beranda hanya berisi: filter, card advokat, panduan singkat, tentang ringkas
- [ ] Tidak ada emoji sebagai ikon
- [ ] Glow hanya di header beranda
- [ ] Responsif di 360px, 768px, 1280px
- [ ] Badge status = ikon + teks + warna
- [ ] Ada state: loading, kosong, error
- [ ] Kontras teks lolos 4.5:1
- [ ] Padding bawah cukup (tidak tertutup bottom nav)
- [ ] Tidak ada angka/klaim palsu