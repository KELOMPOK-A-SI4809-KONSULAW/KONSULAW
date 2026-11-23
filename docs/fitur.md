# fitur.md — Penjelasan Fitur Konsulaw (sesuai Jira KELOMPOK A-PPL-KONSULAW)

> **Sprint 1:** 28 Okt - 24 Nov (18 work item). Data dari screenshot Backlog.
> PIC dibaca dari inisial avatar (**inferensi**, cek lagi di Jira): Z = Zuhdi, DN = Dylan, MF = Faris, FI = Febriyan. Avatar foto tidak terbaca jelas.
> Nama epic terpotong di screenshot, jadi nama di bawah adalah **perkiraan**.

## Ringkasan

| Key | Fitur | Epic | Status | SP | PIC |
|---|---|---|---|---|---|
| LAW-1 | Setup Repositori & Struktur Awal | Fondasi Aplikasi | Done | 2 | Z |
| LAW-2 | Migration Database & Seeder | Fondasi Aplikasi | Done | 3 | Z |
| LAW-18 | Layout Utama & Navigasi Responsif | Fondasi Aplikasi | Done | 3 | DN |
| LAW-3 | Autentikasi & Middleware Role | Fondasi Aplikasi | Done | 8 | Z |
| LAW-4 | Pengelolaan Profil Masyarakat | Direktori dan Pencarian | In Progress | 3 | (foto) |
| LAW-5 | Pengelolaan Profil Profesional Advokat | Profil dan Verifikasi | In Progress | 5 | (foto) |
| LAW-6 | Pengaturan Ketersediaan & Kuota Pro Bono | Layanan Bantuan | In Progress | 5 | (foto) |
| LAW-7 | Upload Dokumen Legalitas Advokat | Profil dan Verifikasi | In Progress | 5 | (foto) |
| LAW-8 | Dashboard Admin Verifikasi Advokat | Profil dan Verifikasi | In Progress | 5 | (foto) |
| LAW-9 | Notifikasi Hasil Verifikasi Advokat | Profil dan Verifikasi | In Progress | 3 | (foto) |
| LAW-10 | Pengajuan Status Pro Bono oleh Masyarakat | Layanan Bantuan | Done | 5 | MF |
| LAW-11 | Pemeriksaan Dokumen Ekonomi oleh Admin | Layanan Bantuan | In Progress | 5 | MF |
| LAW-12 | Notifikasi Perubahan Status Ekonomi | Layanan Bantuan | In Progress | 3 | MF |
| LAW-13 | Halaman Beranda Responsif | Direktori dan Pencarian | In Progress | 3 | FI |
| LAW-14 | Direktori Advokat Terverifikasi | Direktori dan Pencarian | In Progress | 5 | FI |
| LAW-15 | Pencarian, Filter, & Detail Profil Advokat | Direktori dan Pencarian | In Progress | 5 | FI |
| LAW-16 | Halaman Kamus Hukum & Direktori Peraturan | Edukasi dan Informasi | In Progress | 5 | DN |
| LAW-17 | CRUD Konten Edukasi oleh Admin | Edukasi dan Informasi | In Progress | 5 | DN |

Total: **83 SP**. Done: **21 SP** (LAW-1, 2, 3, 10, 18). Sisa: **62 SP** (13 item).

---

## Epic 1 — Fondasi Aplikasi

### LAW-1 — Setup Repositori, Laravel, Tailwind, Struktur Awal
- **Tugas:** inisialisasi repo GitHub, install Laravel + Tailwind, rapikan struktur folder (MVC + Service Layer), konfigurasi `.env`, aturan branch.
- **Hasil:** semua anggota bisa clone dan jalanin project dengan konfigurasi sama.
- **Terkait:** TR-02, TR-08.

### LAW-2 — Migration Database & Seeder
- **Tugas:** bikin migration untuk tabel sesuai ERD, relasi FK, seeder data awal (admin default, wilayah, bidang hukum).
- **Hasil:** database bisa dibangun ulang dengan `migrate --seed`.
- **Terkait:** TR-03, ERD.

### LAW-18 — Layout Utama & Navigasi Responsif
- **Tugas:** layout master Blade (header, footer, navbar), navigasi yang nyaman di HP dan desktop, komponen UI dasar.
- **Hasil:** kerangka tampilan yang dipakai semua halaman.
- **Terkait:** NFR-04, NFR-06.

### LAW-3 — Registrasi, Login, Logout, Pemulihan Kata Sandi, Middleware Role
- **Tugas:** registrasi masyarakat dan advokat, login/logout, reset password, middleware pembatas akses per role (masyarakat / advokat / admin), hashing password.
- **Hasil:** akses halaman terkunci sesuai peran.
- **Terkait:** FR-01, FR-16, NFR-01.
- **Catatan:** 8 SP, item terbesar dan fondasi semua fitur lain.

---

## Epic 2 — Profil dan Verifikasi (Advokat)

### LAW-5 — Pengelolaan Profil Profesional Advokat
- **Tugas:** form dan edit profil advokat: bio, pengalaman, tarif konsultasi, nomor KTA/BAS, bidang keahlian, wilayah praktik.
- **Hasil:** profil lengkap yang nanti tampil di direktori.
- **Terkait:** FR-02, tabel `profil_advokat`, `advokat_bidang`, `advokat_wilayah`.

### LAW-7 — Upload Dokumen Legalitas Advokat
- **Tugas:** upload Ijazah SH, BAS, dan KTA, validasi tipe dan ukuran file, simpan di storage **privat** (tidak bisa diakses publik langsung).
- **Hasil:** berkas siap diperiksa admin, status awal *pending*.
- **Terkait:** FR-03, TR-04, tabel `dokumen_advokat`.

### LAW-8 — Dashboard Admin untuk Verifikasi Advokat
- **Tugas:** antrean verifikasi, preview dokumen, tombol setujui/tolak (tolak wajib alasan), ubah status advokat jadi *Terverifikasi* + badge.
- **Hasil:** hanya advokat terverifikasi yang tampil publik.
- **Terkait:** FR-03, use case "Melakukan Verifikasi Dokumen Advokat".

### LAW-9 — Notifikasi Hasil Verifikasi Advokat
- **Tugas:** kirim notifikasi dalam aplikasi ke advokat saat disetujui atau ditolak (beserta alasan revisi).
- **Hasil:** advokat tahu status dan apa yang harus diperbaiki.
- **Terkait:** TR-06, tabel `notifikasi`.

---

## Epic 3 — Layanan Bantuan (Pro Bono)

### LAW-6 — Pengaturan Ketersediaan Layanan & Kuota Pro Bono
- **Tugas:** advokat atur kuota pro bono bulanan dan status ketersediaan, sistem hitung kuota terpakai dan sisa.
- **Hasil:** angka kuota yang jadi dasar validasi pro bono.
- **Terkait:** FR-09, `profil_advokat.kuota_pro_bono`.

### LAW-10 — Pengajuan Status Pro Bono oleh Masyarakat
- **Tugas:** form upload dokumen pendukung kondisi ekonomi, status awal *Pending*.
- **Hasil:** masyarakat masuk antrean pemeriksaan.
- **Terkait:** FR-04, tabel `verifikasi_ekonomi`.

### LAW-11 — Pemeriksaan Dokumen Ekonomi oleh Admin
- **Tugas:** halaman admin untuk periksa dokumen, tetapkan status **Pending / Pro Bono / Reguler**, beri catatan.
- **Hasil:** status yang menentukan akses jalur pro bono.
- **Terkait:** FR-04, FR-15.

### LAW-12 — Notifikasi Perubahan Status Ekonomi
- **Tugas:** notifikasi ke masyarakat tiap status ekonomi berubah.
- **Hasil:** masyarakat tahu apakah sudah bisa pakai pro bono.
- **Terkait:** TR-06.

---

## Epic 4 — Direktori dan Pencarian

### LAW-4 — Pengelolaan Profil Masyarakat
- **Tugas:** lihat dan edit profil masyarakat (nama, kontak, domisili/wilayah).
- **Hasil:** data dasar pencari keadilan siap dipakai saat konsultasi.
- **Terkait:** FR-02.
- **Catatan:** di Jira epic-nya tertulis "Direktori dan Penc...". Secara isi lebih cocok ke profil/akun, cek lagi.

### LAW-13 — Halaman Beranda Responsif
- **Tugas:** beranda sesuai mockup: hero + ajakan, kolom pencarian (wilayah, bidang hukum), kata kunci populer, 3 pintu masuk (Direktori, Kamus & Peraturan, Konsultasi Awal), banner pro bono, pernyataan batasan layanan.
- **Hasil:** halaman pertama untuk pengunjung dan masyarakat.
- **Terkait:** mockup 4.5.1, NFR-04.

### LAW-14 — Direktori Advokat Terverifikasi
- **Tugas:** daftar kartu advokat terverifikasi (nama, bidang, wilayah, rating, tarif, badge pro bono), pagination.
- **Hasil:** pengunjung tanpa login tetap bisa lihat direktori.
- **Terkait:** FR-05, mockup Kartu Advokat.

### LAW-15 — Pencarian, Filter, dan Detail Profil Advokat
- **Tugas:** pencarian dan filter (wilayah, bidang, rating, ketersediaan pro bono), halaman detail profil lengkap + tombol "Ajukan Konsultasi".
- **Hasil:** masyarakat bisa menemukan advokat yang pas dan lanjut ke pengajuan.
- **Terkait:** FR-05, FR-06, NFR-02 (target muat < 3 detik).

---

## Epic 5 — Edukasi dan Informasi

### LAW-16 — Halaman Kamus Hukum dan Direktori Peraturan
- **Tugas:** halaman publik kamus istilah hukum (bahasa awam) dan daftar peraturan per bidang hukum + ringkasan sederhana, dengan pencarian.
- **Hasil:** masyarakat paham istilah dan dasar hukum sebelum konsultasi.
- **Terkait:** FR-14, SDG 16.10.

### LAW-17 — CRUD Konten Edukasi oleh Admin
- **Tugas:** admin tambah, ubah, hapus istilah kamus dan data peraturan (judul, bidang, ringkasan, PDF).
- **Hasil:** konten edukasi bisa diperbarui tanpa ubah kode.
- **Terkait:** FR-14.

---

## Belum ada di Jira (rencana Sprint 2 di proposal)
Pengajuan konsultasi, tanggapan advokat (terima/tolak), validasi pro bono saat konsultasi, chat, riwayat dan status konsultasi, rating dan ulasan, moderasi akun, pelaporan, dan pernyataan batasan layanan. Item-item ini belum jadi ticket di backlog (backlog di bawah sprint kosong).