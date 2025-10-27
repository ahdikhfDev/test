<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div 
        x-data="leafletMapComponent({
            statePath: '{{ $getStatePath() }}',
            defaultLat: {{ $getDefaultLatitude() }},
            defaultLng: {{ $getDefaultLongitude() }},
            defaultZoom: {{ $getDefaultZoom() }},
            draggable: {{ $isDraggable() ? 'true' : 'false' }},
            searchable: {{ $isSearchable() ? 'true' : 'false' }}
        })"
        x-init="initMap()"
        wire:ignore
        class="space-y-3"
    >
        <!-- Search Box -->
        @if($isSearchable())
        <div class="flex gap-2">
            <input 
                type="text" 
                x-model="searchQuery"
                @keydown.enter.prevent="searchLocation()"
                placeholder="Cari lokasi (contoh: Jl. Sudirman Jakarta)"
                class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500"
            />
            <button 
                type="button"
                @click="searchLocation()"
                class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari
            </button>
        </div>
        @endif

        <!-- Map Container -->
        <div 
            x-ref="mapContainer"
            class="w-full rounded-lg border-2 border-gray-300 dark:border-gray-600 shadow-sm overflow-hidden"
            style="height: 450px; min-height: 450px;"
        ></div>

        <!-- Instructions -->
        <div class="flex items-start gap-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg text-sm text-blue-700 dark:text-blue-300">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium">Cara menggunakan peta:</p>
                <ul class="mt-1 space-y-1 list-disc list-inside">
                    <li>Klik pada peta untuk menandai lokasi</li>
                    <li>Drag marker merah untuk menyesuaikan posisi</li>
                    <li>Gunakan search box untuk mencari alamat</li>
                </ul>
            </div>
        </div>

        <!-- Coordinate Display -->
        <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Latitude:</span>
                <p class="text-sm font-mono text-gray-900 dark:text-white" x-text="currentLat || '-'"></p>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Longitude:</span>
                <p class="text-sm font-mono text-gray-900 dark:text-white" x-text="currentLng || '-'"></p>
            </div>
        </div>
    </div>

    @once
        @push('styles')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
                  integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
                  crossorigin="" />
            <style>
                .leaflet-container {
                    z-index: 1;
                    font-family: inherit;
                }
                .leaflet-pane {
                    z-index: 400;
                }
                .leaflet-top,
                .leaflet-bottom {
                    z-index: 1000;
                }
                .leaflet-control {
                    z-index: 800;
                }
                .leaflet-popup-close-button {
                    color: #333 !important;
                }
            </style>
        @endpush

        @push('scripts')
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
                    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
                    crossorigin=""></script>
            <script>
                function leafletMapComponent(config) {
                    return {
                        map: null,
                        marker: null,
                        searchQuery: '',
                        currentLat: null,
                        currentLng: null,
                        
                        initMap() {
                            console.log('Initializing Leaflet Map...');

                            // Get current coordinates from Livewire
                            const lat = parseFloat(this.$wire.get('data.latitude')) || config.defaultLat;
                            const lng = parseFloat(this.$wire.get('data.longitude')) || config.defaultLng;

                            this.currentLat = lat.toFixed(8);
                            this.currentLng = lng.toFixed(8);

                            // Initialize map
                            this.map = L.map(this.$refs.mapContainer, {
                                center: [lat, lng],
                                zoom: config.defaultZoom,
                                zoomControl: true,
                                scrollWheelZoom: true
                            });

                            // Add tile layer
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                                maxZoom: 19,
                                minZoom: 5
                            }).addTo(this.map);

                            // Custom marker icon
                            const customIcon = L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });

                            // Add marker if coordinates exist
                            if (lat && lng) {
                                this.marker = L.marker([lat, lng], {
                                    icon: customIcon,
                                    draggable: config.draggable
                                }).addTo(this.map);

                                this.marker.bindPopup('<b>Lokasi Usaha</b><br>Drag untuk memindahkan').openPopup();

                                // Handle marker drag
                                if (config.draggable) {
                                    this.marker.on('dragend', (e) => {
                                        const latlng = e.target.getLatLng();
                                        this.updateCoordinates(latlng.lat, latlng.lng);
                                    });
                                }
                            }

                            // Handle map click
                            this.map.on('click', (e) => {
                                this.setMarker(e.latlng.lat, e.latlng.lng);
                            });

                            // Fix map display
                            setTimeout(() => {
                                this.map.invalidateSize();
                            }, 100);

                            console.log('Map initialized successfully!');
                        },

                        setMarker(lat, lng) {
                            const customIcon = L.icon({
                                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                iconSize: [25, 41],
                                iconAnchor: [12, 41],
                                popupAnchor: [1, -34],
                                shadowSize: [41, 41]
                            });

                            if (this.marker) {
                                this.marker.setLatLng([lat, lng]);
                            } else {
                                this.marker = L.marker([lat, lng], {
                                    icon: customIcon,
                                    draggable: config.draggable
                                }).addTo(this.map);

                                this.marker.bindPopup('<b>Lokasi Usaha</b><br>Drag untuk memindahkan').openPopup();

                                if (config.draggable) {
                                    this.marker.on('dragend', (e) => {
                                        const latlng = e.target.getLatLng();
                                        this.updateCoordinates(latlng.lat, latlng.lng);
                                    });
                                }
                            }

                            this.updateCoordinates(lat, lng);
                        },

                        updateCoordinates(lat, lng) {
                            this.currentLat = lat.toFixed(8);
                            this.currentLng = lng.toFixed(8);

                            // Update Livewire state
                            this.$wire.set('data.latitude', this.currentLat);
                            this.$wire.set('data.longitude', this.currentLng);

                            console.log('Coordinates updated:', this.currentLat, this.currentLng);
                        },

                        async searchLocation() {
                            if (!this.searchQuery.trim()) {
                                alert('Masukkan alamat yang ingin dicari');
                                return;
                            }

                            try {
                                const response = await fetch(
                                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=1`
                                );
                                
                                const data = await response.json();
                                
                                if (data && data.length > 0) {
                                    const lat = parseFloat(data[0].lat);
                                    const lon = parseFloat(data[0].lon);
                                    
                                    this.map.setView([lat, lon], 16);
                                    this.setMarker(lat, lon);
                                    
                                    if (this.marker) {
                                        this.marker.bindPopup(`<b>${data[0].display_name}</b>`).openPopup();
                                    }
                                } else {
                                    alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
                                }
                            } catch (error) {
                                console.error('Error geocoding:', error);
                                alert('Terjadi kesalahan saat mencari lokasi');
                            }
                        }
                    }
                }
            </script>
        @endpush
    @endonce
</x-dynamic-component>