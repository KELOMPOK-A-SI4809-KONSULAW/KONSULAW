<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanKonsultasi extends Model
{
    use HasFactory;

    protected $table = 'pesan_konsultasi';

    protected $fillable = [
        'konsultasi_id',
        'pengirim_id',
        'isi',
        'lampiran',
    ];

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}

