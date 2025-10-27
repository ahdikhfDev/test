<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelurahan extends Model
{
    protected $fillable = [
        'kecamatan_id',
        'nama',
        'kode',
        'deskripsi',
        'center_lat',
        'center_lng',
        'boundary_coordinates',
        'is_active',
    ];

    protected $casts = [
        'boundary_coordinates' => 'array',
        'is_active' => 'boolean',
        'center_lat' => 'decimal:8',
        'center_lng' => 'decimal:8',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function usahas(): HasMany
    {
        return $this->hasMany(Usaha::class);
    }
}