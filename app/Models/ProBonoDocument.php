<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProBonoDocument extends Model
{
    protected $fillable = [
        'application_id',
        'original_name',
        'file_path',
        'mime_type',
        'size_bytes',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(
            ProBonoApplication::class,
            'application_id'
        );
    }
}