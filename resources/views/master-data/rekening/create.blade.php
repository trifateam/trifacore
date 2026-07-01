@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Rekening Kas/Bank', 'url' => route('master-data.rekening.index')],
        ['label' => 'Tambah Rekening'],
    ]" />

    <x-page-header title="Tambah Rekening Baru" subtitle="Lengkapi data akun kas atau bank baru" />

    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
        <div class="p-6">
            <form method="POST" action="{{ route('master-data.rekening.store') }}">
                @csrf
                <x-form-section title="Informasi Rekening" description="Lengkapi data akun kas atau bank baru">
                    <div class="space-y-4">
                        <x-input name="nama_akun" label="Nama Bank/Kas" placeholder="Contoh: BCA Utama / Kas Kecil" :required="true" hint="Maksimal 50 karakter, harus unik" />
                        
                        <x-select name="kategori_akun" label="Kategori Akun" :required="true"
                            :options="[
                                ['value' => 'Bank', 'label' => 'Bank'],
                                ['value' => 'Tunai', 'label' => 'Tunai'],
                                ['value' => 'E-Wallet', 'label' => 'E-Wallet'],
                            ]"
                            x-data="{}"
                            x-on:change="document.getElementById('no_rekening_group').style.display = $event.target.value === 'Tunai' ? 'none' : 'block'"
                        />
    
                        <div id="no_rekening_group">
                            <x-input name="no_rekening" label="Nomor Rekening" placeholder="Contoh: 1234567890" hint="Wajib diisi untuk Bank/E-Wallet" />
                        </div>
    
                        <x-input name="nama_pemilik" label="Nama Pemilik (Atas Nama)" placeholder="Contoh: PT TriFaCore" :required="true" />
                        
                        <x-input name="saldo" label="Saldo Awal" type="number" placeholder="0" value="0" :required="true" prefix="Rp" hint="Saldo saat pertama ditambahkan" />
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
                        Simpan Rekening
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@endsection
