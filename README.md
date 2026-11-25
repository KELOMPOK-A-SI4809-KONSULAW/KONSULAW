# Spesifikasi Profil Advokat & Profil Masyarakat serta Alur Konsultasi — Konsulaw

> **Bagian A** (bagian 0-7): Profil Advokat. **Bagian B** (di akhir dokumen): Profil Masyarakat, alur Reguler vs Pro Bono, dan pembayaran Midtrans.

Dasar: Proposal Kelompok A SDG 16 (FR-02, FR-03, FR-05, FR-09, FR-13, FR-16; ERD tabel `users`, `profil_advokat`, `dokumen_advokat`, `advokat_bidang`, `advokat_wilayah`, `ulasan`; Use Case "Mengelola Profil", "Mengelola Kuota Pro Bono", "Unggah Dokumen Verifikasi").

## 0. Masalah pada kondisi sekarang

Halaman "Profil Saya" milik advokat masih memakai tampilan masyarakat. Padahal di proposal, advokat punya data dan hak akses yang berbeda: dokumen verifikasi (Ijazah, BAS, KTA), bidang keahlian, wilayah praktik, tarif, kuota pro bono. Semua itu tidak dimiliki masyarakat.

Prinsip pembeda:
- **Profil Saya (advokat)** = tempat MENGEDIT data diri dan data profesional.
- **Dasbor Advokat** = tempat MELIHAT ringkasan dan MENANGGAPI permintaan konsultasi.
- **Halaman profil publik** (yang dilihat masyarakat dari Cari Advokat) = hasil tampilan dari data di Profil Saya.

---

## 1. Navigasi untuk role Advokat

| Menu | Keterangan |
|---|---|
| Beranda | Landing page |
| Dasbor Advokat | Ringkasan + permintaan masuk |
| Permintaan Konsultasi | Daftar permintaan (Menunggu / Diterima / Ditolak / Selesai) |
| Kamus Hukum | Boleh tetap ada (FR-14) |
| Profil Saya | Halaman yang dibahas di dokumen ini |

- Menu **"Layanan Pro Bono" dan "Cari Advokat" versi masyarakat disembunyikan** untuk advokat. Pengajuan status pro bono hanya untuk masyarakat. Advokat mengatur *kuota* pro bono, bukan mengajukan status.
- Label di badge pojok kanan atas harus "ADVOKAT" (sudah benar).
- Bottom-nav mobile: Beranda, Permintaan, Kamus, Profil.

---

## 2. Struktur halaman Profil Saya (advokat)

Gunakan tab atau section bernavigasi samping (di mobile jadi accordion).

### 2.0 Header profil (selalu tampil di atas)
- Foto profil (upload JPG/PNG, maks 2 MB) dengan avatar inisial sebagai fallback.
- Nama lengkap dan gelar (contoh: "Bambang Prakoso, S.H.").
- Badge **status verifikasi** (lihat 2.1).
- Rating rata-rata dan jumlah ulasan (read-only, dari tabel `ulasan`). Contoh: "4.8 (52 ulasan)" atau "Belum ada ulasan".
- Persentase kelengkapan profil (progress bar). Contoh: "Profil 70% lengkap".
- Tombol **"Lihat Tampilan Publik"** untuk preview kartu seperti yang dilihat masyarakat.

### 2.1 Banner status verifikasi (paling penting)
Banner ini punya **5 kondisi**. Kondisi pertama khusus advokat yang baru pertama kali login dan belum pernah mengajukan apa pun.

Nilai `profil_advokat.status_verifikasi`: `belum_diajukan` (default saat registrasi), `pending`, `terverifikasi`, `ditolak`. Kondisi "Belum Lengkap" **tidak** perlu nilai tersendiri. Itu dihitung dari kelengkapan data (lihat 2.1.2).

| # | Kondisi | Kapan muncul | Tampilan | Aksi advokat |
|---|---|---|---|---|
| 1 | **Baru Daftar** (login pertama) | `belum_diajukan` dan **belum ada satu pun** data profesional/dokumen terisi | Banner **netral/biru** (bukan peringatan): "Selamat datang, [Nama]! Profil Anda belum tampil di direktori. Selesaikan 4 langkah berikut untuk diverifikasi." + checklist onboarding | Tombol "Mulai Lengkapi Profil" |
| 2 | **Belum Lengkap** (draft) | `belum_diajukan` dan **sudah ada sebagian** data terisi | Banner **kuning**: "Profil baru 40% lengkap. Lanjutkan untuk bisa mengirim verifikasi." + progress bar + daftar bagian yang masih kosong | Tombol "Lanjutkan Mengisi" |
| 3 | **Pending** | `pending` | Banner kuning ikon jam: "Dokumen sedang diperiksa admin. Notifikasi hasil akan muncul otomatis." | Edit dokumen dikunci. Data lain (foto, bio) boleh diubah. |
| 4 | **Terverifikasi** | `terverifikasi` | Banner hijau + badge: "Profil Anda tampil di direktori." | Boleh edit tarif, kuota, ketersediaan. Mengubah KTA/BAS memicu verifikasi ulang. |
| 5 | **Ditolak** | `ditolak` | Banner merah + **catatan admin** (`catatan_admin`) | Tombol "Perbaiki & Kirim Ulang" |

Catatan: hanya advokat Terverifikasi yang tampil di direktori dan boleh menerima konsultasi. Pada kondisi 1, 2, 3, dan 5, dasbor menampilkan pesan "Anda belum bisa menerima permintaan konsultasi".

#### 2.1.1 Alur login pertama kali (first-time experience)

1. Advokat registrasi (role = advokat). Sistem membuat `users` dan `profil_advokat` dengan `status_verifikasi = belum_diajukan`.
2. Setelah registrasi atau login pertama, **redirect otomatis ke Profil Saya**, bukan ke dasbor kosong.
3. Tampilkan kondisi 1 (Baru Daftar) dengan **checklist onboarding**:

   | Langkah | Isi | Tab terkait |
   |---|---|---|
   | 1 | Lengkapi informasi pribadi (nama, no. HP, domisili) | Informasi Pribadi |
   | 2 | Isi data profesional (No. KTA, No. BAS, pengalaman) | Data Profesional |
   | 3 | Pilih bidang keahlian dan wilayah praktik | Bidang & Wilayah |
   | 4 | Unggah Ijazah S.H., BAS, dan KTA, lalu kirim verifikasi | Dokumen Verifikasi |

   Setiap langkah punya status: ○ belum, ✓ selesai. Langkah yang diklik langsung membuka tab-nya.
4. Langkah 1-3 tersimpan sebagai draft. Advokat boleh berhenti dan melanjutkan nanti. Setelah ada yang tersimpan, banner berubah ke kondisi 2 (Belum Lengkap).
5. Tombol "Kirim untuk Verifikasi" baru aktif ketika langkah 1-4 selesai. Setelah dikirim, status menjadi `pending` (kondisi 3).
6. Pada login berikutnya sebelum terverifikasi, advokat tetap diarahkan ke Profil Saya (atau dasbor yang menampilkan checklist yang sama di bagian atas) sampai berstatus Terverifikasi.

Pembeda utama kondisi 1 dan 2: pada kondisi 1 jangan menampilkan peringatan atau persentase rendah yang terkesan "salah". Pakai nada menyambut dan petunjuk langkah. Persentase dan daftar kekurangan baru ditampilkan di kondisi 2.

#### 2.1.2 Cara menghitung kelengkapan (untuk membedakan kondisi 1 dan 2)

Hitung berdasarkan 4 komponen, masing-masing bobot 25%:

| Komponen | Dianggap selesai jika |
|---|---|
| Informasi pribadi | nama, no. HP, domisili terisi |
| Data profesional | no. KTA, no. BAS, pengalaman terisi |
| Bidang & wilayah | minimal 1 bidang dan 1 wilayah |
| Dokumen | ketiga dokumen terunggah |

- Kelengkapan = 0% dan `belum_diajukan` : kondisi **1 (Baru Daftar)**.
- Kelengkapan 1-99% dan `belum_diajukan` : kondisi **2 (Belum Lengkap)**.
- Kelengkapan 100% dan `belum_diajukan` : tombol Kirim aktif, banner "Profil lengkap, siap dikirim".
- Catatan: nama dan email sudah terisi dari registrasi, jadi hitung 0% jika komponen lain masih kosong (jangan hitung data dari form registrasi sebagai progres).

#### 2.1.3 Empty state di tiap tab pada login pertama

- Tab Bidang & Wilayah: "Belum ada bidang keahlian. Pilih minimal satu agar masyarakat dapat menemukan Anda."
- Tab Dokumen: tiga kartu berstatus "Belum diunggah" dengan petunjuk format dan ukuran.
- Tab Kuota Pro Bono: "Atur kuota setelah profil Anda terverifikasi" (field boleh diisi, tetapi belum berdampak).
- Tab Ulasan: "Belum ada ulasan."
- Header: rating tampil "Belum ada ulasan", badge menampilkan "Belum Terverifikasi" (abu-abu), tombol "Lihat Tampilan Publik" dinonaktifkan atau bertanda "Pratinjau (belum tayang)".

### 2.2 Tab "Informasi Pribadi" (tabel `users`)
| Field | Tipe | Aturan |
|---|---|---|
| Nama lengkap (beserta gelar) | text | wajib |
| Email | email | wajib, unik, read-only atau butuh verifikasi untuk diubah |
| No. HP | text | wajib, format Indonesia (08xx/+62) |
| Foto profil | file | opsional |
| Domisili (Provinsi, Kota/Kab) | select bertingkat dari `wilayah` | wajib |

### 2.3 Tab "Data Profesional" (tabel `profil_advokat`)
| Field | Tipe | Aturan |
|---|---|---|
| No. KTA (Kartu Tanda Anggota) | text | wajib, unik |
| No. BAS (Berita Acara Sumpah) | text | wajib |
| Pengalaman (tahun) | number | wajib |
| Bio singkat / tentang saya | textarea | opsional, maks ~500 karakter. Dipakai di profil publik. |
| Nama organisasi advokat / kantor | text | opsional |

(Bio dan organisasi perlu kolom tambahan di `profil_advokat` jika belum ada. Lebih baik ditambahkan karena profil publik butuh deskripsi.)

### 2.4 Tab "Bidang Keahlian & Wilayah Praktik"
- **Bidang keahlian**: multi-select dari `bidang_hukum` (Pertanahan & Agraria Desa, Perdata, Ketenagakerjaan, Pidana, Bisnis & UMKM, Keluarga & Waris, dll.). Disimpan di `advokat_bidang`. Minimal 1.
- **Wilayah praktik**: multi-select dari `wilayah` (kota/kabupaten). Disimpan di `advokat_wilayah`. Minimal 1. Ini data yang dipakai filter "Semua Wilayah" di Cari Advokat.
- Ditampilkan sebagai chip/tag yang bisa dihapus.

### 2.5 Tab "Dokumen Verifikasi" (tabel `dokumen_advokat`)
Tiga dokumen wajib, masing-masing satu kartu:

| Dokumen | Format | Status per dokumen |
|---|---|---|
| Ijazah Sarjana Hukum | PDF/JPG/PNG, maks 5 MB | Belum diunggah / Menunggu / Disetujui / Ditolak |
| Berita Acara Sumpah (BAS) | sama | sama |
| Kartu Tanda Anggota (KTA) | sama | sama |

Elemen tiap kartu:
- Nama file yang sudah diunggah, tanggal unggah, tombol "Ganti File" (hanya saat Belum Lengkap atau Ditolak).
- Catatan penolakan dari admin jika ditolak (`catatan_admin`).
- Tombol **"Kirim untuk Verifikasi"** aktif hanya jika ketiga dokumen sudah diunggah dan Data Profesional sudah terisi. Setelah dikirim, status menjadi Pending.
- Kotak privasi (selaras TR-04 dan NFR-05): "Dokumen disimpan di server privat dan hanya dapat dilihat admin." Dokumen **tidak** ditampilkan ke publik.
- Preview dokumen hanya lewat route ter-autentikasi, bukan link publik.

### 2.6 Tab "Tarif & Ketersediaan"
| Field | Aturan |
|---|---|
| Tarif konsultasi reguler (Rp / sesi) | number, wajib, ditampilkan di kartu ("Rp 280.000 / sesi") |
| Ketersediaan | toggle "Menerima konsultasi baru" (aktif/nonaktif). Jika nonaktif, advokat tidak bisa diajukan konsultasi baru tetapi tetap tampil di direktori dengan label "Sedang tidak menerima". |

Catatan: sesuai proposal bagian 5, pembayaran reguler berada di luar sistem. Cukup tampilkan tarif dan kolom "Catatan pembayaran" (opsional, misalnya nomor rekening/instruksi) yang dikirim ke masyarakat setelah permintaan diterima.

### 2.7 Tab "Kuota Pro Bono" (use case "Mengelola Kuota Pro Bono")
- Input **kuota pro bono per bulan** (number, 0 sampai batas yang ditetapkan, misalnya 10). Nilai 0 berarti tidak menyediakan pro bono.
- Tampilkan: "Sisa kuota bulan ini: 2 dari 5" (dan indikator terpakai).
- Daftar singkat konsultasi pro bono yang sedang berjalan bulan ini.
- Kuota di-reset otomatis tiap awal bulan (scheduler Laravel).
- Aturan: pengurangan kuota saat advokat **menerima** permintaan pro bono (FR-09). Jika ditolak atau dibatalkan, kuota tidak berkurang atau dikembalikan.
- Peringatan: kuota yang diturunkan tidak boleh di bawah jumlah yang sudah terpakai bulan ini.
- Tampil di kartu publik sebagai "Pro Bono: Sisa N" (sudah ada di UI Anda).

### 2.8 Tab "Ulasan Saya" (read-only)
- Daftar rating 1-5 dan komentar dari `ulasan` (nama pengulas boleh disamarkan, misalnya "Warga Kab. Bandung").
- Advokat **tidak bisa mengedit atau menghapus** ulasan. Boleh tombol "Laporkan ulasan" ke admin (tabel `laporan`, FR-15).
- Empty state: "Belum ada ulasan".

### 2.9 Tab "Keamanan Akun"
- Ubah kata sandi (kata sandi lama, baru, konfirmasi).
- Logout dari semua perangkat (opsional).
- Nonaktifkan akun (opsional, meminta konfirmasi).

---

## 3. Dasbor Advokat (terpisah dari profil, ringkasan saja)

- Kartu statistik: permintaan baru (Menunggu Tanggapan), konsultasi berlangsung, konsultasi selesai, sisa kuota pro bono.
- Daftar permintaan terbaru dengan tombol Lihat Detail. Detail memuat nama pemohon, jenis layanan (Reguler/Pro Bono), kategori, kronologi, dan lampiran. Tombol Terima atau Tolak (dengan alasan).
- Daftar chat aktif.
- Notifikasi (lonceng): permintaan baru, hasil verifikasi, ulasan baru.
- Jika belum Terverifikasi: tampil checklist "Langkah yang perlu diselesaikan".

---

## 4. Hak akses dan keamanan (FR-16)

- Route profil advokat hanya untuk `role = advokat` (middleware).
- Advokat hanya dapat melihat dan mengedit profilnya sendiri (policy berdasarkan `user_id`).
- Dokumen verifikasi: disimpan di `storage/app/private`, diakses lewat controller yang mengecek pemilik atau admin.
- Masyarakat yang membuka profil publik advokat tidak boleh melihat: no. HP, email, no. KTA/BAS lengkap, dokumen, catatan admin. Yang tampil: nama, badge verifikasi, bidang, wilayah, pengalaman, bio, tarif, kuota sisa, rating.
- Validasi file: mime type dan ukuran di server, bukan hanya di browser.

---

## 5. Tampilan publik advokat (apa yang dilihat masyarakat)

Agar konsisten dengan Mockup 4.5.2: nama + badge Terverifikasi, bidang keahlian (chip), wilayah praktik, rating + jumlah ulasan, "Pro bono sisa N" (dicoret/"Penuh" jika 0), tarif, tombol **Ajukan Konsultasi** (bukan "Ajukan Verifikasi").

---

## 6. Checklist penerimaan (siap untuk black-box testing)

- [ ] Login sebagai advokat tidak menampilkan form/halaman pengajuan pro bono masyarakat.
- [ ] Navbar advokat berisi Dasbor, Permintaan Konsultasi, Profil Saya.
- [ ] Banner status verifikasi tampil sesuai 5 kondisi (Baru Daftar, Belum Lengkap, Pending, Terverifikasi, Ditolak).
- [ ] Registrasi advokat langsung diarahkan ke Profil Saya dan menampilkan banner "Baru Daftar" beserta checklist 4 langkah.
- [ ] Setelah mengisi sebagian data, banner berubah dari "Baru Daftar" ke "Belum Lengkap" dengan persentase yang benar.
- [ ] Data yang tersimpan sebagai draft tetap ada saat advokat logout lalu login kembali.
- [ ] Advokat dapat mengisi dan menyimpan Informasi Pribadi, Data Profesional, Bidang, Wilayah.
- [ ] Upload 3 dokumen berjalan, file salah format/ukuran ditolak dengan pesan jelas.
- [ ] "Kirim untuk Verifikasi" tidak aktif jika data/dokumen belum lengkap.
- [ ] Status Pending mengunci edit dokumen.
- [ ] Setelah admin menolak, catatan penolakan terlihat dan upload ulang bisa dilakukan.
- [ ] Advokat belum Terverifikasi tidak muncul di Cari Advokat.
- [ ] Mengubah kuota pro bono langsung tercermin di kartu publik.
- [ ] Kuota tidak bisa di bawah jumlah terpakai bulan ini.
- [ ] Menerima permintaan pro bono mengurangi kuota; menolak tidak.
- [ ] Advokat A tidak dapat membuka dokumen/profil edit advokat B (uji akses langsung via URL).
- [ ] Tampilan responsif di layar ponsel.

---

## 7. Urutan pengerjaan yang disarankan

1. Pisahkan view: `advokat/profil.blade.php` (bukan memakai view masyarakat) dan tambahkan guard role di route.
2. Header profil + banner status verifikasi.
3. Tab Informasi Pribadi dan Data Profesional.
4. Bidang & wilayah (relasi many-to-many).
5. Upload dokumen + alur "Kirim untuk Verifikasi".
6. Tarif, ketersediaan, kuota pro bono.
7. Ulasan read-only dan keamanan akun.
8. Hubungkan ke kartu publik di Cari Advokat.


---
---

# BAGIAN B — Pengelolaan Profil Masyarakat & Alur Konsultasi

Dasar: proposal bagian 2.3.2 (To-Be), FR-04, FR-07 s.d. FR-13, Use Case "Mengajukan Konsultasi Awal"; ERD tabel `users`, `verifikasi_ekonomi`, `konsultasi`, `pesan_konsultasi`, `ulasan`, `notifikasi`, `laporan`.

## B0. Diagnosis: kenapa alur sekarang terasa salah

Alur yang berjalan sekarang:
1. Klik profil advokat
2. Muncul form upload dokumen verifikasi pro bono
3. Status Pending
4. Disetujui, lalu... tidak ada langkah lanjutan

Ada 3 kesalahan logika:

| # | Masalah | Penjelasan |
|---|---|---|
| 1 | **Verifikasi ekonomi ditaruh di alur "klik profil advokat"** | Verifikasi itu milik **akun** (sekali, berlaku untuk semua advokat), bukan milik satu advokat. Di profil advokat seharusnya hanya ada tombol **Ajukan Konsultasi**. |
| 2 | **Tidak ada pilihan Reguler vs Pro Bono** | Proposal (use case A, langkah 3) mewajibkan pengguna memilih jenis layanan. Verifikasi hanya muncul **jika** memilih Pro Bono dan statusnya belum Pro Bono. Pengguna Reguler tidak perlu upload apa pun. |
| 3 | **Status "Pro Bono Aktif" adalah jalan buntu** | Halaman hanya menampilkan "Telah Disetujui" tanpa tombol lanjut. Status itu baru izin; konsultasinya sendiri belum dibuat. |

## B1. Jawaban: dokumen kasus diunggah bersamaan dengan verifikasi, atau terpisah?

**Terpisah.** Jangan digabung. Alasannya:

| Aspek | Verifikasi ekonomi | Dokumen kasus |
|---|---|---|
| Milik | Akun (sekali, dipakai ke semua advokat) | Satu konsultasi dengan satu advokat |
| Isi | SKTM, KIS, KKS, bukti pendapatan | Somasi, surat tanah, kontrak, kronologi tertulis, dll. |
| Dibaca oleh | **Admin** | **Advokat yang dituju saja** |
| Waktu | Sebelum memilih jalur pro bono | Saat mengajukan konsultasi atau di dalam chat |

Alasan tambahan yang kuat:
- **Kerahasiaan.** Dokumen kasus bersifat rahasia antara klien dan advokat. Admin tidak boleh membacanya. Menggabungkannya ke verifikasi berarti admin melihat isi perkara. Ini bertentangan dengan FR-16 dan NFR-05 (pembatasan akses dan perlindungan data pribadi).
- **Tidak efisien.** Kalau status pro bono sudah disetujui lalu pengguna ingin konsultasi dengan 3 advokat berbeda, dokumen kasusnya berbeda-beda, sedangkan SKTM tetap sama.
- **Ruang lingkup.** Platform hanya mendukung pra-litigasi (batasan 3 di proposal). Kronologi tertulis sudah cukup di form pengajuan. Lampiran bersifat **opsional**.

Aturan akhirnya:
- Verifikasi = **hanya dokumen ekonomi**, diperiksa admin, dilakukan sekali.
- Pengajuan konsultasi = **kronologi wajib + lampiran opsional** (PDF/JPG/PNG, maks 5 MB, maks 3 berkas), hanya terlihat advokat tujuan.
- Dokumen tambahan setelahnya dikirim lewat **chat** (`pesan_konsultasi.lampiran`).

## B2. Alur lengkap yang benar (To-Be)

### B2.1 Pengajuan konsultasi (titik awal untuk semua)

1. Masyarakat login, buka **Cari Advokat**, lalu membuka profil advokat.
2. Di profil advokat hanya ada tombol **Ajukan Konsultasi** (bukan form upload verifikasi). Jika advokat sedang "tidak menerima konsultasi baru", tombol dinonaktifkan.
3. Form pengajuan, **Langkah 1: Pilih jenis layanan**
   - Kartu **Reguler**: menampilkan tarif ("Rp 280.000 / sesi"), keterangan "Bayar setelah advokat menerima".
   - Kartu **Pro Bono**: menampilkan "Sisa kuota: N". Jika N = 0, kartu dinonaktifkan dengan label "Kuota bulan ini penuh". Jika status akun belum Pro Bono, tampil label "Perlu verifikasi".
4. **Langkah 2: Isi kasus**: kategori masalah (bidang hukum), judul singkat, kronologi, pertanyaan hukum, lampiran opsional.
5. Klik **Kirim Pengajuan**: sistem memvalidasi lalu menyimpan `konsultasi.status = menunggu_tanggapan`, dan advokat mendapat notifikasi.

### B2.2 Cabang Pro Bono (di langkah 3 di atas)

Saat pengguna memilih Pro Bono, sistem mengecek `users.status_ekonomi`:

| Status akun | Perilaku |
|---|---|
| **Reguler** dan belum pernah mengajukan | Tampilkan panel: "Untuk pro bono, akun Anda perlu diverifikasi dulu." Tombol **Ajukan Verifikasi** membuka halaman pengajuan (atau modal) untuk upload SKTM/KIS/KKS. Draf form kasus **disimpan** sehingga pengguna bisa kembali. Ada juga opsi "Lanjut sebagai Reguler". |
| **Pending** | Panel: "Verifikasi sedang diperiksa admin." Pengguna boleh: (a) menunggu (draf tersimpan, dapat dilanjutkan saat disetujui), atau (b) beralih ke Reguler. |
| **Pro Bono** (disetujui) | Lolos. Lanjut ke langkah 4 dan 5. |
| **Reguler** karena ditolak | Panel merah berisi `catatan_admin` dan tombol **Unggah Ulang**. Tetap boleh memilih Reguler. |

**Setelah admin menyetujui** (jawaban untuk pertanyaan "lalu apa?"):
1. Status akun menjadi `Pro Bono`, notifikasi dalam aplikasi: "Status Pro Bono Anda aktif."
2. Halaman status pro bono menampilkan tombol **Cari Advokat dengan Kuota Pro Bono** (menuju direktori dengan filter "Pro bono tersedia" aktif), dan jika ada draf tersimpan, tombol **Lanjutkan Pengajuan Konsultasi**.
3. Pengguna kembali ke profil advokat, memilih kartu Pro Bono, lalu mengirim pengajuan.

### B2.3 Setelah pengajuan terkirim: jalur Pro Bono

```
menunggu_tanggapan --(advokat Terima)--> berlangsung   [kuota advokat -1, chat terbuka]
                   --(advokat Tolak)---> ditolak       [notifikasi + alasan, kuota tidak berubah]
                   --(pengguna batal)--> dibatalkan
berlangsung --(advokat/pengguna akhiri)--> selesai --> pengguna dapat memberi rating
```

Validasi ulang saat advokat menekan **Terima**: status akun masih Pro Bono dan kuota masih > 0 (cek ulang untuk menghindari dua pengajuan yang berebut kuota terakhir). Jika gagal, tampilkan pesan dan sarankan memilih advokat lain.

### B2.4 Setelah pengajuan terkirim: jalur Reguler + Midtrans

```
menunggu_tanggapan --(Tolak)--> ditolak
                   --(Terima)--> menunggu_pembayaran   [belum ada chat]
menunggu_pembayaran --(bayar sukses, webhook settlement)--> berlangsung   [chat terbuka]
                    --(batas waktu habis/expire)--> kedaluwarsa
                    --(pengguna batal)--> dibatalkan
berlangsung --> selesai --> rating
```

Urutan dan alasan:
- **Bayar setelah advokat menerima**, bukan sebelumnya. Sesuai proposal (2.3.2) dan agar pengguna tidak membayar untuk permintaan yang akhirnya ditolak.
- Saat status `menunggu_pembayaran`, halaman detail konsultasi menampilkan ringkasan tagihan dan tombol **Bayar Sekarang** (Midtrans Snap popup), dengan batas waktu (misalnya 24 jam).
- Chat **hanya terbuka** setelah status pembayaran `settlement`/`capture` terkonfirmasi lewat **webhook** server (bukan hanya dari redirect di browser).

### B2.5 Kapan ikon chat muncul (aturan tunggal untuk Reguler dan Pro Bono)

**Aturan inti:** ikon/tombol chat muncul **jika dan hanya jika `konsultasi.status = berlangsung`**. Status ini hanya bisa dicapai lewat satu "gerbang" yang berbeda untuk tiap jalur:

| | Reguler | Pro Bono |
|---|---|---|
| Syarat 1 | Advokat **menerima** permintaan | Advokat **menerima** permintaan |
| Syarat 2 | **Pembayaran berhasil** (webhook Midtrans: settlement) | Status akun **Pro Bono** (terverifikasi) dan kuota > 0 saat diterima |
| Hasil | status menjadi `berlangsung`, **ikon chat muncul otomatis** | status menjadi `berlangsung`, **ikon chat muncul otomatis**, kuota advokat -1 |
| Verifikasi ekonomi | Tidak perlu | Wajib (sekali per akun) |
| Pembayaran | Wajib | Tidak ada |

Jadi perbedaannya hanya pada gerbang (bayar vs. verifikasi + kuota). Setelah masuk `berlangsung`, chat keduanya identik.

**Urutan Reguler dan alasan "bayar setelah diterima":** pengguna mengirim pengajuan, advokat menerima, lalu pengguna membayar, lalu chat muncul. Urutan ini sesuai proposal (2.3.2) dan menghindari pengguna membayar lalu ditolak (yang berarti harus refund). Jika kelompok tetap ingin **bayar di awal**, konsekuensinya: harus ada alur refund otomatis saat advokat menolak atau tidak merespons, dan status baru `dibayar_menunggu_tanggapan`. Untuk prototipe, urutan di proposal lebih sederhana dan lebih aman.

**Di mana ikon chat muncul:**
- Kartu/baris konsultasi di tab **Konsultasi Saya** (tombol **Buka Chat**).
- Halaman detail konsultasi.
- Badge pesan belum dibaca di navbar.
- Notifikasi: "Pembayaran berhasil, chat dengan [Advokat] sudah aktif" (reguler) atau "Permintaan pro bono Anda diterima, chat sudah aktif" (pro bono).
- Sisi advokat: daftar chat aktif di Dasbor.

**Sebelum syarat terpenuhi**, ikon tidak disembunyikan secara membingungkan. Tampilkan tombol nonaktif dengan teks alasan:
- `menunggu_tanggapan`: "Chat aktif setelah advokat menerima."
- `menunggu_pembayaran`: "Selesaikan pembayaran untuk membuka chat." (+ tombol **Bayar Sekarang**)
- `ditolak`/`kedaluwarsa`/`dibatalkan`: tidak ada tombol chat.

**Implementasi:** satu method/policy (misalnya `Konsultasi::chatTerbuka()` atau `KonsultasiPolicy@chat`) yang dipakai oleh tampilan **dan** route chat. Cek di server, bukan hanya menyembunyikan ikon: membuka URL chat langsung saat status belum `berlangsung` harus mengembalikan 403. Setelah `selesai`, chat menjadi **read-only** (riwayat tetap bisa dibaca, tidak bisa kirim pesan).

### B2.6 Apakah Reguler perlu mengunggah/mengisi kasus? (Jawaban: ya, kronologi tetap wajib)

**Form pengajuan sama untuk Reguler dan Pro Bono.** Bedanya hanya satu bagian, yaitu jenis layanan.

| Bagian form | Reguler | Pro Bono |
|---|---|---|
| Kategori masalah (bidang hukum) | Wajib | Wajib |
| Judul singkat | Wajib | Wajib |
| Kronologi dan pertanyaan hukum | **Wajib** | **Wajib** |
| Lampiran dokumen kasus | **Opsional** | **Opsional** |
| Dokumen ekonomi (SKTM, dll.) | Tidak ada | Lewat verifikasi akun (bukan di form ini) |

Alasan kronologi tetap wajib untuk Reguler:
1. Sesuai use case "Menanggapi Permintaan Konsultasi": advokat meninjau kronologi **sebelum** memutuskan menerima atau menolak. Tanpa kronologi, advokat tidak punya dasar, dan ia bisa menolak karena di luar keahlian. Itu merugikan pengguna yang sudah (atau akan) membayar.
2. Memenuhi kolom `konsultasi.kronologi` dan `bidang_id` di ERD, yang dipakai juga untuk pencocokan keahlian.
3. Konsultasi lebih efisien karena advokat sudah membaca kasusnya sebelum chat dimulai.

Alasan lampiran cukup **opsional** (keduanya):
- Platform ini hanya pra-litigasi (batasan 3 di proposal). Banyak pengguna awam belum punya dokumen rapi di awal.
- Jika diwajibkan, pengguna di desa dengan ponsel dan sinyal terbatas akan kesulitan (NFR-04).
- Dokumen tambahan tetap bisa dikirim lewat chat setelah chat aktif.

Rekomendasi UI: sediakan hint "Dokumen pendukung membantu advokat memahami kasus, misalnya surat tanah, kontrak, somasi. Boleh dilewati dan dikirim lewat chat nanti." Batasi 3 berkas, 5 MB per berkas, PDF/JPG/PNG.

**Alur ringkas yang direkomendasikan:**

```
[Reguler]   Ajukan (kronologi, lampiran opsional) -> Advokat terima -> Bayar (Midtrans) -> CHAT AKTIF
[Pro Bono]  (Akun terverifikasi Pro Bono) -> Ajukan (kronologi, lampiran opsional) -> Advokat terima (kuota -1) -> CHAT AKTIF
```

## B3. Midtrans (payment gateway)

### B3.1 Catatan penting untuk proposal (perlu disinkronkan)

Proposal bagian 5 menyatakan: *"Integrasi pembayaran otomatis melalui payment gateway tidak dimasukkan ke jadwal... Apabila kelompok memutuskan pembayaran otomatis menjadi fitur aplikasi, kebutuhan fungsional, teknologi, dan PBI terkait perlu ditambahkan terlebih dahulu."*

Karena kalian memutuskan memakai Midtrans, hal-hal berikut perlu **ditambahkan ke proposal/dokumen** agar konsisten:
- **FR-10** sudah ada (Pembayaran Konsultasi Reguler). Perluas deskripsinya: "melalui Midtrans, status ditentukan dari notifikasi server."
- **TR baru**: "Payment gateway Midtrans (Snap) mode sandbox pada prototipe."
- **PBI baru** (misalnya PBI-10 Pembayaran Konsultasi Reguler), masuk Sprint 2.
- **Tabel baru** di ERD: `pembayaran` (lihat B3.2). Jumlah tabel jadi 14.
- Hapus/ubah kalimat "pengaturan pembayaran berada di luar sistem".
- Status konsultasi tambahan: `menunggu_pembayaran`, `kedaluwarsa` (FR-12).
- Gunakan **sandbox** untuk prototipe tugas.

### B3.2 Tabel `pembayaran` (usulan)

| Kolom | Keterangan |
|---|---|
| id (PK) | |
| konsultasi_id (FK, unik) | Satu konsultasi reguler punya satu pembayaran aktif |
| order_id | Unik, dikirim ke Midtrans (misalnya `KSL-{konsultasi_id}-{timestamp}`) |
| jumlah | Diambil dari `tarif_konsultasi` advokat **pada saat diterima** (simpan snapshot, jangan baca ulang) |
| status | `pending`, `paid`, `failed`, `expired`, `cancelled` |
| metode | payment_type dari Midtrans (QRIS, VA, e-wallet, dll.) |
| snap_token | Token transaksi |
| transaction_id | ID transaksi Midtrans |
| dibayar_pada | datetime |
| payload_notifikasi | JSON mentah notifikasi (untuk audit) |

### B3.3 Aturan teknis ringkas

1. Server membuat transaksi Snap saat advokat menerima, bukan di browser. `server_key` hanya di `.env` backend.
2. Endpoint webhook (`POST /midtrans/notification`) dikecualikan dari CSRF. **Verifikasi signature key** (SHA512 dari order_id + status_code + gross_amount + server_key) sebelum memproses.
3. Pemrosesan webhook harus **idempotent**: notifikasi yang sama dua kali tidak membuat efek ganda.
4. Hanya status `settlement` (atau `capture` dengan fraud `accept` untuk kartu) yang mengubah konsultasi menjadi `berlangsung`.
5. Status `expire`/`cancel`/`deny` mengubah pembayaran menjadi `expired`/`cancelled`/`failed`; konsultasi menjadi `kedaluwarsa` atau tetap `menunggu_pembayaran` dengan opsi bayar ulang.
6. Redirect balik ke halaman konsultasi hanya untuk tampilan; keputusan akses chat tetap dari database.
7. Untuk pro bono, **tidak ada** transaksi dan tidak ada record `pembayaran`.
8. Kebijakan pengembalian dana (refund) bila advokat tidak merespons: tentukan secara eksplisit atau nyatakan di luar lingkup prototipe.

### B3.4 Cara kerja Midtrans (penjelasan sederhana)

**Analogi:** Midtrans itu seperti kasir pihak ketiga. Konsulaw tidak menyentuh uang atau data kartu pengguna sama sekali. Konsulaw hanya berkata ke Midtrans, "tolong tagih pengguna ini Rp 280.000 untuk pesanan nomor X." Midtrans yang menampilkan pilihan bayar (QRIS, Virtual Account bank, e-wallet), menerima uangnya, lalu **mengabari Konsulaw** bahwa pesanan X sudah dibayar.

```
Pengguna        Konsulaw (Laravel)           Midtrans            Bank / e-wallet
   |  klik Bayar     |                          |                      |
   |---------------->| 1. buat transaksi        |                      |
   |                 |------------------------->|                      |
   |                 |<---- snap_token ---------|                      |
   |<-- popup bayar -| 2. tampilkan Snap popup  |                      |
   |  pilih QRIS/VA, bayar ---------------------------------------->   |
   |                 |                          |<---- uang masuk -----|
   |                 |<-- 3. WEBHOOK (POST) ----|                      |
   |                 | 4. verifikasi signature, |                      |
   |                 |    status -> berlangsung |                      |
   |<-- ikon chat muncul                        |                      |
```

**Langkah menjalankannya (sandbox dulu, gratis dan uang palsu):**

1. **Buat akun Midtrans, masuk ke mode Sandbox.** Ambil dua kunci di dashboard: **Client Key** (boleh dipakai di browser) dan **Server Key** (RAHASIA, hanya di backend). Jangan commit Server Key ke GitHub.
2. **Pasang library resmi:** `composer require midtrans/midtrans-php`.
3. **Simpan kunci di `.env`**, lalu baca lewat `config/midtrans.php`:
   ```
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
   MIDTRANS_IS_PRODUCTION=false
   ```
4. **Backend membuat transaksi (Snap token).** Dilakukan saat pengguna menekan Bayar Sekarang (atau saat advokat menerima):
   ```php
   \Midtrans\Config::$serverKey    = config('midtrans.server_key');
   \Midtrans\Config::$isProduction = false;

   $params = [
     'transaction_details' => [
       'order_id'     => $pembayaran->order_id,   // unik, mis. KSL-12-1732512000
       'gross_amount' => $pembayaran->jumlah,     // dari snapshot tarif
     ],
     'customer_details' => [
       'first_name' => $user->nama,
       'email'      => $user->email,
       'phone'      => $user->no_hp,
     ],
     'expiry' => ['unit' => 'hours', 'duration' => 24], // batas waktu bayar
   ];
   $snapToken = \Midtrans\Snap::getSnapToken($params);
   $pembayaran->update(['snap_token' => $snapToken]);
   ```
5. **Frontend menampilkan popup** (view Blade halaman detail konsultasi):
   ```html
   <script src="https://app.sandbox.midtrans.com/snap/snap.js"
           data-client-key="{{ config('midtrans.client_key') }}"></script>
   <button id="pay-button">Bayar Sekarang</button>
   <script>
     document.getElementById('pay-button').onclick = () => {
       snap.pay('{{ $pembayaran->snap_token }}', {
         onSuccess: () => location.reload(),
         onPending: () => location.reload(),
         onError:   () => alert('Pembayaran gagal, coba lagi.')
       });
     };
   </script>
   ```
   (Untuk production, hapus `.sandbox` dari URL snap.js.) Callback `onSuccess` hanya untuk memperbarui tampilan. **Jangan** mengaktifkan chat dari callback ini karena bisa dimanipulasi pengguna.
6. **Endpoint webhook** (`POST /midtrans/notification`) menerima kabar dari Midtrans. Daftarkan URL-nya di pengaturan notifikasi dashboard Midtrans. Di dalamnya:
   - Buat `new \Midtrans\Notification()` untuk membaca `order_id`, `transaction_status`, `fraud_status`.
   - **Verifikasi signature:** `sha512(order_id + status_code + gross_amount + server_key)` harus sama dengan `signature_key` yang dikirim. Gunakan `gross_amount` persis sebagai string dari Midtrans (contoh `"280000.00"`), jangan di-cast ke angka karena hasil hash jadi tidak cocok.
   - Cari `pembayaran` berdasarkan `order_id`. Jika `settlement` (atau `capture` dengan `fraud_status = accept`): ubah `pembayaran.status = paid` dan `konsultasi.status = berlangsung`, kirim notifikasi. Jika `expire`: `expired` dan `kedaluwarsa`. Jika `cancel`/`deny`: `cancelled`/`failed`.
   - Abaikan jika sudah pernah diproses (idempotent), lalu balas HTTP 200.
   - Kecualikan route ini dari CSRF (`VerifyCsrfToken::$except`).
7. **Uji dengan simulator sandbox Midtrans.** Pilih metode (misalnya QRIS atau Virtual Account), lalu pakai simulator pembayaran Midtrans untuk "membayar" tanpa uang sungguhan.

**Kendala yang pasti ditemui: webhook tidak bisa masuk ke `127.0.0.1:8000`.** Midtrans ada di internet, sedangkan server lokal Anda tidak bisa dijangkau dari luar. Solusi:
- **Cara utama:** pakai tunnel (ngrok atau cloudflared) agar localhost punya URL publik sementara, lalu isi URL itu (`https://xxxx.ngrok-free.app/midtrans/notification`) di pengaturan notifikasi dashboard Midtrans. URL berubah tiap tunnel dinyalakan ulang, jadi perlu diperbarui.
- **Cadangan (sangat membantu saat demo):** saat pengguna membuka halaman detail konsultasi berstatus `menunggu_pembayaran`, server memanggil `\Midtrans\Transaction::status($order_id)` untuk menanyakan status langsung ke Midtrans, lalu memperbarui database. Dengan begitu chat tetap terbuka walau webhook gagal masuk. Webhook tetap jadi jalur utama saat sudah di-deploy.

**Pembagian peran kunci:**

| Siapa | Memegang | Boleh di mana |
|---|---|---|
| Client Key | Kunci publik | Browser |
| Server Key | Kunci rahasia | Hanya `.env` backend |
| Midtrans | Data kartu/rekening pengguna | Konsulaw tidak pernah menyimpannya |

Dokumentasi resmi untuk dirujuk di laporan: docs.midtrans.com dan repositori `midtrans/midtrans-php`.

### B3.5 Kebijakan pencegahan masalah pembayaran (sesuai keputusan: terima dulu, baru bayar)

| Risiko | Aturan |
|---|---|
| Pengguna tidak membayar setelah advokat menerima | **Batas waktu bayar 24 jam** (`expiry` Midtrans + `konsultasi.batas_bayar`). Lewat batas, status `kedaluwarsa`. Pengguna harus mengajukan ulang. |
| Pengguna "mengunci" banyak advokat tanpa membayar | Maksimal **3** konsultasi berstatus `menunggu_pembayaran` per pengguna dalam satu waktu. |
| Pengguna tidak sadar ada tagihan | Notifikasi saat advokat menerima, pengingat sekitar 6 jam sebelum kedaluwarsa, dan pemberitahuan ke advokat saat kedaluwarsa. |
| Advokat sudah dibayar tapi tidak merespons di chat | Jika tidak ada balasan advokat dalam **48 jam** setelah chat aktif, tombol **Laporkan Masalah** muncul bagi pengguna (masuk tabel `laporan`) dan admin menindaklanjuti. Refund otomatis berada di luar lingkup prototipe, cukup dicatat sebagai kebijakan. |
| Advokat tidak merespons pengajuan | Pengajuan `menunggu_tanggapan` yang tidak dijawab dalam **3x24 jam** otomatis `kedaluwarsa` dan pengguna diberi tahu untuk memilih advokat lain. Untuk pro bono, kuota tidak berkurang. |
| Pembayaran ganda | Satu konsultasi hanya punya satu `pembayaran` aktif (FK unik). Jika kedaluwarsa dan pengguna mengajukan ulang, buat konsultasi baru. |

(Perlu scheduler Laravel, `php artisan schedule:run`, untuk menjalankan pengecekan kedaluwarsa tiap beberapa menit.)

## B4. Halaman Profil Saya (masyarakat)

Navigasi masyarakat: Beranda, Cari Advokat, Kamus Hukum, **Konsultasi Saya**, Layanan Pro Bono (atau **Status Layanan**), Profil Saya.

Struktur tab (di mobile menjadi accordion):

### B4.1 Header profil
- Foto, nama, role "Masyarakat".
- **Badge status layanan** dari `users.status_ekonomi`: `Reguler` (abu-abu), `Verifikasi Diproses` (kuning), `Pro Bono Aktif` (hijau).
- Ringkasan: jumlah konsultasi berjalan, selesai.

### B4.2 Tab "Informasi Pribadi" (`users`)
| Field | Aturan |
|---|---|
| Nama lengkap | wajib |
| Email | wajib, unik |
| No. HP | wajib, format Indonesia |
| Domisili (provinsi, kota/kab) | wajib, memakai tabel `wilayah` (dipakai untuk rekomendasi advokat sewilayah) |
| Foto profil | opsional, JPG/PNG maks 2 MB |

### B4.3 Tab "Status Layanan" (pengganti halaman pro bono yang berdiri sendiri)

Tiga kondisi, **masing-masing wajib punya aksi lanjutan**:

| Kondisi | Tampilan | Tombol aksi |
|---|---|---|
| **Belum pernah mengajukan** (login pertama) | Penjelasan singkat: "Akun Anda berstatus Reguler. Jika kurang mampu, ajukan verifikasi untuk konsultasi gratis." Daftar dokumen yang diterima (SKTM, KIS, KKS, bukti pendapatan), format dan ukuran, kotak privasi (TR-04). | **Ajukan Verifikasi Pro Bono** (form upload). Tombol sekunder: **Cari Advokat** (jalur reguler). |
| **Pending** | Badge "Sedang Diproses", tanggal pengajuan, nama file yang diunggah. Teks: "Hasil muncul otomatis di notifikasi." | **Cari Advokat** (tetap boleh konsultasi reguler), **Batalkan Pengajuan** (opsional). Form upload disembunyikan. |
| **Disetujui (Pro Bono Aktif)** | Badge hijau, tanggal disetujui, (opsional) masa berlaku. | **Cari Advokat Pro Bono** (filter pro bono aktif), **Lihat Konsultasi Saya**. |
| **Ditolak** (kembali ke Reguler) | Banner merah + `catatan_admin` (alasan, mis. "dokumen buram"). | **Unggah Ulang Dokumen**, **Cari Advokat** (Reguler). |

Poin kunci: **setiap status punya tombol lanjut**, jadi tidak ada jalan buntu seperti di tampilan sekarang ("Telah Disetujui" tanpa langkah berikutnya).

Opsional namun disarankan: masa berlaku status Pro Bono (misalnya 12 bulan), karena kondisi ekonomi bisa berubah. Setelah lewat, status kembali ke Reguler dan pengguna dapat mengajukan ulang.

### B4.4 Tab "Konsultasi Saya" (riwayat, FR-12)

Daftar `konsultasi` milik pengguna, dengan filter status:

| Status | Label | Aksi |
|---|---|---|
| menunggu_tanggapan | Menunggu Tanggapan | Lihat, Batalkan |
| ditolak | Ditolak | Lihat alasan, Cari advokat lain |
| menunggu_pembayaran | Menunggu Pembayaran | **Bayar Sekarang**, Batalkan |
| berlangsung | Berlangsung | **Buka Chat** |
| selesai | Selesai | Lihat riwayat chat, **Beri Rating** (jika belum) |
| dibatalkan / kedaluwarsa | Dibatalkan / Kedaluwarsa | Ajukan ulang |

Setiap baris memuat: nama advokat, jenis layanan (Reguler/Pro Bono), judul kasus, tanggal, status. Detail konsultasi memuat kronologi, lampiran, status pembayaran (jika reguler), dan chat.

Rating (FR-13): 1 rating per konsultasi, setelah status `selesai`, bintang 1-5 dan komentar opsional. Ulasan tidak dapat diubah setelah dikirim (atau batas edit singkat).

### B4.5 Tab "Notifikasi" (`notifikasi`)
Daftar notifikasi: konsultasi diterima/ditolak, tagihan siap dibayar, pembayaran berhasil, status pro bono berubah, pesan baru. Tandai dibaca (`dibaca_pada`). Setiap notifikasi punya tautan langsung ke halaman terkait.

### B4.6 Tab "Laporan Saya" (opsional, FR-15)
Riwayat laporan yang dibuat pengguna terhadap profil advokat atau ulasan, lengkap dengan status tindak lanjut admin (`laporan.status`).

### B4.7 Tab "Keamanan Akun"
Ubah kata sandi, logout semua perangkat (opsional), nonaktifkan akun.

## B4A. Dasbor Masyarakat (halaman utama setelah login) & Form Ajukan Konsultasi

Catatan: "tombol utama Ajukan Konsultasi" diletakkan di **Dasbor/Beranda masyarakat** (halaman pertama setelah login). Profil Saya tetap berisi data pribadi dan riwayat seperti B4.

### B4A.1 Susunan halaman (atas ke bawah)

1. **Salam + ringkasan status** (nama, badge status layanan, konsultasi berjalan).
2. **Tombol utama besar: "Ajukan Konsultasi"** (CTA paling menonjol).
3. **Konsultasi berjalan** (jika ada): daftar singkat dengan tombol Buka Chat atau Bayar Sekarang. Letakkan di atas rekomendasi karena lebih mendesak.
4. **Rekomendasi advokat terverifikasi** (kartu, seperti tampilan Cari Advokat sekarang).
5. Pintasan: Kamus Hukum, Status Layanan Pro Bono.

### B4A.2 Rekomendasi advokat

- Hanya advokat `terverifikasi` dan sedang menerima konsultasi.
- Urutan rekomendasi (usulan sederhana untuk prototipe): (1) wilayah praktik sama dengan domisili pengguna, (2) rating tertinggi, (3) pro bono tersedia dahulu jika status pengguna Pro Bono. Tampilkan 4-6 kartu, ditambah tautan **Lihat Semua Advokat** ke halaman Cari Advokat.
- Isi kartu: nama dan gelar, badge Terverifikasi, bidang keahlian, wilayah, rating, sisa kuota pro bono, tarif.
- **Tombol pada kartu menjadi "Ajukan Konsultasi"** (menggantikan "Lihat Profil"). Klik tombol ini membuka form dengan advokat sudah terpilih.
- Kartu yang jenis layanannya tidak tersedia (misalnya kuota pro bono 0) tetap tampil, dengan label "Pro bono penuh". Tombol tetap aktif karena jalur Reguler masih bisa dipakai.

**Masukan penting soal menghilangkan "Lihat Profil" sepenuhnya.** Mengganti tombolnya itu bagus, tetapi sebaiknya jangan hilangkan akses ke detail. Kartu saat ini belum memuat **bio, pengalaman (tahun), nomor KTA publik, dan ulasan**. Padahal tujuan platform adalah transparansi kualifikasi, dan masyarakat ingin membaca ulasan sebelum memilih. Use case "Mengajukan Konsultasi" di proposal juga mensyaratkan pengguna sudah membuka detail profil advokat. Solusi yang tetap sesuai keinginan Anda:
- Tombol utama kartu = **Ajukan Konsultasi**.
- Tambahkan **tautan teks kecil "Lihat detail"** (atau nama advokat dibuat bisa diklik) yang membuka halaman/modal detail (bio, pengalaman, ulasan). Di halaman detail itu juga ada tombol Ajukan Konsultasi.
- Alternatif lebih ringan: kartu bisa di-expand (accordion) untuk memperlihatkan bio dan 2-3 ulasan terbaru.

### B4A.3 Dua pintu masuk ke form yang sama

Gunakan **satu halaman/form** dengan parameter opsional advokat: `/konsultasi/ajukan` atau `/konsultasi/ajukan?advokat={id}`.

| Pintu masuk | Kondisi awal form |
|---|---|
| **Tombol utama "Ajukan Konsultasi"** (di Dasbor) | Belum ada advokat terpilih, mulai dari **Langkah 1: Pilih Advokat** |
| **Tombol "Ajukan Konsultasi" di kartu/profil advokat** | Advokat **sudah terpilih**, langkah 1 dilewati, mulai dari **Langkah 2: Jenis Layanan**. Tampilkan kartu ringkas advokat di atas form dengan tautan **Ganti Advokat**. |

### B4A.4 Langkah-langkah form

**Langkah 1: Pilih Advokat** (hanya muncul dari tombol utama)
- Pilihan bidang hukum dan wilayah di atas daftar (bidang boleh diisi di awal, sekaligus menjadi kategori kasus).
- Daftar advokat terverifikasi yang cocok, urutan sama dengan rekomendasi. Setiap item: nama, bidang, wilayah, rating, tarif, sisa pro bono, tombol **Pilih**.
- Ada pencarian nama dan filter bidang/wilayah.
- Langkah 1 berada **sebelum** jenis layanan karena tarif dan kuota pro bono tergantung advokat yang dipilih.
- Tombol Lanjut baru aktif setelah memilih satu advokat.

**Langkah 2: Pilih Jenis Layanan** (sudah dijelaskan di B2.1 dan B2.2)
- Kartu Reguler (tarif advokat terpilih, "bayar setelah advokat menerima") dan kartu Pro Bono (sisa kuota, cek status akun).
- Cabang verifikasi ekonomi untuk Pro Bono mengikuti B2.2. Draf form tersimpan.

**Langkah 3: Isi Kasus**
- Kategori masalah (terisi otomatis jika sudah dipilih di langkah 1), judul singkat, kronologi, pertanyaan hukum, lampiran opsional (B2.6).

**Langkah 4: Tinjau & Kirim**
- Ringkasan: advokat, jenis layanan, tarif atau "Gratis (Pro Bono)", kronologi.
- Teks penting untuk Reguler: "Anda baru membayar **setelah** advokat menerima permintaan ini."
- Tombol **Kirim Pengajuan**.

Tampilkan indikator langkah (misalnya 1 / 4). Untuk pintu masuk dari kartu advokat, tampilkan sebagai 3 langkah (mulai dari Jenis Layanan).

### B4A.5 Aturan teknis

- Parameter `advokat` di URL **tidak boleh dipercaya begitu saja**. Server memvalidasi bahwa advokat itu ada, `terverifikasi`, dan sedang menerima konsultasi. Jika tidak, kembali ke Langkah 1 dengan pesan.
- Pengguna yang belum login dan menekan Ajukan Konsultasi diarahkan ke login, lalu dikembalikan ke URL form yang sama (intended URL).
- Cegah duplikasi: jika pengguna sudah punya konsultasi aktif (`menunggu_tanggapan`, `menunggu_pembayaran`, `berlangsung`) dengan advokat yang sama, tampilkan "Anda sudah memiliki konsultasi dengan advokat ini" dengan tautan ke konsultasi tersebut.
- Simpan draf (session atau tabel draf) agar pengisian tidak hilang saat pengguna pergi ke halaman verifikasi pro bono lalu kembali.
- Pada mobile, setiap langkah satu layar penuh dengan tombol Kembali dan Lanjut yang besar (NFR-04).

## B5. Perubahan pada profil publik advokat (yang dilihat masyarakat)

- Tombol utama **Ajukan Konsultasi** (menggantikan form verifikasi). Klik langsung membuka form dengan advokat ini sudah terpilih (lihat B4A.3).
- Di bawah tombol: ringkasan "Reguler Rp X / sesi" dan "Pro Bono: sisa N kuota".
- Pengunjung (belum login) yang menekan tombol diarahkan ke login, lalu dikembalikan ke profil yang sama.
- Pengguna yang sudah punya konsultasi aktif dengan advokat tersebut melihat tombol **Buka Konsultasi** (mencegah duplikasi).

## B6. Hak akses dan keamanan (FR-16, NFR-01, NFR-05)

- Dokumen verifikasi ekonomi: hanya pemilik dan admin. Disimpan di penyimpanan privat.
- Lampiran kasus dan chat: hanya masyarakat pemilik konsultasi dan advokat tujuan. **Admin tidak dapat membuka**, kecuali dalam penanganan laporan yang sah (dan harus tercatat).
- Masyarakat A tidak boleh membuka konsultasi/pembayaran/dokumen masyarakat B (uji langsung lewat URL).
- Pembayaran: jangan simpan data kartu apa pun; hanya referensi transaksi Midtrans.

## B7. Checklist penerimaan (Bagian B)

- [ ] Profil advokat menampilkan tombol Ajukan Konsultasi, bukan form upload verifikasi.
- [ ] Form pengajuan menampilkan pilihan Reguler dan Pro Bono dengan tarif dan sisa kuota.
- [ ] Memilih Pro Bono saat status Reguler/belum pernah mengajukan mengarahkan ke verifikasi dan draf form tidak hilang.
- [ ] Saat Pending, pengguna masih bisa memilih jalur Reguler.
- [ ] Setelah disetujui admin, halaman status menampilkan tombol Cari Advokat Pro Bono dan notifikasi terkirim.
- [ ] Status ditolak menampilkan alasan dan tombol unggah ulang.
- [ ] Dokumen kasus tidak pernah muncul di panel verifikasi admin.
- [ ] Pengajuan pro bono saat kuota 0 ditolak dengan pesan yang sesuai.
- [ ] Menerima permintaan pro bono mengurangi kuota; menolak tidak.
- [ ] Reguler: setelah advokat menerima, status menjadi menunggu_pembayaran dan chat belum bisa dibuka.
- [ ] Pembayaran sandbox Midtrans sukses mengubah status menjadi berlangsung lewat webhook; chat terbuka.
- [ ] Webhook dengan signature salah ditolak; notifikasi ganda tidak membuat efek ganda.
- [ ] Pembayaran kedaluwarsa mengubah status menjadi kedaluwarsa.
- [ ] Rating hanya bisa diberikan sekali, setelah status selesai.
- [ ] Ikon/tombol Buka Chat muncul hanya saat status berlangsung, baik Reguler maupun Pro Bono.
- [ ] Sebelum syarat terpenuhi, tombol chat nonaktif dengan teks alasan yang sesuai (menunggu tanggapan / menunggu pembayaran).
- [ ] Membuka URL chat langsung saat status belum berlangsung mengembalikan 403.
- [ ] Form pengajuan Reguler tetap mewajibkan kronologi; lampiran opsional di kedua jalur.
- [ ] Setelah status selesai, chat menjadi read-only.
- [ ] Masyarakat tidak dapat mengakses data konsultasi pengguna lain.
- [ ] Dasbor masyarakat menampilkan tombol utama Ajukan Konsultasi dan rekomendasi advokat terverifikasi di bawahnya.
- [ ] Tombol pada kartu advokat berbunyi Ajukan Konsultasi dan membuka form dengan advokat terpilih (langkah Pilih Advokat dilewati).
- [ ] Tombol utama membuka form mulai dari Pilih Advokat.
- [ ] Parameter advokat di URL yang tidak valid/tidak terverifikasi ditolak.
- [ ] Pengguna belum login diarahkan ke login lalu kembali ke form yang sama.
- [ ] Pengajuan duplikat ke advokat yang sama (konsultasi aktif) dicegah.
- [ ] Pembayaran sandbox Midtrans: Server Key tidak muncul di sisi browser/repo.
- [ ] Pengajuan tanpa respons 3x24 jam dan pembayaran lewat 24 jam menjadi kedaluwarsa otomatis.
- [ ] Maksimal 3 konsultasi menunggu_pembayaran per pengguna diberlakukan.

## B8. Urutan pengerjaan yang disarankan (Bagian B)

1. Ubah profil publik advokat: tombol Ajukan Konsultasi + form dua langkah (jenis layanan, isi kasus).
2. Pindahkan form verifikasi ke tab Status Layanan; tambahkan 4 kondisi beserta tombol lanjutnya.
3. Logika cabang pro bono (cek status, kuota, simpan draf).
4. Halaman Konsultasi Saya + status konsultasi + notifikasi.
5. Penerimaan/penolakan oleh advokat (jalur pro bono dulu, kuota berkurang saat diterima).
6. Integrasi Midtrans sandbox: tabel `pembayaran`, Snap, webhook, status menunggu_pembayaran.
7. Chat, rating, dan laporan.
8. Pengujian alur lintas peran (masyarakat, advokat, admin).