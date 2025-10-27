<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicMapController;
use App\Models\Usaha;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::get('/usahas', function (Request $request) {
    $query = Usaha::with(['kategori', 'kelurahan']);

    if ($request->has('search')) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    if ($request->has('kategori_id') && $request->kategori_id) {
        $query->where('kategori_id', $request->kategori_id);
    }

    if ($request->has('kelurahan_id') && $request->kelurahan_id) {
        $query->where('kelurahan_id', $request->kelurahan_id);
    }

    return response()->json([
        'data' => $query->get()
    ]);
});

