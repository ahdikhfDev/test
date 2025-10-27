<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KategoriUsaha extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'icon',
        'color',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama);
            }
        });
    }

    public function usahas(): HasMany
    {
        return $this->hasMany(Usaha::class, 'kategori_id');
    }
}