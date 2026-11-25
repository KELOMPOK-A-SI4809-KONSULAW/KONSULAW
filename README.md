# Spesifikasi Halaman Profil Advokat (Setelah Login) — Konsulaw

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