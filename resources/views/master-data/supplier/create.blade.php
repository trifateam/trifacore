@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Supplier', 'url' => route('master-data.supplier.index')],
        ['label' => 'Tambah Supplier'],
    ]" />

    <x-page-header title="Tambah Supplier Baru" subtitle="Lengkapi data supplier baru" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.supplier.store') }}">
                @csrf
                <x-form-section title="Informasi Supplier" description="Lengkapi data supplier baru">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input name="nama_supplier" label="Nama Supplier" placeholder="Contoh: PT Pakan Sejahtera" :required="true" hint="Maksimal 100 karakter, harus unik" />
                        <x-input name="kontak_supplier" label="No. Telp / Kontak" placeholder="Contoh: 08123456789" :required="true" hint="Maksimal 20 karakter" />
                        <x-input name="email" label="Email" type="email" placeholder="Contoh: supplier@email.com" hint="Opsional, format email valid" />
                        <x-input name="nama_pic" label="Nama PIC (Penanggung Jawab)" placeholder="Contoh: Budi Santoso" hint="Opsional, maksimal 100 karakter" />
                    </div>
                    <div class="mt-4">
                        <x-textarea name="alamat_supplier" label="Alamat" placeholder="Masukkan alamat lengkap supplier..." :required="true" hint="Alamat lengkap supplier" rows="3" />
                    </div>
                </x-form-section>

                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.supplier.index') }}">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Simpan Supplier
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
