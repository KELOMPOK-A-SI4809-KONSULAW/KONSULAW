<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'konsultasi_id',
        'order_id',
        'jumlah',
        'status',
        'metode',
        'snap_token',
        'transaction_id',
        'dibayar_pada',
        'payload_notifikasi',
    ];

    protected function casts(): array
    {
        return [
            'jumlah'       => 'decimal:2',
            'dibayar_pada' => 'datetime',
        ];
    }

    public function konsultasi()
    {
        return $this->belongsTo(Konsultasi::class, 'konsultasi_id');
    }
}
