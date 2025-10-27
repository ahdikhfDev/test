<?php

namespace Database\Seeders;

use App\Models\KategoriUsaha;
use Illuminate\Database\Seeder;

class KategoriUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'nama' => 'Kuliner',
                'icon' => 'heroicon-o-cake',
                'color' => '#ef4444',
                'urutan' => 1,
            ],
            [
                'nama' => 'Retail & Toko',
                'icon' => 'heroicon-o-shopping-bag',
                'color' => '#3b82f6',
                'urutan' => 2,
            ],
            [
                'nama' => 'Jasa',
                'icon' => 'heroicon-o-wrench-screwdriver',
                'color' => '#8b5cf6',
                'urutan' => 3,
            ],
            [
                'nama' => 'Pendidikan',
                'icon' => 'heroicon-o-academic-cap',
                'color' => '#10b981',
                'urutan' => 4,
            ],
            [
                'nama' => 'Kesehatan',
                'icon' => 'heroicon-o-heart',
                'color' => '#f59e0b',
                'urutan' => 5,
            ],
            [
                'nama' => 'Fashion',
                'icon' => 'heroicon-o-sparkles',
                'color' => '#ec4899',
                'urutan' => 6,
            ],
            [
                'nama' => 'Otomotif',
                'icon' => 'heroicon-o-truck',
                'color' => '#6366f1',
                'urutan' => 7,
            ],
            [
                'nama' => 'Hotel & Penginapan',
                'icon' => 'heroicon-o-home-modern',
                'color' => '#14b8a6',
                'urutan' => 8,
            ],
        ];

        foreach ($kategoris as $kategori) {
            KategoriUsaha::create($kategori);
        }
    }
}