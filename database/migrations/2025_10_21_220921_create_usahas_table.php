<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usahas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_usahas')->cascadeOnDelete();
            $table->foreignId('kelurahan_id')->constrained()->cascadeOnDelete();
            
            // Data Usaha
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->text('alamat');
            
            // Koordinat
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            // Kontak
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('whatsapp')->nullable();
            
            // Operasional
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->json('hari_operasional')->nullable(); // ["senin", "selasa", ...]
            
            // Pemilik/Penanggung Jawab
            $table->string('nama_pemilik')->nullable();
            $table->string('nik_pemilik')->nullable();
            $table->string('telepon_pemilik')->nullable();
            
            // Status & Verifikasi
            $table->enum('status', ['aktif', 'tidak_aktif', 'tutup_sementara', 'tutup_permanen'])
                ->default('aktif');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            
            // Metadata
            $table->integer('views_count')->default(0);
            $table->decimal('rating_avg', 3, 2)->nullable();
            $table->integer('reviews_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes untuk performa
            $table->index(['latitude', 'longitude']);
            $table->index(['kategori_id', 'status']);
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usahas');
    }
};