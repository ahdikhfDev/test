<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Usaha extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kategori_id',
        'kelurahan_id',
        'nama',
        'slug',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'telepon',
        'email',
        'website',
        'whatsapp',
        'jam_buka',
        'jam_tutup',
        'hari_operasional',
        'nama_pemilik',
        'nik_pemilik',
        'telepon_pemilik',
        'status',
        'is_verified',
        'verified_at',
        'verified_by',
        'views_count',
        'rating_avg',
        'reviews_count',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        // 'hari_operasional' => 'array',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'views_count' => 'integer',
        'rating_avg' => 'decimal:2',
        'reviews_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($usaha) {
            if (empty($usaha->slug)) {
                $usaha->slug = Str::slug($usaha->nama);
            }
        });
    }
    /**
     * Get the hari_operasional attribute.
     *
     * @param  string|null  $value
     * @return array
     */
    public function getHariOperasionalAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        // 1. Coba decode sebagai JSON terlebih dahulu
        $data = json_decode($value, true);
        if (is_array($data)) {
            return $data;
        }

        // 2. Jika gagal, anggap sebagai string dipisah koma
        return array_map('trim', explode(',', $value));
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriUsaha::class, 'kategori_id');
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(FotoUsaha::class);
    }

    public function fotoPrimary()
    {
        return $this->hasOne(FotoUsaha::class)->where('is_primary', true);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Helper method untuk mendapatkan Google Maps link
    public function getGoogleMapsUrlAttribute(): string
    {
        return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
    }

    // Helper method untuk mendapatkan jarak dari koordinat tertentu
    public function getDistanceFrom(float $lat, float $lng): float
    {
        $earthRadius = 6371; // km

        $latFrom = deg2rad($lat);
        $lonFrom = deg2rad($lng);
        $latTo = deg2rad($this->latitude);
        $lonTo = deg2rad($this->longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}