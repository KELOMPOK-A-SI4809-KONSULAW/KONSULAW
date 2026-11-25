<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilAdvokat extends Model
{
    use HasFactory;

    protected $table = 'profil_advokat';

    protected $fillable = [
        'user_id',
        'no_kta',
        'no_bas',
        'no_lisensi',
        'pengalaman',
        'tahun_mulai_praktik',
        'biografi',
        'nama_organisasi',
        'tarif_konsultasi',
        'kuota_probono',
        'ketersediaan',
        'status_verifikasi',
        'catatan_verifikasi',
    ];

    /**
     * Hitung persentase kelengkapan profil (0-100).
     * Bobot: 4 komponen @ 25%
     */
    public function persentaseKelengkapan(): int
    {
        $skor = 0;

        // 1. Informasi pribadi: nama, no_hp, wilayah_id
        $user = $this->user;
        $namaOk   = !empty($user?->nama ?? $user?->name);
        $hpOk     = !empty($user?->no_hp);
        $wilayahOk = !empty($user?->wilayah_id);
        if ($namaOk && $hpOk && $wilayahOk) {
            $skor += 25;
        }

        // 2. Data profesional: no_kta, no_bas, pengalaman (atau tahun_mulai_praktik)
        $ktaOk  = !empty($this->no_kta);
        $basOk  = !empty($this->no_bas);
        $pengOk = !empty($this->pengalaman) || !empty($this->tahun_mulai_praktik);
        if ($ktaOk && $basOk && $pengOk) {
            $skor += 25;
        }

        // 3. Bidang & wilayah: minimal 1 bidang dan 1 wilayah
        $hasBidang  = $this->bidangHukum()->exists();
        $hasWilayah = $this->wilayahPraktik()->exists();
        if ($hasBidang && $hasWilayah) {
            $skor += 25;
        }

        // 4. Dokumen: ketiga dokumen (KTA, BAS, Ijazah) sudah diunggah
        $jenisDokumen = $this->dokumen()->pluck('jenis')->toArray();
        $docsOk = in_array('KTA', $jenisDokumen)
               && in_array('BAS', $jenisDokumen)
               && in_array('Ijazah', $jenisDokumen);
        if ($docsOk) {
            $skor += 25;
        }

        return $skor;
    }

    /**
     * Apakah profil sudah bisa dikirim untuk verifikasi?
     */
    public function siapDikirim(): bool
    {
        return $this->persentaseKelengkapan() === 100;
    }

    protected function casts(): array
    {
        return [
            'tahun_mulai_praktik' => 'integer',
            'tarif_konsultasi'    => 'decimal:2',
            'kuota_probono'       => 'integer',
            'ketersediaan'        => 'boolean',
        ];
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Dokumen Advokat
     */
    public function dokumen()
    {
        return $this->hasMany(DokumenAdvokat::class, 'profil_advokat_id');
    }

    /**
     * Relasi Many-to-Many ke Wilayah Praktik
     */
    public function wilayahPraktik()
    {
        return $this->belongsToMany(Wilayah::class, 'advokat_wilayah', 'profil_advokat_id', 'wilayah_id')
                    ->withTimestamps();
    }

    /**
     * Relasi Many-to-Many ke Bidang Hukum
     */
    public function bidangHukum()
    {
        return $this->belongsToMany(BidangHukum::class, 'advokat_bidang', 'profil_advokat_id', 'bidang_id')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Ulasan Klien
     */
    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'advokat_id', 'user_id');
    }
}

