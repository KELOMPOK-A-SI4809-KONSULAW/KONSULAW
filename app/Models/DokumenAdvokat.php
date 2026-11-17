<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenAdvokat extends Model
{
    use HasFactory;

    protected $table = 'dokumen_advokat';

    protected $fillable = [
        'profil_advokat_id',
        'jenis',
        'file',
        'status',
        'catatan_admin',
    ];

    /**
     * Relasi ke Profil Advokat
     */
    public function profilAdvokat()
    {
        return $this->belongsTo(ProfilAdvokat::class, 'profil_advokat_id');
    }
}

