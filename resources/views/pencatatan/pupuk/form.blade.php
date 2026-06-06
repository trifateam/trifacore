@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Produksi Pupuk', 'url' => route('pencatatan.pupuk.index')],
        ['label' => 'Catat Pupuk'],
    ]" />

    <x-page-header title="Catat Produksi Pupuk Kandang" subtitle="Masukkan data hasil pengumpulan kotoran ayam" />

    <div class="max-w-3xl mx-auto mt-6">
        <form method="POST" action="{{ route('pencatatan.pupuk.store', $kandang->id_kandang) }}">
            @csrf

            <x-card class="mb-6 border border-gray-200">
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Kandang</h3>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $kandang->nama_kandang }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal</h3>
                        <p class="mt-1 text-lg font-bold text-amber-600">
                            {{ \Carbon\Carbon::parse($hariIni)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="p-6">
                    <x-form-section title="Detail Produksi Pupuk" description="Masukkan jumlah karung dan total berat pupuk yang dikumpulkan.">
                        <div class="space-y-5">

                            {{-- Tanggal Kumpul --}}
                            <div>
                                <label for="tanggal_kumpul" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Kumpul <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_kumpul" id="tanggal_kumpul" required
                                    value="{{ old('tanggal_kumpul', $hariIni) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('tanggal_kumpul') ? 'border-red-500' : '' }}">
                                @error('tanggal_kumpul') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Jumlah Karung --}}
                                <div>
                                    <label for="jumlah_karung" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jumlah Karung <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="1" min="0" name="jumlah_karung" id="jumlah_karung" required
                                            value="{{ old('jumlah_karung', 0) }}" placeholder="0"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-20 {{ $errors->has('jumlah_karung') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">karung</span>
                                        </div>
                                    </div>
                                    @error('jumlah_karung') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                {{-- Total Berat --}}
                                <div>
                                    <label for="total_berat_kg" class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Berat <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" min="0" name="total_berat_kg" id="total_berat_kg" required
                                            value="{{ old('total_berat_kg', 0) }}" placeholder="0.00"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('total_berat_kg') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">Kg</span>
                                        </div>
                                    </div>
                                    @error('total_berat_kg') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </x-form-section>

                    <div class="mt-6 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Informasi</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Jika master data barang dengan kategori <strong>"Pupuk"</strong> tersedia, stok pupuk akan otomatis bertambah sebesar total berat yang dicatat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan.pupuk.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Simpan Pencatatan
                </x-button>
            </div>
        </form>
    </div>
@endsection
