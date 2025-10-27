<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriUsahaSeeder::class,
            // KecamatanSeeder::class, // sesuaikan dengan data kecamatan Anda
        ]);
    }
}