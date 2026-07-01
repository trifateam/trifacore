@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pegawai', 'url' => route('master-data.pegawai.index')],
        ['label' => 'Tambah Pegawai'],
    ]" />

    <x-page-header title="Tambah Pegawai Baru" subtitle="Lengkapi data pegawai baru" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.pegawai.store') }}">
                @csrf
                <x-form-section title="Informasi Pegawai" description="Lengkapi data pegawai baru">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input name="nama_lengkap" label="Nama Lengkap" placeholder="Contoh: Budi Santoso" :required="true" hint="Maksimal 100 karakter" />
                        
                        <x-input name="username" label="Username" placeholder="Contoh: budisantoso" :required="true" hint="Alfanumerik, maksimal 50 karakter, harus unik" />
                        
                        <x-input name="password" label="Password" type="password" placeholder="Minimal 8 karakter" :required="true" hint="Minimal 8 karakter" />
                        
                        <x-input name="password_confirmation" label="Konfirmasi Password" type="password" placeholder="Ulangi password" :required="true" hint="Harus sama dengan password" />
                        
                        <x-select name="role" label="Role" :required="true"
                            :options="[
                                ['value' => 'Admin', 'label' => 'Admin'],
                                ['value' => 'Owner', 'label' => 'Owner'],
                                ['value' => 'Pegawai Kandang', 'label' => 'Pegawai Kandang'],
                                ['value' => 'Sales', 'label' => 'Sales'],
                                ['value' => 'Pegawai Gudang', 'label' => 'Pegawai Gudang'],
                            ]" />
                    </div>
                </x-form-section>

                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.pegawai.index') }}">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Simpan Pegawai
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
