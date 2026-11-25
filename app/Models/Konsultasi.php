<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model
{
    use HasFactory;

    protected $table = 'konsultasi';

    protected $fillable = [
        'masyarakat_id',
        'advokat_id',
        'bidang_id',
        'jenis_layanan',
        'judul',
        'kronologi',
        'pertanyaan_hukum',
        'lampiran',
        'status',
        'alasan_penolakan',
        'batas_bayar',
    ];

    protected function casts(): array
    {
        return [
            'lampiran'    => 'array',
            'batas_bayar' => 'datetime',
        ];
    }

    /**
     * Apakah sesi chat konsultasi sedang aktif dan bisa saling berkirim pesan?
     * Aturan tunggal README B2.5: Hanya saat status 'berlangsung'.
     */
    public function chatTerbuka(): bool
    {
        return $this->status === 'berlangsung';
    }

    /**
     * Apakah riwayat percakapan konsultasi dapat diakses / dibaca?
     */
    public function chatBisaDibaca(): bool
    {
        return in_array($this->status, ['berlangsung', 'selesai']);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'konsultasi_id');
    }

    public function masyarakat()
    {
        return $this->belongsTo(User::class, 'masyarakat_id');
    }

    public function advokat()
    {
        return $this->belongsTo(User::class, 'advokat_id');
    }

    public function bidangHukum()
    {
        return $this->belongsTo(BidangHukum::class, 'bidang_id');
    }

    public function pesan()
    {
        return $this->hasMany(PesanKonsultasi::class, 'konsultasi_id');
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'konsultasi_id');
    }
}

