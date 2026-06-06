@extends('layouts.app')

@php
    $isEdit = isset($produksi);
    $title = $isEdit ? 'Edit Produksi Telur' : 'Catat Produksi Telur Baru';
    $actionUrl = $isEdit ? route('pencatatan.produksi-telur.update', ['batch' => $batch->id_batch, 'produksi' => $produksi->id_produksi]) : route('pencatatan.produksi-telur.store', $batch->id_batch);
    $hariIniView = $isEdit ? \Carbon\Carbon::parse($produksi->tanggal_produksi)->translatedFormat('l, d F Y') : \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y');
@endphp

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Produksi Telur', 'url' => route('pencatatan.produksi-telur.index')],
        ['label' => $isEdit ? 'Edit Data' : 'Tambah Data'],
    ]" />

    <x-page-header :title="$title" subtitle="Lengkapi jumlah produksi telur harian untuk batch ini" />

    <div class="max-w-4xl mx-auto mt-6">
        <form method="POST" action="{{ $actionUrl }}">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <x-card class="mb-6 border border-gray-200">
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Informasi Kandang & Batch</h3>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $batch->kandang->nama_kandang }} &mdash; {{ $batch->nama_batch }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal Pencatatan</h3>
                        <p class="mt-1 text-lg font-bold text-indigo-600">{{ $hariIniView }}</p>
                    </div>
                </div>

                <div class="p-6" 
                    x-data="{
                        rb: {{ old('jml_telur_rb', $produksi->jml_telur_rb ?? 0) }},
                        mb: {{ old('jml_telur_mb', $produksi->jml_telur_mb ?? 0) }},
                        mk: {{ old('jml_telur_mk', $produksi->jml_telur_mk ?? 0) }},
                        pecah: {{ old('jml_telur_pecah', $produksi->jml_telur_pecah ?? 0) }},
                        get total() {
                            return (parseInt(this.rb) || 0) + (parseInt(this.mb) || 0) + (parseInt(this.mk) || 0) + (parseInt(this.pecah) || 0);
                        }
                    }">
                    
                    <x-form-section title="Jumlah Produksi (Butir)" description="Masukkan jumlah telur berdasarkan grade/kategori. Minimal 1 jenis telur harus diisi.">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                            
                            <div class="mb-4">
                                <label for="jml_telur_rb" class="block text-sm font-medium text-gray-700 mb-1">
                                    Telur RB (Rumpai Berkualitas) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jml_telur_rb" id="jml_telur_rb" x-model="rb" min="0" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('jml_telur_rb') ? 'border-red-500' : '' }}">
                                @error('jml_telur_rb') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="jml_telur_mb" class="block text-sm font-medium text-gray-700 mb-1">
                                    Telur MB (Memanjang Biasa) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jml_telur_mb" id="jml_telur_mb" x-model="mb" min="0" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('jml_telur_mb') ? 'border-red-500' : '' }}">
                                @error('jml_telur_mb') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="jml_telur_mk" class="block text-sm font-medium text-gray-700 mb-1">
                                    Telur MK (Memanjang Kecil) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jml_telur_mk" id="jml_telur_mk" x-model="mk" min="0" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('jml_telur_mk') ? 'border-red-500' : '' }}">
                                @error('jml_telur_mk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-4">
                                <label for="jml_telur_pecah" class="block text-sm font-medium text-gray-700 mb-1">
                                    Telur Pecah / Rusak <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="jml_telur_pecah" id="jml_telur_pecah" x-model="pecah" min="0" required 
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('jml_telur_pecah') ? 'border-red-500' : '' }}">
                                @error('jml_telur_pecah') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                        </div>
                    </x-form-section>

                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <x-form-section title="Informasi Tambahan & Total" description="Tinjau total kalkulasi dan masukkan berat total">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Total Telur (Butir)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="text" disabled :value="total" class="w-full rounded-lg border-gray-300 bg-emerald-50 text-emerald-800 font-bold text-lg cursor-not-allowed shadow-sm">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500" x-show="total === 0">Minimal 1 butir telur harus dicatat.</p>
                                </div>

                                <div class="mb-4">
                                    <label for="total_berat_kg" class="block text-sm font-medium text-gray-700 mb-1">Total Berat Aktual (Kg)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" name="total_berat_kg" id="total_berat_kg" min="0" value="{{ old('total_berat_kg', $isEdit ? $produksi->total_berat_kg : 0) }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('total_berat_kg') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">Kg</span>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Opsional, total berat keseluruhan jika ditimbang.</p>
                                </div>

                            </div>
                        </x-form-section>
                    </div>

                </div>
            </x-card>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan.produksi-telur.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Pencatatan' }}
                </x-button>
            </div>
        </form>
    </div>
@endsection
