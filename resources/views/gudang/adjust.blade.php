@extends('layouts.app')

@section('content')
    @php
        $backRoute = $barang->dapat_dibeli ? route('gudang.stok-konsumsi') : route('gudang.stok-produksi');
    @endphp

    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Inventory Gudang', 'url' => $backRoute],
        ['label' => 'Stock Opname'],
    ]" />

    <x-page-header title="Stock Opname (Penyesuaian Stok)" subtitle="Sesuaikan stok fisik barang di gudang dengan pencatatan di sistem untuk barang: {{ $barang->nama_barang }}" />

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-6">
            <x-alert type="error">{{ session('error') }}</x-alert>
        </div>
    @endif

    <div class="max-w-3xl">
        <x-card>
            <form action="{{ route('gudang.adjust', $barang->id_barang) }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Detail Info (Readonly) --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nama Barang</label>
                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">{{ $barang->nama_barang }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Kategori</label>
                            <p class="mt-1 text-base font-semibold text-gray-700 dark:text-gray-300">{{ $barang->kategori_barang }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Stok Sistem Saat Ini</label>
                            <p class="mt-1 text-lg font-bold text-indigo-600 dark:text-indigo-500">
                                {{ number_format($barang->stok_barang, 2, ',', '.') }} 
                                <span class="text-sm font-semibold text-indigo-400 uppercase ml-1">{{ $barang->satuan }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Input Fields --}}
                <div class="space-y-5" x-data="{
                    stokSistem: {{ $barang->stok_barang }},
                    stokFisik: {{ $barang->stok_barang }},
                    
                    get isSameStock() {
                        return parseFloat(this.stokFisik) === parseFloat(this.stokSistem);
                    }
                }">
                    <div>
                        <label for="stok_fisik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Stok Fisik Aktual <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                step="0.01" 
                                name="stok_fisik" 
                                id="stok_fisik" 
                                x-model="stokFisik" 
                                min="0" 
                                required 
                                class="block w-full pr-16 pl-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-lg font-bold transition-colors"
                            >
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400 font-bold uppercase">{{ $barang->satuan }}</span>
                            </div>
                        </div>
                        
                        {{-- Warning jika stok tidak ada perubahan --}}
                        <p x-show="isSameStock" x-cloak class="mt-2 text-sm font-bold text-amber-600 dark:text-amber-500 flex items-center bg-amber-50 dark:bg-amber-900/30 p-3 rounded-lg border border-amber-200 dark:border-amber-800">
                            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            Stok aktual sama dengan sistem. Tidak ada perubahan yang akan disimpan.
                        </p>
                    </div>

                    <div>
                        <label for="alasan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Alasan Penyesuaian <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="alasan" 
                            id="alasan" 
                            rows="4" 
                            required 
                            maxlength="255" 
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm py-3 px-4" 
                            placeholder="Contoh: Barang tumpah/rusak, koreksi perhitungan bulan lalu, penyusutan..."
                        ></textarea>
                    </div>
                    
                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ $backRoute }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                            Batal
                        </a>
                        <x-button 
                            variant="primary" 
                            type="submit" 
                            icon="check-circle"
                            ::disabled="isSameStock"
                        >
                            Simpan Penyesuaian
                        </x-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
@endsection
