# paymentgateway.md — Prompt: Fitur Pembayaran Konsultasi Reguler (Midtrans Snap, Sandbox)

## Peran
Kamu adalah senior Laravel developer. Implementasikan fitur pembayaran konsultasi reguler pada proyek **Konsulaw** menggunakan **Midtrans Snap (mode Sandbox)**. Kerjakan bertahap, jelaskan perubahan singkat per langkah, dan jangan mengubah fitur di luar lingkup ini.

## Konteks proyek
- Konsulaw: platform web penghubung masyarakat dan advokat terverifikasi (SDG 16). Peran: masyarakat, advokat, admin.
- Stack: Laravel (PHP 8.2+), Blade, Tailwind CSS, MySQL/MariaDB, arsitektur MVC + Service Layer.
- Tabel relevan (sudah ada): `users`, `profil_advokat` (kolom `tarif_konsultasi`), `konsultasi` (kolom `jenis_layanan`, `status`), `notifikasi`.
- Status konsultasi: `menunggu`, `diterima`, `ditolak`, `berlangsung`, `selesai`, `dibatalkan`.
- UI mengikuti `baselineui.md` (token Tailwind, badge status ikon + teks, halaman sensitif pakai class `calm`).

## Tujuan
Konsultasi **reguler** hanya boleh lanjut ke chat setelah pembayaran **berhasil** (diverifikasi dari webhook Midtrans). Konsultasi **pro bono** tidak melalui pembayaran.

## Alur bisnis
1. Masyarakat mengajukan konsultasi reguler, status `menunggu`.
2. Advokat menerima, status `diterima` (artinya: menunggu pembayaran).
3. Masyarakat melihat tombol **Bayar Sekarang**, sistem membuat record pembayaran + Snap token, popup Snap muncul.
4. Midtrans mengirim notifikasi ke webhook. Jika **lolos signature** dan status sukses, pembayaran jadi `lunas` dan konsultasi otomatis jadi `berlangsung` (chat terbuka).
5. Jika pembayaran gagal/kedaluwarsa, konsultasi tetap `diterima`, tombol bayar bisa dipakai lagi (buat transaksi baru dengan `order_id` baru).
6. Jika advokat menolak atau konsultasi dibatalkan sebelum bayar, tidak ada transaksi aktif.
7. Jalur pro bono: lewati seluruh alur pembayaran.

## Lingkup yang dikerjakan
1. Install `midtrans/midtrans-php`, buat `config/midtrans.php`, variabel `.env` (`MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, `MIDTRANS_IS_PRODUCTION=false`). Tambahkan `.env.example` tanpa nilai asli.
2. **Migration tabel `pembayaran`:**
   - `id`, `konsultasi_id` (FK, index), `order_id` (unique), `gross_amount` (unsigned integer, rupiah), `snap_token` (nullable), `status` enum(`pending`,`lunas`,`gagal`,`kedaluwarsa`,`dibatalkan`), `payment_type` (nullable), `transaction_id` (nullable), `fraud_status` (nullable), `raw_notification` (json nullable), `paid_at` (nullable), timestamps.
   - Satu konsultasi boleh punya banyak percobaan pembayaran, tapi hanya satu yang `lunas`.
3. **Model `Pembayaran`** + relasi ke `Konsultasi`.
4. **`PembayaranService`** (Service Layer) dengan method:
   - `buatTransaksi(Konsultasi $k)`: validasi, buat `order_id` unik, panggil `Snap::getSnapToken`, simpan record `pending`.
   - `prosesNotifikasi(array $payload)`: verifikasi, mapping status, update idempotent.
5. **Controller + route:**
   - `POST /konsultasi/{id}/bayar` (auth, role masyarakat): buat transaksi, kembalikan token / tampilkan halaman bayar.
   - `POST /midtrans/notification` (publik, **tanpa CSRF**, tanpa auth): webhook.
6. **Blade:** tombol "Bayar Sekarang" di detail konsultasi (hanya muncul jika reguler + `diterima` + belum lunas), memuat `snap.js` sandbox dengan client key dari config, callback `onSuccess/onPending/onError`.
7. **Badge status pembayaran** di UI (pending, lunas, gagal, kedaluwarsa) sesuai `baselineui.md`.
8. Notifikasi dalam aplikasi ke masyarakat dan advokat saat pembayaran lunas.

## Aturan bisnis dan validasi
- Tombol/endpoint bayar hanya untuk: pemilik konsultasi (masyarakat), `jenis_layanan = reguler`, `status = diterima`, belum ada pembayaran `lunas`. Selain itu tolak (403/422).
- `gross_amount` = `profil_advokat.tarif_konsultasi` **dibaca dari database di server**, bukan dari request klien. Harus integer > 0.
- `order_id` unik dan tidak dipakai ulang, format contoh `KNS-{konsultasi_id}-{timestamp}`.
- Opsional: batas waktu pembayaran lewat parameter `expiry` Snap, status `kedaluwarsa` saat notifikasi expire.
- Pemetaan status notifikasi:
  - `settlement`, atau `capture` dengan `fraud_status = accept` -> `lunas`
  - `pending` -> `pending`
  - `deny`, `failure` -> `gagal`
  - `cancel` -> `dibatalkan`
  - `expire` -> `kedaluwarsa`
  - `capture` dengan `fraud_status = challenge` -> tetap `pending`, jangan buka chat
- Ketika `lunas`: update `konsultasi.status` menjadi `berlangsung` **di dalam satu DB transaction** bersama update pembayaran.

## Keamanan (wajib)
- Verifikasi `signature_key` webhook: `hash('sha512', order_id . status_code . gross_amount . SERVER_KEY)`, bandingkan dengan `hash_equals`. Gunakan `gross_amount` sebagai **string persis dari notifikasi**. Jika tidak cocok: abaikan dan balas 403, jangan ubah data.
- Setelah signature valid, **cek ulang lewat Status API** (`\Midtrans\Transaction::status($order_id)`) dan gunakan hasilnya sebagai acuan akhir.
- Handler **idempotent**: notifikasi yang sama dua kali tidak menggandakan efek. **Jangan menurunkan status** (misal dari `lunas` kembali ke `pending` karena notifikasi tidak berurutan).
- Server Key hanya di `.env` dan backend. Jangan di Blade, JS, log, atau repo.
- Callback `onSuccess` di browser **bukan** dasar membuka chat. Hanya status dari webhook.
- Cocokkan `gross_amount` notifikasi dengan `pembayaran.gross_amount`. Jika beda, tolak dan catat.
- Simpan payload mentah di `raw_notification` untuk audit, tanpa data kartu.
- Rate limit ringan pada endpoint bayar.

## Cara uji (sandbox)
Dokumentasikan di `README` bagian "Uji Pembayaran":
1. Ambil key sandbox dari dashboard Midtrans, isi `.env`.
2. Jalankan tunnel (ngrok/cloudflared), isi **Payment Notification URL** di dashboard Midtrans dengan `https://<tunnel>/midtrans/notification`.
3. Kartu sukses: `4811 1111 1111 1114`, CVV `123`, expiry bulan apa saja dan tahun masa depan, OTP/3DS `112233`.
4. Kartu ditolak: `4911 1111 1111 1113`.
5. Virtual Account dan e-wallet/QRIS tidak otomatis lunas, simulasikan lewat simulator Midtrans.

## Kriteria diterima (acceptance criteria)
- [ ] Konsultasi reguler berstatus `diterima` menampilkan tombol bayar, pro bono tidak.
- [ ] Popup Snap muncul dengan nominal sama dengan tarif advokat.
- [ ] Bayar sukses -> pembayaran `lunas`, konsultasi `berlangsung`, chat terbuka, notifikasi terkirim.
- [ ] Bayar gagal / kedaluwarsa -> konsultasi tetap `diterima`, bisa coba lagi.
- [ ] Webhook dengan signature salah ditolak dan tidak mengubah data.
- [ ] Notifikasi duplikat dan tidak berurutan tidak merusak status.
- [ ] Pengguna lain tidak bisa membayar / melihat pembayaran konsultasi orang lain.
- [ ] Server Key tidak muncul di kode frontend maupun repo.
- [ ] Feature test (PHPUnit) untuk: mapping status, signature valid/invalid, idempotensi, otorisasi.

## Di luar lingkup
Refund, pembayaran berulang, payout ke advokat, mode production, pembagian komisi, dan integrasi pembayaran untuk jalur pro bono.

## Output yang diminta
1. Daftar file yang dibuat/diubah.
2. Kode lengkap (migration, model, service, controller, route, Blade, test).
3. Langkah menjalankan dan mengujinya.
4. Catatan asumsi yang kamu ambil. Jika ada ambiguitas yang menghalangi, tanyakan sebelum menulis kode.