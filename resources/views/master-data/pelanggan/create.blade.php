@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pelanggan', 'url' => route('master-data.pelanggan.index')],
        ['label' => 'Tambah Pelanggan'],
    ]" />

    <x-page-header title="Tambah Pelanggan Baru" subtitle="Lengkapi data pelanggan baru" />

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden" x-data="pelangganForm()">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.pelanggan.store') }}">
                @csrf
                <x-form-section title="Informasi Pelanggan" description="Lengkapi data pelanggan baru">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input name="nama_lengkap" label="Nama Pelanggan" placeholder="Contoh: CV Telur Makmur" :required="true" hint="Maksimal 100 karakter, harus unik" />
                        <x-input name="kontak" label="No. Telp / Kontak" placeholder="Contoh: 08123456789" :required="true" hint="Maksimal 20 karakter" />
                        <x-select name="kategori" label="Kategori" :required="true"
                            :options="[
                                ['value' => 'Distributor', 'label' => 'Distributor'],
                                ['value' => 'Retail', 'label' => 'Retail'],
                                ['value' => 'Personal', 'label' => 'Personal'],
                            ]" />
                    </div>
                    <div class="mt-4">
                        <x-textarea name="alamat" label="Alamat" placeholder="Masukkan alamat lengkap pelanggan..." :required="true" hint="Alamat lengkap pelanggan" rows="3" />
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi Peta (Opsional)</label>
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                            <button type="button" @click="initMap()" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                Pin Lokasi Pelanggan
                            </button>
                        </div>
                        <input type="hidden" name="latitude" :value="latitude">
                        <input type="hidden" name="longitude" :value="longitude">
                        <p x-show="latitude && longitude" class="text-xs text-green-600 mt-1" x-text="`Titik disetel: ${latitude}, ${longitude}`"></p>
                    </div>
                </x-form-section>

                {{-- Map Modal --}}
                <div x-show="isMapModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <!-- Backdrop -->
                    <div x-show="isMapModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75" @click="closeMap()"></div>

                    <!-- Modal Panel -->
                    <div x-show="isMapModalOpen" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 scale-95" 
                         x-transition:enter-end="opacity-100 scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 scale-100" 
                         x-transition:leave-end="opacity-0 scale-95" 
                         class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl flex flex-col w-full max-w-4xl overflow-hidden z-10" 
                         style="max-height: 90vh;">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900 shrink-0">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                Pilih Titik Lokasi Pelanggan
                            </h3>
                            <button type="button" @click="closeMap()" class="text-gray-500 hover:text-red-500 focus:outline-none p-1 rounded-md transition-colors">
                                <span class="sr-only">Tutup</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                        
                        <!-- Body (Map) -->
                        <div class="flex-1 relative bg-gray-200 w-full" style="min-height: 60vh;">
                            <div id="map" class="absolute inset-0 w-full h-full" x-ignore></div>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 flex justify-end shrink-0 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.1)] relative z-20">
                            <button type="button" @click="closeMap()" class="w-full sm:w-auto inline-flex justify-center items-center rounded-md px-8 py-3 bg-indigo-600 text-white font-bold hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-500/50 shadow-lg transition-all transform hover:scale-105 active:scale-95 text-base">
                                Simpan Koordinat & Tutup
                            </button>
                        </div>
                    </div>
                </div>
                {{-- End Map Modal --}}

                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.pelanggan.index') }}">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Simpan Pelanggan
                    </x-button>
                </div>
            </form>
        </div>
    </div>

<script>
    function pelangganForm() {
        return {
            latitude: '{{ old("latitude", "") }}',
            longitude: '{{ old("longitude", "") }}',
            isMapModalOpen: false,
            map: null,
            marker: null,

            initMap() {
                this.isMapModalOpen = true;
                setTimeout(() => {
                    if (!this.map) {
                        let defaultLat = this.latitude || -0.789275; 
                        let defaultLng = this.longitude || 100.615306;
                        this.map = L.map('map').setView([defaultLat, defaultLng], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap contributors'
                        }).addTo(this.map);
                        
                        this.marker = L.marker([defaultLat, defaultLng], {draggable: true}).addTo(this.map);
                        
                        this.marker.on('dragend', (e) => {
                            let pos = e.target.getLatLng();
                            this.latitude = pos.lat.toFixed(6);
                            this.longitude = pos.lng.toFixed(6);
                        });
                        
                        this.map.on('click', (e) => {
                            this.marker.setLatLng(e.latlng);
                            this.latitude = e.latlng.lat.toFixed(6);
                            this.longitude = e.latlng.lng.toFixed(6);
                        });
                    } else {
                        this.map.invalidateSize();
                    }
                }, 300);
            },

            closeMap() {
                this.isMapModalOpen = false;
            }
        }
    }
</script>
@endsection
