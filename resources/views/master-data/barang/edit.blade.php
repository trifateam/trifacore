@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Barang/Item', 'url' => route('master-data.barang.index')],
        ['label' => 'Edit Barang'],
    ]" />

    <x-page-header title="Edit Data Barang" subtitle="Perbarui data barang" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.barang.update', $barang->id_barang) }}">
                @csrf
                @method('PUT')
                
                <x-form-section title="Informasi Barang" description="Perbarui data barang">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input name="nama_barang" label="Nama Barang" placeholder="Contoh: Telur Ayam" :required="true" hint="Maksimal 100 karakter, harus unik" :value="old('nama_barang', $barang->nama_barang)" />
                        
                        <x-select name="kategori_barang" label="Kategori" :required="true"
                            :options="[
                                ['value' => 'Telur', 'label' => 'Telur'],
                                ['value' => 'Pakan', 'label' => 'Pakan'],
                                ['value' => 'Vitamin', 'label' => 'Vitamin'],
                                ['value' => 'Pupuk', 'label' => 'Pupuk'],
                                ['value' => 'Obat', 'label' => 'Obat'],
                                ['value' => 'Lainnya', 'label' => 'Lainnya'],
                            ]"
                            :selected="old('kategori_barang', $barang->kategori_barang)" />
                            
                        <x-input name="sku" label="SKU / Kode" placeholder="Contoh: TLR-001" hint="Opsional, harus unik jika diisi" :value="old('sku', $barang->sku)" />
                        
                        <x-select name="satuan" label="Satuan" :required="true"
                            :options="[
                                ['value' => 'butir', 'label' => 'Butir'],
                                ['value' => 'kg', 'label' => 'Kilogram (kg)'],
                                ['value' => 'karung', 'label' => 'Karung'],
                                ['value' => 'liter', 'label' => 'Liter'],
                                ['value' => 'box', 'label' => 'Box'],
                                ['value' => 'botol', 'label' => 'Botol'],
                                ['value' => 'ekor', 'label' => 'Ekor'],
                            ]"
                            :selected="old('satuan', $barang->satuan)" />
                            
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok Saat Ini</label>
                            <input type="text" disabled value="{{ number_format($barang->stok_barang, 0, ',', '.') }} {{ $barang->satuan }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm text-sm bg-gray-100 dark:bg-gray-700 cursor-not-allowed text-gray-500 dark:text-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Stok hanya berubah via transaksi/opname</p>
                        </div>
                        
                        <x-input name="stok_minimum" label="Stok Minimum" type="number" placeholder="0" :required="true" hint="Batas alert stok kritis" :value="old('stok_minimum', $barang->stok_minimum)" />
                        
                        <x-input name="harga" label="Harga Default" type="number" placeholder="0" :required="true" prefix="Rp" hint="Harga satuan default" :value="old('harga', $barang->harga)" />
                    </div>
                    
                    <div class="flex items-center space-x-8 mt-2">
                        <x-toggle name="dapat_dijual" label="Dapat Dijual" :checked="(bool)old('dapat_dijual', $barang->dapat_dijual)" />
                        <x-toggle name="dapat_dibeli" label="Dapat Dibeli" :checked="(bool)old('dapat_dibeli', $barang->dapat_dibeli)" />
                    </div>
                </x-form-section>
                
                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.barang.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
