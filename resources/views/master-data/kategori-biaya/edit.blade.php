@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Kategori Biaya Operasional', 'url' => route('master-data.kategori-biaya.index')],
        ['label' => 'Edit Kategori'],
    ]" />

    <x-page-header title="Edit Kategori Biaya" subtitle="Perbarui kategori biaya operasional" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.kategori-biaya.update', $kategori->id_kategori_biaya) }}">
                @csrf
                @method('PUT')
                <x-form-section title="Informasi Kategori" description="Detail kategori biaya operasional">
                    <div class="space-y-4">
                        <x-input
                            name="nama_kategori"
                            label="Nama Kategori"
                            placeholder="Contoh: Biaya Listrik"
                            :required="true"
                            hint="Maksimal 50 karakter"
                            :value="old('nama_kategori', $kategori->nama_kategori)"
                        />
        
                        <x-textarea
                            name="keterangan"
                            label="Keterangan"
                            placeholder="Penjelasan detail tentang kategori biaya ini (opsional)..."
                            :required="false"
                            rows="3"
                            :value="old('keterangan', $kategori->keterangan)"
                        />
                    </div>
                </x-form-section>

                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.kategori-biaya.index') }}">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
