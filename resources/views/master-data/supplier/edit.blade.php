@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Supplier', 'url' => route('master-data.supplier.index')],
        ['label' => 'Edit Supplier'],
    ]" />

    <x-page-header title="Edit Data Supplier" subtitle="Perbarui data supplier" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.supplier.update', $supplier->id_supplier) }}">
                @csrf
                @method('PUT')
                <x-form-section title="Informasi Supplier" description="Perbarui data supplier">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="mb-4">
                            <label for="edit_nama_supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Supplier <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_supplier" id="edit_nama_supplier" required maxlength="100" placeholder="Contoh: PT Pakan Sejahtera" value="{{ old('nama_supplier', $supplier->nama_supplier) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 100 karakter, harus unik</p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_kontak_supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">No. Telp / Kontak <span class="text-red-500">*</span></label>
                            <input type="text" name="kontak_supplier" id="edit_kontak_supplier" required maxlength="20" placeholder="Contoh: 08123456789" value="{{ old('kontak_supplier', $supplier->kontak_supplier) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 20 karakter</p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" id="edit_email" maxlength="100" placeholder="Contoh: supplier@email.com" value="{{ old('email', $supplier->email) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opsional, format email valid</p>
                        </div>
                        <div class="mb-4">
                            <label for="edit_nama_pic" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama PIC (Penanggung Jawab)</label>
                            <input type="text" name="nama_pic" id="edit_nama_pic" maxlength="100" placeholder="Contoh: Budi Santoso" value="{{ old('nama_pic', $supplier->nama_pic) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opsional, maksimal 100 karakter</p>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="edit_alamat_supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="alamat_supplier" id="edit_alamat_supplier" required rows="3" placeholder="Masukkan alamat lengkap supplier..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('alamat_supplier', $supplier->alamat_supplier) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Alamat lengkap supplier</p>
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
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
