<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';

    protected $fillable = [
        'parent_id',
        'nama',
        'tingkat',
    ];

    /**
     * Relasi ke wilayah induk (parent)
     */
    public function parent()
    {
        return $this->belongsTo(Wilayah::class, 'parent_id');
    }

    /**
     * Relasi ke sub-wilayah anak (children)
     */
    public function children()
    {
        return $this->hasMany(Wilayah::class, 'parent_id');
    }

    /**
     * Relasi ke users yang berdomisili di wilayah ini
     */
    public function users()
    {
        return $this->hasMany(User::class, 'wilayah_id');
    }

    /**
     * Relasi Many-to-Many ke Advokat yang melayani wilayah ini
     */
    public function advokat()
    {
        return $this->belongsToMany(ProfilAdvokat::class, 'advokat_wilayah', 'wilayah_id', 'profil_advokat_id')
                    ->withTimestamps();
    }
}

