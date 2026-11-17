<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangHukum extends Model
{
    use HasFactory;

    protected $table = 'bidang_hukum';

    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi Many-to-Many ke Advokat dengan spesialisasi bidang hukum ini
     */
    public function advokat()
    {
        return $this->belongsToMany(ProfilAdvokat::class, 'advokat_bidang', 'bidang_id', 'profil_advokat_id')
                    ->withTimestamps();
    }
}

