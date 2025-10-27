<?php

namespace App\Http\Controllers;

use App\Models\KategoriUsaha;
use App\Models\Usaha;
use App\Models\Kategori;
use App\Models\Kelurahan;
use Illuminate\Http\Request;

class PublicMapController extends Controller
{
    public function index()
    {
        $usahas = Usaha::with(['kategori', 'kelurahan'])
            ->where('status', 'aktif')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $kategoris = KategoriUsaha::withCount('usahas')->get();
        $kelurahans = Kelurahan::withCount('usahas')->get();

        return view('public.map', compact('usahas', 'kategoris', 'kelurahans'));
    }

    public function getUsahas(Request $request)
    {
        $query = Usaha::with(['kategori', 'kelurahan'])
            ->where('status', 'aktif')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        // Filter by kategori
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter by kelurahan
        if ($request->has('kelurahan_id') && $request->kelurahan_id != '') {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        // Search by name
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $usahas = $query->get();

        return response()->json([
            'success' => true,
            'data' => $usahas->map(function ($usaha) {
                return [
                    'id' => $usaha->id,
                    'nama' => $usaha->nama,
                    'slug' => $usaha->slug,
                    'alamat' => $usaha->alamat,
                    'telepon' => $usaha->telepon,
                    'whatsapp' => $usaha->whatsapp,
                    'latitude' => (float) $usaha->latitude,
                    'longitude' => (float) $usaha->longitude,
                    'kategori' => [
                        'id' => $usaha->kategori->id,
                        'nama' => $usaha->kategori->nama,
                        'color' => $usaha->kategori->color ?? 'blue',
                    ],
                    'kelurahan' => [
                        'id' => $usaha->kelurahan->id,
                        'nama' => $usaha->kelurahan->nama,
                    ],
                    'google_maps_url' => $usaha->google_maps_url,
                ];
            })
        ]);
    }

    public function show($slug)
    {
        $usaha = Usaha::with(['kategori', 'kelurahan'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment views
        $usaha->increment('views_count');

        return view('public.usaha-detail', compact('usaha'));
    }
}