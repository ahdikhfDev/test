<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $fillable = [
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

    public function kelurahans(): HasMany
    {
        return $this->hasMany(Kelurahan::class);
    }

    public function usahas(): HasMany
    {
        return $this->hasManyThrough(Usaha::class, Kelurahan::class);
    }
}