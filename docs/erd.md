# erd.md — ERD Konsulaw

> Sumber: Proposal Kelompok A (Bab 4.3, 13 tabel). Nama tabel sesuai proposal. **Kolom = usulan**, sesuaikan dengan gambar ERD final.

## Diagram (Mermaid)

```mermaid
erDiagram
    wilayah ||--o{ users : "domisili"
    wilayah ||--o{ advokat_wilayah : ""
    wilayah ||--o{ wilayah : "parent"
    users ||--o| profil_advokat : "punya"
    users ||--o{ verifikasi_ekonomi : "mengajukan"
    users ||--o{ notifikasi : "menerima"
    users ||--o{ konsultasi : "masyarakat"
    users ||--o{ konsultasi : "advokat"
    users ||--o{ pesan_konsultasi : "mengirim"
    users ||--o{ laporan : "melapor"
    profil_advokat ||--o{ dokumen_advokat : "punya"
    profil_advokat ||--o{ advokat_bidang : ""
    profil_advokat ||--o{ advokat_wilayah : ""
    bidang_hukum ||--o{ advokat_bidang : ""
    bidang_hukum ||--o{ konsultasi : "kategori"
    konsultasi ||--o{ pesan_konsultasi : "berisi"
    konsultasi ||--o| ulasan : "dinilai"

    users {
        bigint id PK
        string nama
        string email UK
        string no_hp
        string password
        enum role "masyarakat|advokat|admin"
        enum status_akun "aktif|nonaktif"
        bigint wilayah_id FK
    }
    wilayah {
        bigint id PK
        string nama
        enum tingkat "provinsi|kota|kecamatan|desa"
        bigint parent_id FK
    }
    verifikasi_ekonomi {
        bigint id PK
        bigint user_id FK
        string dokumen_path
        enum status "pending|pro_bono|reguler"
        text catatan_admin
        bigint diperiksa_oleh FK
    }
    notifikasi {
        bigint id PK
        bigint user_id FK
        string judul
        text isi
        string tipe
        boolean sudah_dibaca
    }
    profil_advokat {
        bigint id PK
        bigint user_id FK
        string no_kta
        string no_bas
        text bio
        int pengalaman_tahun
        decimal tarif_konsultasi
        int kuota_pro_bono
        int kuota_terpakai
        enum status_verifikasi "pending|terverifikasi|ditolak"
        decimal rating_rata
    }
    dokumen_advokat {
        bigint id PK
        bigint profil_advokat_id FK
        enum jenis "ijazah|bas|kta"
        string file_path
        text catatan_admin
    }
    bidang_hukum {
        bigint id PK
        string nama
        text deskripsi
    }
    advokat_bidang {
        bigint profil_advokat_id FK
        bigint bidang_hukum_id FK
    }
    advokat_wilayah {
        bigint profil_advokat_id FK
        bigint wilayah_id FK
    }
    konsultasi {
        bigint id PK
        bigint masyarakat_id FK
        bigint advokat_id FK
        bigint bidang_hukum_id FK
        enum jenis_layanan "reguler|pro_bono"
        text kronologi
        enum status "menunggu|diterima|ditolak|berlangsung|selesai|dibatalkan"
        text alasan_penolakan
    }
    pesan_konsultasi {
        bigint id PK
        bigint konsultasi_id FK
        bigint pengirim_id FK
        text isi
        timestamp created_at
    }
    ulasan {
        bigint id PK
        bigint konsultasi_id FK
        tinyint rating "1-5"
        text komentar
    }
    laporan {
        bigint id PK
        bigint pelapor_id FK
        string target_tipe "advokat|layanan|ulasan"
        bigint target_id
        text alasan
        enum status "baru|diproses|selesai"
        bigint ditangani_oleh FK
    }
```

## Penjelasan per modul

### Modul Akun
- **users**: tabel utama semua pengguna. Pembeda peran lewat kolom `role`.
- **verifikasi_ekonomi**: dokumen + hasil pemeriksaan status ekonomi (pending / pro_bono / reguler) untuk akses pro bono.
- **notifikasi**: pemberitahuan dalam aplikasi (status konsultasi, hasil verifikasi).
- **wilayah**: referensi wilayah, dipakai buat domisili user dan wilayah praktik advokat.

### Modul Advokat
- **profil_advokat**: data khusus advokat (KTA, BAS, pengalaman, tarif, kuota pro bono).
- **dokumen_advokat**: berkas verifikasi (ijazah, BAS, KTA) yang diperiksa admin.
- **bidang_hukum**: referensi bidang keahlian.
- **advokat_bidang** dan **advokat_wilayah**: tabel pivot many-to-many. Satu advokat bisa banyak bidang dan banyak wilayah.

### Modul Konsultasi
- **konsultasi**: pengajuan masyarakat ke advokat (bidang, jenis layanan, kronologi, status).
- **pesan_konsultasi**: isi chat selama konsultasi.
- **ulasan**: rating 1-5 + komentar, **maksimal satu per konsultasi**.
- **laporan**: pengaduan terhadap profil, layanan, atau ulasan untuk ditindak admin.

## Relasi utama
| Relasi | Kardinalitas |
|---|---|
| users — profil_advokat | 1 : 0..1 (hanya role advokat) |
| profil_advokat — dokumen_advokat | 1 : N |
| profil_advokat — bidang_hukum | N : M (via advokat_bidang) |
| profil_advokat — wilayah | N : M (via advokat_wilayah) |
| users — verifikasi_ekonomi | 1 : N |
| users (masyarakat) — konsultasi | 1 : N |
| users (advokat) — konsultasi | 1 : N |
| konsultasi — pesan_konsultasi | 1 : N |
| konsultasi — ulasan | 1 : 0..1 |

## Aturan integritas (usulan)
- `ulasan.konsultasi_id` UNIQUE, dan hanya boleh dibuat kalau `konsultasi.status = selesai`.
- `profil_advokat.kuota_terpakai <= kuota_pro_bono`.
- `konsultasi.jenis_layanan = pro_bono` hanya boleh kalau `verifikasi_ekonomi.status = pro_bono`.
- Pivot `advokat_bidang` dan `advokat_wilayah` pakai composite unique.

## Tabel tambahan usulan (DI LUAR 13 tabel proposal)
Dibutuhkan fitur edukasi (LAW-16, LAW-17). Kalau mau tetap 13 tabel, ini harus diputuskan dulu sama tim.

```mermaid
erDiagram
    kamus_hukum {
        bigint id PK
        string istilah
        text arti_awam
        text contoh
        bigint dibuat_oleh FK
    }
    peraturan {
        bigint id PK
        string judul
        string nomor_tahun
        bigint bidang_hukum_id FK
        text ringkasan_awam
        string file_pdf_path
        bigint dibuat_oleh FK
    }
```