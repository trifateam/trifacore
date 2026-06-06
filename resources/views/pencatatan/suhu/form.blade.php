@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Suhu Kandang', 'url' => route('pencatatan.suhu.index')],
        ['label' => 'Catat Suhu'],
    ]" />

    <x-page-header title="Catat Suhu Kandang" subtitle="Masukkan data suhu dan kelembaban lingkungan kandang" />

    <div class="max-w-3xl mx-auto mt-6" x-data="suhuForm()">
        <form method="POST" action="{{ route('pencatatan.suhu.store', $kandang->id_kandang) }}">
            @csrf

            <x-card class="mb-6 border border-gray-200">
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Kandang</h3>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $kandang->nama_kandang }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal</h3>
                        <p class="mt-1 text-lg font-bold text-sky-600">
                            {{ \Carbon\Carbon::parse($hariIni)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="p-6">
                    <x-form-section title="Data Suhu & Kelembaban" description="Catat kondisi suhu lingkungan kandang saat ini.">
                        <div class="space-y-5">

                            {{-- Tanggal Waktu --}}
                            <div>
                                <label for="tanggal_waktu" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal & Waktu <span class="text-red-500">*</span>
                                </label>
                                <input type="datetime-local" name="tanggal_waktu" id="tanggal_waktu" required
                                    value="{{ old('tanggal_waktu', \Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm {{ $errors->has('tanggal_waktu') ? 'border-red-500' : '' }}">
                                @error('tanggal_waktu') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Suhu Utama --}}
                            <div>
                                <label for="suhu" class="block text-sm font-medium text-gray-700 mb-1">
                                    Suhu (°C) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" step="0.01" name="suhu" id="suhu" required
                                        x-model="suhu"
                                        value="{{ old('suhu') }}" placeholder="0.00"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('suhu') ? 'border-red-500' : '' }}">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">°C</span>
                                    </div>
                                </div>
                                @error('suhu') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Warning suhu abnormal --}}
                            <template x-if="suhu !== '' && suhu !== null && parseFloat(suhu) > 35">
                                <div class="p-3 rounded-lg bg-red-50 border border-red-200 flex items-start gap-2">
                                    <span class="text-lg">⚠️</span>
                                    <div>
                                        <p class="text-sm font-medium text-red-800">Suhu tinggi!</p>
                                        <p class="text-xs text-red-600">Risiko heat stress pada ayam meningkat. Segera cek ventilasi dan ketersediaan air minum.</p>
                                    </div>
                                </div>
                            </template>
                            <template x-if="suhu !== '' && suhu !== null && parseFloat(suhu) < 18">
                                <div class="p-3 rounded-lg bg-blue-50 border border-blue-200 flex items-start gap-2">
                                    <span class="text-lg">⚠️</span>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Suhu rendah!</p>
                                        <p class="text-xs text-blue-600">Risiko mortalitas meningkat. Pastikan pemanas dan insulasi berfungsi baik.</p>
                                    </div>
                                </div>
                            </template>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                {{-- Suhu Min --}}
                                <div>
                                    <label for="suhu_min" class="block text-sm font-medium text-gray-700 mb-1">Suhu Min (°C)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" name="suhu_min" id="suhu_min"
                                            value="{{ old('suhu_min') }}" placeholder="0.00"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('suhu_min') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">°C</span>
                                        </div>
                                    </div>
                                    @error('suhu_min') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Opsional — suhu terendah hari ini.</p>
                                </div>

                                {{-- Suhu Max --}}
                                <div>
                                    <label for="suhu_max" class="block text-sm font-medium text-gray-700 mb-1">Suhu Max (°C)</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" name="suhu_max" id="suhu_max"
                                            value="{{ old('suhu_max') }}" placeholder="0.00"
                                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('suhu_max') ? 'border-red-500' : '' }}">
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <span class="text-gray-500 sm:text-sm">°C</span>
                                        </div>
                                    </div>
                                    @error('suhu_max') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    <p class="mt-1 text-xs text-gray-500">Opsional — suhu tertinggi hari ini.</p>
                                </div>
                            </div>

                            {{-- Kelembaban --}}
                            <div>
                                <label for="kelembaban" class="block text-sm font-medium text-gray-700 mb-1">Kelembaban (%)</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" step="0.01" min="0" max="100" name="kelembaban" id="kelembaban"
                                        value="{{ old('kelembaban') }}" placeholder="0.00"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12 {{ $errors->has('kelembaban') ? 'border-red-500' : '' }}">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                                @error('kelembaban') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                <p class="mt-1 text-xs text-gray-500">Opsional — tingkat kelembaban udara.</p>
                            </div>
                        </div>
                    </x-form-section>
                </div>
            </x-card>

            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan.suhu.index') }}">
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

@section('scripts')
<script>
    function suhuForm() {
        return {
            suhu: '{{ old("suhu", "") }}',
        }
    }
</script>
@endsection
