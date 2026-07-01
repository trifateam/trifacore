@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Barang/Item', 'url' => route('master-data.barang.index')],
        ['label' => 'Tambah Barang'],
    ]" />

    <x-page-header title="Tambah Barang Baru" subtitle="Lengkapi data barang baru" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.barang.store') }}">
                @csrf
                <x-form-section title="Informasi Barang" description="Lengkapi data barang baru">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input name="nama_barang" label="Nama Barang" placeholder="Contoh: Telur Ayam" :required="true" hint="Maksimal 100 karakter, harus unik" />
                        <x-select name="kategori_barang" label="Kategori" :required="true"
                            :options="[
                                ['value' => 'Telur', 'label' => 'Telur'],
                                ['value' => 'Pakan', 'label' => 'Pakan'],
                                ['value' => 'Vitamin', 'label' => 'Vitamin'],
                                ['value' => 'Pupuk', 'label' => 'Pupuk'],
                                ['value' => 'Obat', 'label' => 'Obat'],
                                ['value' => 'Lainnya', 'label' => 'Lainnya'],
                            ]" />
                        <x-input name="sku" label="SKU / Kode" placeholder="Contoh: TLR-001" hint="Opsional, harus unik jika diisi" />
                        <x-select name="satuan" label="Satuan" :required="true"
                            :options="[
                                ['value' => 'butir', 'label' => 'Butir'],
                                ['value' => 'kg', 'label' => 'Kilogram (kg)'],
                                ['value' => 'karung', 'label' => 'Karung'],
                                ['value' => 'liter', 'label' => 'Liter'],
                                ['value' => 'box', 'label' => 'Box'],
                                ['value' => 'botol', 'label' => 'Botol'],
                                ['value' => 'ekor', 'label' => 'Ekor'],
                            ]" />
                        <x-input name="stok_barang" label="Stok Awal" type="number" placeholder="0" value="0" :required="true" hint="Stok awal saat pertama ditambahkan" />
                        <x-input name="stok_minimum" label="Stok Minimum" type="number" placeholder="0" value="0" :required="true" hint="Batas alert stok kritis" />
                        <x-input name="harga" label="Harga Default" type="number" placeholder="0" value="0" :required="true" prefix="Rp" hint="Harga satuan default" />
                    </div>
                    <div class="flex items-center space-x-8 mt-2">
                        <x-toggle name="dapat_dijual" label="Dapat Dijual" :checked="false" />
                        <x-toggle name="dapat_dibeli" label="Dapat Dibeli" :checked="false" />
                    </div>
                </x-form-section>
                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.barang.index') }}">Batal</x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        Simpan Barang
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
