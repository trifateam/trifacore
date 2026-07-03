@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Kandang', 'url' => route('master-data.kandang.index')],
        ['label' => 'Tambah Kandang'],
    ]" />

    <x-page-header title="Tambah Kandang Baru" subtitle="Lengkapi data kandang baru" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.kandang.store') }}">
                @csrf
                <x-form-section title="Informasi Kandang" description="Lengkapi data kandang baru">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input
                            name="nama_kandang"
                            label="Nama Kandang"
                            placeholder="Contoh: Kandang A1"
                            :required="true"
                            hint="Maksimal 50 karakter, harus unik"
                        />
        
                        <x-input
                            name="tahun_masuk"
                            label="Tahun Masuk"
                            type="number"
                            placeholder="Contoh: {{ date('Y') }}"
                            :required="true"
                            hint="Tahun mulai operasi (2000 - {{ date('Y') }})"
                        />
                    </div>
                </x-form-section>

                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.kandang.index') }}">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Simpan Kandang
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
