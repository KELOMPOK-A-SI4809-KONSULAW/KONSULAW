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
        'status',
    ];

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

