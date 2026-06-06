@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Konsumsi Vitamin', 'url' => route('pencatatan.konsumsi-vitamin.index')],
        ['label' => 'Catat Vitamin'],
    ]" />

    <x-page-header title="Catat Konsumsi Vitamin" subtitle="Masukkan data vitamin/obat yang diberikan kepada ayam" />

    <div class="max-w-3xl mx-auto mt-6">
        <form method="POST" action="{{ route('pencatatan.konsumsi-vitamin.store', $batch->id_batch) }}">
            @csrf

            <x-card class="mb-6 border border-gray-200">
                {{-- Header Info --}}
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Informasi Kandang & Batch</h3>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $batch->kandang->nama_kandang }} &mdash; {{ $batch->nama_batch }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal</h3>
                        <p class="mt-1 text-lg font-bold text-violet-600">
                            {{ \Carbon\Carbon::parse($hariIni)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="p-6">
                    <x-form-section title="Detail Vitamin" description="Pilih jenis vitamin dari gudang, tentukan dosis dan total penggunaan.">
                        <div class="space-y-5">
                            
                            {{-- Dropdown Jenis Vitamin --}}
                            <div>
                                <label for="id_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Vitamin <span class="text-red-500">*</span>
                                </label>
                                <select name="id_barang" id="id_barang" required
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('id_barang') ? 'border-red-500' : '' }}">
                                    <option value="" disabled selected>-- Pilih Jenis Vitamin --</option>
                                    @foreach($vitaminList as $vitamin)
                                        <option value="{{ $vitamin->id_barang }}" {{ old('id_barang') == $vitamin->id_barang ? 'selected' : '' }}>
                                            {{ $vitamin->nama_barang }} (Stok: {{ number_format($vitamin->stok_barang, 2, ',', '.') }} {{ $vitamin->satuan ?? 'unit' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_barang') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500">Hanya menampilkan barang dengan kategori 'Vitamin'.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Input Dosis --}}
                                <div>
                                    <label for="dosis" class="block text-sm font-medium text-gray-700 mb-1">
                                        Dosis per Ayam
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" min="0" name="dosis" id="dosis"
                                            value="{{ old('dosis') }}" placeholder="0.00"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-16 {{ $errors->has('dosis') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">dosis</span>
                                        </div>
                                    </div>
                                    @error('dosis') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Jumlah dosis per ekor ayam (opsional).</p>
                                </div>

                                {{-- Input Total Penggunaan --}}
                                <div>
                                    <label for="total_penggunaan" class="block text-sm font-medium text-gray-700 mb-1">
                                        Total Penggunaan <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" min="0.01" name="total_penggunaan" id="total_penggunaan" required
                                            value="{{ old('total_penggunaan') }}" placeholder="0.00"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-16 {{ $errors->has('total_penggunaan') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">unit</span>
                                        </div>
                                    </div>
                                    @error('total_penggunaan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Total vitamin terpakai (dalam satuan barang).</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Dropdown Metode Pemberian --}}
                                <div>
                                    <label for="metode_pemberian" class="block text-sm font-medium text-gray-700 mb-1">
                                        Metode Pemberian
                                    </label>
                                    <select name="metode_pemberian" id="metode_pemberian"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('metode_pemberian') ? 'border-red-500' : '' }}">
                                        <option value="" {{ old('metode_pemberian') == '' ? 'selected' : '' }}>-- Tidak Ditentukan --</option>
                                        <option value="Air Minum" {{ old('metode_pemberian') == 'Air Minum' ? 'selected' : '' }}>Air Minum</option>
                                        <option value="Pakan" {{ old('metode_pemberian') == 'Pakan' ? 'selected' : '' }}>Pakan</option>
                                        <option value="Suntik" {{ old('metode_pemberian') == 'Suntik' ? 'selected' : '' }}>Suntik</option>
                                    </select>
                                    @error('metode_pemberian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Cara pemberian vitamin (opsional).</p>
                                </div>

                                {{-- Input Waktu Pemberian --}}
                                <div>
                                    <label for="waktu_pemberian" class="block text-sm font-medium text-gray-700 mb-1">
                                        Waktu Pemberian
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="time" name="waktu_pemberian" id="waktu_pemberian" 
                                            value="{{ old('waktu_pemberian', \Carbon\Carbon::now()->format('H:i')) }}" 
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('waktu_pemberian') ? 'border-red-500' : '' }}">
                                    </div>
                                    @error('waktu_pemberian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menggunakan waktu saat ini.</p>
                                </div>
                            </div>

                        </div>
                    </x-form-section>

                    {{-- Warning Notice --}}
                    <div class="mt-6 p-4 rounded-lg bg-yellow-50 border border-yellow-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Menyimpan form ini akan secara otomatis memotong stok vitamin yang dipilih di sistem gudang utama. Pastikan jenis vitamin, dosis, dan total penggunaan sudah sesuai.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan.konsumsi-vitamin.index') }}">
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
