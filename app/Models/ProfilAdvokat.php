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
        'pengalaman',
        'tarif_konsultasi',
        'kuota_probono',
        'ketersediaan',
        'status_verifikasi',
    ];

    protected function casts(): array
    {
        return [
            'tarif_konsultasi' => 'decimal:2',
            'kuota_probono'    => 'integer',
            'ketersediaan'     => 'boolean',
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
}

