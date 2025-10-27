<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicMapController;

// Halaman Utama - Peta Usaha
Route::get('/', [PublicMapController::class, 'index'])->name('home');

// API untuk filter usaha
Route::get('/api/usahas', [PublicMapController::class, 'getUsahas'])->name('api.usahas');

// Detail Usaha
Route::get('/usaha/{slug}', [PublicMapController::class, 'show'])->name('usaha.show');