<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $fillable = [
        'pelapor_id',
        'ditangani_oleh',
        'target_tipe',
        'target_id',
        'alasan',
        'status',
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }
}

