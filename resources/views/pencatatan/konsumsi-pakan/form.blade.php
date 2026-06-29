@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Konsumsi Pakan', 'url' => route('pencatatan.konsumsi-pakan.index')],
        ['label' => 'Catat Sesi ' . ($jumlahSesiHariIni + 1)],
    ]" />

    <x-page-header title="Catat Konsumsi Pakan Sesi {{ $jumlahSesiHariIni + 1 }}" subtitle="Masukkan data pakan yang diberikan kepada ayam" />

    <div class="max-w-3xl mx-auto mt-6">
        <form method="POST" action="{{ route('pencatatan.konsumsi-pakan.store', $batch->id_batch) }}">
            @csrf

            <x-card class="mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Informasi Kandang & Batch</h3>
                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">{{ $batch->kandang->nama_kandang }} &mdash; {{ $batch->nama_batch }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal & Sesi</h3>
                        <p class="mt-1 text-lg font-bold text-emerald-600 dark:text-emerald-500">
                            {{ \Carbon\Carbon::parse($hariIni)->translatedFormat('d F Y') }}
                            <span class="text-sm font-medium bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-2 py-0.5 rounded-full ml-2">Sesi {{ $jumlahSesiHariIni + 1 }}</span>
                        </p>
                    </div>
                </div>

                <div class="p-6">
                    <x-form-section title="Detail Pakan" description="Pilih jenis pakan dari gudang dan tentukan berat pakan yang diberikan.">
                        <div class="space-y-5">
                            
                            <div>
                                <label for="id_barang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Jenis Pakan <span class="text-red-500">*</span>
                                </label>
                                <select name="id_barang" id="id_barang" required
                                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('id_barang') ? 'border-red-500' : '' }}">
                                    <option value="" disabled selected>-- Pilih Jenis Pakan --</option>
                                    @foreach($pakanList as $pakan)
                                        <option value="{{ $pakan->id_barang }}" {{ old('id_barang') == $pakan->id_barang ? 'selected' : '' }}>
                                            {{ $pakan->nama_barang }} (Stok: {{ number_format($pakan->stok_barang, 2, ',', '.') }} {{ $pakan->satuan ?? 'kg' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_barang') <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Hanya menampilkan barang dengan kategori 'Pakan'.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="jumlah_pakan_kg" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Berat Pakan (Kg) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" min="0.01" name="jumlah_pakan_kg" id="jumlah_pakan_kg" required
                                            value="{{ old('jumlah_pakan_kg') }}" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('jumlah_pakan_kg') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Kg</span>
                                        </div>
                                    </div>
                                    @error('jumlah_pakan_kg') <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="waktu_pemberian" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Waktu Pemberian
                                    </label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="time" name="waktu_pemberian" id="waktu_pemberian" 
                                            value="{{ old('waktu_pemberian', \Carbon\Carbon::now()->format('H:i')) }}" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('waktu_pemberian') ? 'border-red-500' : '' }}">
                                    </div>
                                    @error('waktu_pemberian') <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan untuk menggunakan waktu saat ini.</p>
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
                                <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Menyimpan form ini akan secara otomatis memotong stok pakan yang dipilih di sistem gudang utama. Pastikan berat pakan dan jenis sudah sesuai.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan.konsumsi-pakan.index') }}">
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
