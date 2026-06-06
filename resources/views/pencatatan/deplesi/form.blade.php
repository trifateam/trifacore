@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Deplesi', 'url' => route('pencatatan.deplesi.index')],
        ['label' => 'Catat Deplesi'],
    ]" />

    <x-page-header title="Catat Deplesi (Kematian/Afkir)" subtitle="Masukkan data kematian dan afkir ayam pada batch ini" />

    <div class="max-w-3xl mx-auto mt-6" x-data="deplesiForm()">
        <form method="POST" action="{{ route('pencatatan.deplesi.store', $batch->id_batch) }}" @submit="return validateForm()">
            @csrf

            <x-card class="mb-6 border border-gray-200">
                {{-- Header Info --}}
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Informasi Kandang & Batch</h3>
                            <p class="mt-1 text-lg font-bold text-gray-900">{{ $batch->kandang->nama_kandang }} &mdash; {{ $batch->nama_batch }}</p>
                        </div>
                        <div class="text-left md:text-right">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Tanggal</h3>
                            <p class="mt-1 text-lg font-bold text-red-600">
                                {{ \Carbon\Carbon::parse($hariIni)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Populasi Saat Ini</span>
                            <span class="text-xl font-bold text-gray-900">{{ number_format($populasiSaatIni, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">ekor</span></span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <x-form-section title="Data Deplesi" description="Masukkan jumlah ayam mati dan afkir/cacat pada hari ini.">
                        <div class="space-y-5">

                            {{-- Jumlah Ayam Mati --}}
                            <div>
                                <label for="jml_mati" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Ayam Mati <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" step="1" min="0" name="jml_mati" id="jml_mati" required
                                        x-model.number="jmlMati"
                                        value="{{ old('jml_mati', 0) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-16 {{ $errors->has('jml_mati') ? 'border-red-500' : '' }}">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">ekor</span>
                                    </div>
                                </div>
                                @error('jml_mati') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jumlah Ayam Afkir --}}
                            <div>
                                <label for="jml_afkir" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Ayam Afkir/Cacat <span class="text-red-500">*</span>
                                </label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="number" step="1" min="0" name="jml_afkir" id="jml_afkir" required
                                        x-model.number="jmlAfkir"
                                        value="{{ old('jml_afkir', 0) }}"
                                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-16 {{ $errors->has('jml_afkir') ? 'border-red-500' : '' }}">
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                        <span class="text-gray-500 sm:text-sm">ekor</span>
                                    </div>
                                </div>
                                @error('jml_afkir') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Total Deplesi (auto-calculated) --}}
                            <div class="p-4 rounded-lg border-2" :class="totalDeplesi > {{ $populasiSaatIni }} ? 'bg-red-50 border-red-300' : (totalDeplesi > 0 ? 'bg-amber-50 border-amber-300' : 'bg-gray-50 border-gray-200')">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-700">Total Deplesi</span>
                                        <p class="text-xs text-gray-500 mt-0.5">Mati + Afkir (otomatis)</p>
                                    </div>
                                    <span class="text-2xl font-bold" :class="totalDeplesi > {{ $populasiSaatIni }} ? 'text-red-600' : 'text-gray-900'" x-text="totalDeplesi + ' ekor'"></span>
                                </div>
                                <template x-if="totalDeplesi > {{ $populasiSaatIni }}">
                                    <p class="mt-2 text-sm text-red-600 font-medium">
                                        ⚠ Total deplesi (<span x-text="totalDeplesi"></span> ekor) melebihi populasi saat ini ({{ number_format($populasiSaatIni, 0, ',', '.') }} ekor)
                                    </p>
                                </template>
                                <template x-if="totalDeplesi > 0 && totalDeplesi <= {{ $populasiSaatIni }}">
                                    <p class="mt-2 text-sm text-amber-700">
                                        Populasi setelah deplesi: <strong x-text="({{ $populasiSaatIni }} - totalDeplesi) + ' ekor'"></strong>
                                    </p>
                                </template>
                            </div>

                        </div>
                    </x-form-section>

                    {{-- Warning Notice --}}
                    <div class="mt-6 p-4 rounded-lg bg-red-50 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Perhatian Penting</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>Menyimpan form ini akan secara otomatis <strong>mengurangi populasi kandang</strong>. Data deplesi hanya dapat dicatat <strong>1x per hari per batch</strong>. Pastikan jumlah sudah benar sebelum menyimpan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('pencatatan.deplesi.index') }}">
                    <x-button variant="secondary" type="button">Batal</x-button>
                </a>
                <x-button variant="danger" type="submit" :disabled="false" x-bind:disabled="totalDeplesi <= 0 || totalDeplesi > {{ $populasiSaatIni }}">
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
    function deplesiForm() {
        return {
            jmlMati: {{ old('jml_mati', 0) }},
            jmlAfkir: {{ old('jml_afkir', 0) }},
            populasi: {{ $populasiSaatIni }},
            get totalDeplesi() {
                return (parseInt(this.jmlMati) || 0) + (parseInt(this.jmlAfkir) || 0);
            },
            validateForm() {
                if (this.totalDeplesi <= 0) {
                    alert('Minimal salah satu kategori (Mati atau Afkir) harus lebih dari 0.');
                    return false;
                }
                if (this.totalDeplesi > this.populasi) {
                    alert('Total deplesi (' + this.totalDeplesi + ' ekor) melebihi populasi saat ini (' + this.populasi + ' ekor).');
                    return false;
                }
                return true;
            }
        }
    }
</script>
@endsection
