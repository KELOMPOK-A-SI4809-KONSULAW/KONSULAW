<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifikasiEkonomi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi_ekonomi';

    protected $fillable = [
        'user_id',
        'diperiksa_oleh',
        'dokumen',
        'status',
        'catatan_admin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemeriksa()
    {
        return $this->belongsTo(User::class, 'diperiksa_oleh');
    }
}

