<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Usaha;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class KecamatanKelurahanUsahaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // --- 1. Tambah kecamatan ---
        $kecamatan = Kecamatan::firstOrCreate(
            ['kode' => 'BGTMR'],
            [
                'nama' => 'Bogor Timur',
                'deskripsi' => 'Wilayah dengan banyak kegiatan usaha kuliner, retail, dan jasa.',
                'center_lat' => -6.64947572,
                'center_lng' => 106.90568783,
                'boundary_coordinates' => json_encode([]),
                'is_active' => true,
            ]
        );

        // --- 2. Tambah kelurahan ---
        $kelurahans = [
            ['nama' => 'Sindangrasa', 'kode' => 'SDR001', 'lat' => -6.6488, 'lng' => 106.9060],
            ['nama' => 'Katulampa', 'kode' => 'KTL002', 'lat' => -6.6492, 'lng' => 106.9055],
            ['nama' => 'Baranangsiang', 'kode' => 'BRS003', 'lat' => -6.6501, 'lng' => 106.9059],
            ['nama' => 'Tajur', 'kode' => 'TJR004', 'lat' => -6.6521, 'lng' => 106.9067],
            ['nama' => 'Sukasari', 'kode' => 'SKS005', 'lat' => -6.6545, 'lng' => 106.9052],
        ];

        foreach ($kelurahans as $kel) {
            Kelurahan::firstOrCreate(
                ['kode' => $kel['kode']],
                [
                    'kecamatan_id' => $kecamatan->id,
                    'nama' => $kel['nama'],
                    'center_lat' => $kel['lat'],
                    'center_lng' => $kel['lng'],
                    'is_active' => true,
                ]
            );
        }

        $kelurahanIds = Kelurahan::pluck('id')->toArray();

        // --- 3. Generate 200 data usaha acak ---
        $kategoriIds = range(1, 8); // menyesuaikan kategori yang sudah kamu punya
        $namaUsahaContoh = [
            'Warung Kopi', 'Toko Roti', 'Bengkel Motor', 'Ayam Geprek', 'Bimbel Pintar',
            'Apotek Sehat', 'Laundry Express', 'Salon Cantik', 'Service AC', 'Hotel Asri',
            'Warung Nasi', 'Percetakan Cepat', 'Fotocopy Center', 'Toko Elektronik',
            'Barbershop', 'Warung Sate', 'Kursus Komputer', 'Cuci Mobil', 'Klinik Gigi',
            'Mart Buah Segar'
        ];

        for ($i = 0; $i < 200; $i++) {
            $nama = $faker->randomElement($namaUsahaContoh) . ' ' . $faker->lastName;
            Usaha::create([
                'kategori_id' => $faker->randomElement($kategoriIds),
                'kelurahan_id' => $faker->randomElement($kelurahanIds),
                'nama' => $nama,
                'slug' => Str::slug($nama . '-' . $i),
                'deskripsi' => $faker->sentence(8),
                'alamat' => $faker->address,
                'latitude' => -6.64 + $faker->randomFloat(5, 0, 0.02),
                'longitude' => 106.90 + $faker->randomFloat(5, 0, 0.02),
                'telepon' => '08' . rand(1000000000, 9999999999),
                'whatsapp' => '08' . rand(1000000000, 9999999999),
                'jam_buka' => '08:00',
                'jam_tutup' => '22:00',
                'hari_operasional' => json_encode(["senin","selasa","rabu","kamis","jumat","sabtu"]),
                'nama_pemilik' => $faker->name,
                'nik_pemilik' => rand(3201012301000000, 3201012301999999),
                'telepon_pemilik' => '08' . rand(1000000000, 9999999999),
                'status' => 'aktif',
                'is_verified' => (bool)rand(0,1),
                'verified_at' => now(),
                'verified_by' => 1,
                'views_count' => rand(5,1000),
                'rating_avg' => rand(35, 50) / 10,
                'reviews_count' => rand(1,100),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Seeder Kecamatan, Kelurahan, dan 200 Usaha berhasil dibuat!');
    }
}
