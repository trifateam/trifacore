@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pegawai', 'url' => route('master-data.pegawai.index')],
        ['label' => 'Edit Pegawai'],
    ]" />

    <x-page-header title="Edit Data Pegawai" subtitle="Perbarui data pegawai" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.pegawai.update', $pegawai->id_pengguna) }}">
                @csrf
                @method('PUT')
                <x-form-section title="Informasi Pegawai" description="Perbarui data pegawai">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <x-input name="nama_lengkap" label="Nama Lengkap" placeholder="Contoh: Budi Santoso" :required="true" hint="Maksimal 100 karakter" :value="old('nama_lengkap', $pegawai->nama_lengkap)" />
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                            <input type="text" disabled readonly value="{{ $pegawai->username }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm text-sm bg-gray-100 dark:bg-gray-700 cursor-not-allowed text-gray-500 dark:text-gray-400">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Username tidak dapat diubah setelah dibuat</p>
                        </div>
                        
                        <x-input name="password" label="Password Baru" type="password" placeholder="Kosongkan jika tidak diubah" :required="false" hint="Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password." />
                        
                        <x-input name="password_confirmation" label="Konfirmasi Password Baru" type="password" placeholder="Ulangi password baru" :required="false" hint="Harus sama dengan password baru" />
                        
                        <x-select name="role" label="Role" :required="true"
                            :options="[
                                ['value' => 'Admin', 'label' => 'Admin'],
                                ['value' => 'Owner', 'label' => 'Owner'],
                                ['value' => 'Pegawai Kandang', 'label' => 'Pegawai Kandang'],
                                ['value' => 'Sales', 'label' => 'Sales'],
                                ['value' => 'Pegawai Gudang', 'label' => 'Pegawai Gudang'],
                            ]"
                            :selected="old('role', $pegawai->role)" />
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
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
