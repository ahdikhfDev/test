<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FotoUsaha extends Model
{
    protected $fillable = [
        'usaha_id',
        'path',
        'caption',
        'is_primary',
        'urutan',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'urutan' => 'integer',
    ];

    public function usaha(): BelongsTo
    {
        return $this->belongsTo(Usaha::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }
}