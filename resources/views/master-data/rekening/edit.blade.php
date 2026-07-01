@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Rekening Kas/Bank', 'url' => route('master-data.rekening.index')],
        ['label' => 'Edit Rekening'],
    ]" />

    <x-page-header title="Edit Data Rekening" subtitle="Perbarui data akun kas atau bank" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.rekening.update', $rekening->id_akun) }}">
                @csrf
                @method('PUT')
                <x-form-section title="Informasi Rekening" description="Perbarui data akun kas atau bank">
                    <div class="space-y-4">
                        <div class="mb-4">
                            <label for="edit_nama_akun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Bank/Kas <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_akun" id="edit_nama_akun" required maxlength="50" placeholder="Contoh: BCA Utama" value="{{ old('nama_akun', $rekening->nama_akun) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                        </div>
    
                        <div class="mb-4">
                            <label for="edit_kategori_akun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Akun <span class="text-red-500">*</span></label>
                            <select name="kategori_akun" id="edit_kategori_akun" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm" onchange="document.getElementById('edit_no_rekening_group').style.display = this.value === 'Tunai' ? 'none' : 'block'">
                                <option value="Bank" {{ old('kategori_akun', $rekening->kategori_akun) == 'Bank' ? 'selected' : '' }}>Bank</option>
                                <option value="Tunai" {{ old('kategori_akun', $rekening->kategori_akun) == 'Tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="E-Wallet" {{ old('kategori_akun', $rekening->kategori_akun) == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                        </div>
    
                        <div class="mb-4" id="edit_no_rekening_group" style="display: {{ old('kategori_akun', $rekening->kategori_akun) === 'Tunai' ? 'none' : 'block' }}">
                            <label for="edit_no_rekening" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nomor Rekening</label>
                            <input type="text" name="no_rekening" id="edit_no_rekening" maxlength="50" placeholder="Contoh: 1234567890" value="{{ old('no_rekening', $rekening->no_rekening) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Wajib diisi untuk Bank/E-Wallet</p>
                        </div>
    
                        <div class="mb-4">
                            <label for="edit_nama_pemilik" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Pemilik (Atas Nama) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pemilik" id="edit_nama_pemilik" required maxlength="100" placeholder="Contoh: PT TriFaCore" value="{{ old('nama_pemilik', $rekening->nama_pemilik) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                        </div>
    
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Saldo Saat Ini</label>
                            <div class="relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span></div>
                                <input type="text" id="edit_saldo_display" disabled value="{{ number_format($rekening->saldo, 0, ',', '.') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 cursor-not-allowed text-gray-500 dark:text-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm pl-12">
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Saldo hanya berubah otomatis melalui transaksi.</p>
                        </div>
                    </div>
                </x-form-section>

                <div class="flex items-center justify-end space-x-3 pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <x-button variant="secondary" type="button" tag="a" href="{{ route('master-data.rekening.index') }}">
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
