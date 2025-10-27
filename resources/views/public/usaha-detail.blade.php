<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $usaha->nama }} - Detail Usaha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-2 text-blue-600 hover:text-blue-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-medium">Kembali ke Peta</span>
                </a>
                <h1 class="text-xl font-bold text-gray-800">Detail Usaha</h1>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Header Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $usaha->nama }}</h1>
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $usaha->kategori->nama }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $usaha->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($usaha->status) }}
                                </span>
                                @if($usaha->is_verified)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Terverifikasi
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center gap-1 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="text-sm">{{ $usaha->views_count ?? 0 }} views</span>
                            </div>
                        </div>
                    </div>

                    @if($usaha->deskripsi)
                    <div class="prose max-w-none">
                        <p class="text-gray-700">{{ $usaha->deskripsi }}</p>
                    </div>
                    @endif
                </div>

                <!-- Map -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">📍 Lokasi</h2>
                    <div id="map" class="w-full h-96 rounded-lg border"></div>
                    <div class="mt-4 flex items-start gap-2 text-gray-700">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div>
                            <p class="font-medium">{{ $usaha->alamat }}</p>
                            <p class="text-sm text-gray-500">{{ $usaha->kelurahan->nama }}</p>
                        </div>
                    </div>
                </div>

                <!-- Operating Hours -->
                @if($usaha->jam_buka || $usaha->hari_operasional)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">🕐 Jam Operasional</h2>
                    <div class="space-y-3">
                        @if($usaha->jam_buka && $usaha->jam_tutup)
                        <div class="flex items-center gap-3">
                            <div class="w-32 font-medium text-gray-700">Jam Buka</div>
                            <div class="text-gray-900">{{ date('H:i', strtotime($usaha->jam_buka)) }} - {{ date('H:i', strtotime($usaha->jam_tutup)) }}</div>
                        </div>
                        @endif
                        
                        @if($usaha->hari_operasional)
                        <div class="flex items-start gap-3">
                            <div class="w-32 font-medium text-gray-700">Hari Buka</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($usaha->hari_operasional as $hari)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                    {{ ucfirst($hari) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Contact Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">📞 Kontak</h3>
                    <div class="space-y-3">
                        @if($usaha->telepon)
                        <a href="tel:{{ $usaha->telepon }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500">Telepon</div>
                                <div class="font-medium text-gray-900">{{ $usaha->telepon }}</div>
                            </div>
                        </a>
                        @endif

                        @if($usaha->whatsapp)
                        <a href="https://wa.me/{{ $usaha->whatsapp }}" target="_blank" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500">WhatsApp</div>
                                <div class="font-medium text-gray-900">{{ $usaha->whatsapp }}</div>
                            </div>
                        </a>
                        @endif

                        @if($usaha->email)
                        <a href="mailto:{{ $usaha->email }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500">Email</div>
                                <div class="font-medium text-gray-900 break-all">{{ $usaha->email }}</div>
                            </div>
                        </a>
                        @endif

                        @if($usaha->website)
                        <a href="{{ $usaha->website }}" target="_blank" class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center group-hover:bg-indigo-200 transition-colors">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs text-gray-500">Website</div>
                                <div class="font-medium text-gray-900 truncate">{{ $usaha->website }}</div>
                            </div>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Navigation Button -->
                <a href="{{ $usaha->google_maps_url }}" target="_blank" 
                   class="block w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-center py-4 rounded-lg font-semibold shadow-lg transition-all transform hover:scale-105">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        <span>Navigasi ke Lokasi</span>
                    </div>
                </a>

                <!-- Owner Info -->
                @if($usaha->nama_pemilik || $usaha->telepon_pemilik)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">👤 Informasi Pemilik</h3>
                    <div class="space-y-2 text-sm">
                        @if($usaha->nama_pemilik)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama</span>
                            <span class="font-medium text-gray-900">{{ $usaha->nama_pemilik }}</span>
                        </div>
                        @endif
                        @if($usaha->telepon_pemilik)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Telepon</span>
                            <a href="tel:{{ $usaha->telepon_pemilik }}" class="font-medium text-blue-600 hover:text-blue-700">
                                {{ $usaha->telepon_pemilik }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Share -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🔗 Bagikan</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="shareWhatsApp()" class="flex flex-col items-center gap-2 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">WhatsApp</span>
                        </button>
                        <button onclick="shareFacebook()" class="flex flex-col items-center gap-2 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">Facebook</span>
                        </button>
                        <button onclick="copyLink()" class="flex flex-col items-center gap-2 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">Salin Link</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize map
        const map = L.map('map').setView([{{ $usaha->latitude }}, {{ $usaha->longitude }}], 16);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const icon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #3b82f6; width: 40px; height: 40px; border-radius: 50% 50% 50% 0; transform: rotate(-45deg); border: 4px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3);"></div>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        L.marker([{{ $usaha->latitude }}, {{ $usaha->longitude }}], { icon })
            .addTo(map)
            .bindPopup('<b>{{ $usaha->nama }}</b><br>{{ $usaha->alamat }}')
            .openPopup();

        // Share functions
        function shareWhatsApp() {
            const text = encodeURIComponent('{{ $usaha->nama }} - {{ $usaha->alamat }}\n\n{{ url()->current() }}');
            window.open(`https://wa.me/?text=${text}`, '_blank');
        }

        function shareFacebook() {
            const url = encodeURIComponent('{{ url()->current() }}');
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }

        function copyLink() {
            navigator.clipboard.writeText('{{ url()->current() }}').then(() => {
                alert('Link berhasil disalin!');
            });
        }
    </script>
</body>
</html>