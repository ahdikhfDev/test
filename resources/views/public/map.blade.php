<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Usaha Interaktif</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        #map { height: 100vh; background-color: #f0f2f5; }

        .leaflet-popup-content-wrapper {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0;
            overflow: hidden;
        }
        .leaflet-popup-content {
            margin: 0 !important;
            width: 320px !important;
        }
        .leaflet-popup-tip-container {
            width: 40px;
            height: 20px;
        }
        .leaflet-popup-tip {
            background: #ffffff;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .leaflet-container a.leaflet-popup-close-button {
            top: 12px;
            right: 12px;
            color: #ffffff;
            background-color: rgba(0,0,0,0.4);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            text-align: center;
            line-height: 24px;
            font-size: 18px;
            transition: background-color 0.2s;
        }
        .leaflet-container a.leaflet-popup-close-button:hover {
            color: #ffffff;
            background-color: rgba(0,0,0,0.6);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <div x-data="mapApp()" x-init="init()" class="flex h-screen overflow-hidden">
        
        <div class="w-[400px] bg-white border-r border-gray-200 flex flex-col z-20">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 rounded-xl">
                         <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Peta Usaha Interaktif</h1>
                        <p class="text-gray-500 text-sm mt-0.5">Temukan & jelajahi usaha di sekitar Anda.</p>
                    </div>
                </div>
            </div>

            <div class="p-5 space-y-4 border-b border-gray-200">
                <div>
                    <label for="search" class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Cari Usaha</label>
                    <div class="relative">
                        <input 
                            id="search" type="text" x-model="filters.search"
                            @input.debounce.500ms="filterUsahas()"
                            placeholder="Ketik nama usaha..."
                            class="w-full pl-10 pr-4 py-2 text-sm bg-gray-100 border border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all"
                        />
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="kategori" class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Kategori</label>
                        <div class="relative">
                            <select id="kategori" x-model="filters.kategori_id" @change="filterUsahas()" class="w-full pl-3 pr-8 py-2 text-sm bg-gray-100 border border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all">
                                <option value="">Semua</option>
                                @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <div>
                        <label for="kelurahan" class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2 block">Kelurahan</label>
                        <div class="relative">
                            <select id="kelurahan" x-model="filters.kelurahan_id" @change="filterUsahas()" class="w-full pl-3 pr-8 py-2 text-sm bg-gray-100 border border-transparent rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white focus:border-blue-500 transition-all">
                                <option value="">Semua</option>
                                 @foreach($kelurahans as $kelurahan)
                                <option value="{{ $kelurahan->id }}">{{ $kelurahan->nama }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-5 py-3 flex items-center justify-between border-b border-gray-200">
                <span class="text-sm text-gray-600">
                    Ditemukan <strong x-text="filteredUsahas.length" class="font-bold text-blue-600"></strong> usaha
                </span>
                <button @click="resetFilters()" class="flex items-center gap-1.5 text-sm text-blue-600 hover:text-blue-800 font-semibold transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:rotate-[-90deg]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M4 4l16 16"></path></svg>
                    Reset
                </button>
            </div>

            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <template x-if="loading">
                    <div class="p-10 text-center">
                        <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent"></div>
                        <p class="mt-4 text-gray-600 font-medium">Memuat data usaha...</p>
                    </div>
                </template>

                <template x-if="!loading && filteredUsahas.length === 0">
                    <div class="p-10 text-center text-gray-500">
                        <svg class="mx-auto w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="mt-4 font-bold text-lg text-gray-700">Oops! Usaha tidak ditemukan</p>
                        <p class="text-sm mt-1">Coba gunakan kata kunci atau filter yang berbeda.</p>
                    </div>
                </template>
                
                <div class="divide-y divide-gray-100">
                    <template x-for="usaha in filteredUsahas" :key="usaha.id">
                        <div 
                            @click="focusUsaha(usaha)"
                            class="p-5 hover:bg-gray-50 cursor-pointer transition-all duration-200 ease-in-out border-l-4"
                            :class="selectedUsaha?.id === usaha.id ? 'bg-blue-50 border-blue-500' : 'border-transparent'"
                        >
                            <div class="flex items-start justify-between mb-1.5">
                                <h3 class="font-bold text-base text-gray-800 pr-4" x-text="usaha.nama"></h3>
                                <span 
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full whitespace-nowrap"
                                    :style="`background-color: ${getKategoriColor(usaha.kategori.color)}20; color: ${getKategoriColor(usaha.kategori.color)}`"
                                    x-text="usaha.kategori.nama"
                                ></span>
                            </div>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-1" x-text="usaha.alamat"></p>
                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center gap-1.5" title="Kelurahan">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <span class="font-medium" x-text="usaha.kelurahan.nama"></span>
                                </span>
                                <template x-if="usaha.telepon">
                                    <span class="flex items-center gap-1.5" title="Telepon">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        <span class="font-medium" x-text="usaha.telepon"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex-1 relative">
            <div id="map"></div>
            
            <div class="absolute top-5 right-5 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg z-[1000] border border-gray-200/50">
                 <div class="flex items-center gap-6 p-5">
                    <div class="text-center">
                        <div class="text-3xl font-black text-blue-600" x-text="filteredUsahas.length"></div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-1">Total Usaha</div>
                    </div>
                    <div class="h-12 w-px bg-gray-200"></div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-green-600">{{ $kategoris->count() }}</div>
                        <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider mt-1">Kategori</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function mapApp() {
        return {
            map: null,
            markers: [],
            allUsahas: [],
            filteredUsahas: [],
            selectedUsaha: null,
            loading: true,
            filters: {
                search: '',
                kategori_id: '',
                kelurahan_id: ''
            },

            async init() {
                // Hapus map lama kalau sudah ada
                if (window.myMap) {
                    window.myMap.remove();
                }

                this.initMap();
                window.myMap = this.map;
                await this.filterUsahas();
            },

            initMap() {
                this.map = L.map('map', { zoomControl: false }).setView([-6.2088, 106.8456], 12);
                L.control.zoom({ position: 'bottomright' }).addTo(this.map);
                L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(this.map);
            },

            addMarkers() {
                this.markers.forEach(m => m.remove());
                this.markers = [];

                this.filteredUsahas.forEach(usaha => {
                    const icon = L.divIcon({
                        html: `<svg viewBox="0 0 32 46" fill="${this.getKategoriColor(usaha.kategori.color)}" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 0C7.163 0 0 7.163 0 16C0 28 16 46 16 46C16 46 32 28 32 16C32 7.163 24.837 0 16 0Z"/>
                                <circle cx="16" cy="16" r="7" fill="white"/>
                            </svg>`,
                        className: 'custom-div-icon',
                        iconSize: [32, 46],
                        iconAnchor: [16, 46],
                    });

                    const marker = L.marker([usaha.latitude, usaha.longitude], { icon })
                        .addTo(this.map)
                        .bindPopup(`<b>${usaha.nama}</b><br>${usaha.alamat}`);

                    marker.on('click', () => {
                        this.selectedUsaha = usaha;
                        this.map.flyTo([usaha.latitude, usaha.longitude], 17);
                    });

                    this.markers.push(marker);
                });
            },

            async filterUsahas() {
                this.loading = true;
                this.selectedUsaha = null;

                try {
                    const params = new URLSearchParams();
                    if (this.filters.search) params.append('search', this.filters.search);
                    if (this.filters.kategori_id) params.append('kategori_id', this.filters.kategori_id);
                    if (this.filters.kelurahan_id) params.append('kelurahan_id', this.filters.kelurahan_id);

                    const response = await fetch(`/api/usahas?${params}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    this.filteredUsahas = result.data || [];
                    this.addMarkers();
                } catch (error) {
                    console.error("Error filtering usahas:", error);
                } finally {
                    this.loading = false;
                }
            },

            resetFilters() {
                this.filters = { search: '', kategori_id: '', kelurahan_id: '' };
                this.filterUsahas();
            },

            getKategoriColor(color) {
                const colors = {
                    'blue': '#3b82f6', 'red': '#ef4444', 'green': '#10b981',
                    'yellow': '#f59e0b', 'purple': '#8b5cf6', 'pink': '#ec4899',
                    'indigo': '#6366f1', 'gray': '#6b7280'
                };
                return colors[color] || '#3b82f6';
            }
        }
    }
</script>

</body>
</html>