@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pelanggan', 'url' => route('master-data.pelanggan.index')],
        ['label' => 'Tambah Pelanggan'],
    ]" />

    <x-page-header title="Tambah Pelanggan Baru" subtitle="Lengkapi data pelanggan baru" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
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
                </x-form-section>

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
@endsection
