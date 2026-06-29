@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Data Kandang'],
    ]" />

    <x-page-header title="Data Kandang" subtitle="Monitor populasi dan kapasitas kandang aktif yang ada saat ini" />

    <div class="mt-6 space-y-10">

        {{-- SECTION 1: POPULASI KANDANG AKTIF --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Populasi Kandang Aktif
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($kandangs as $kandang)
                    @php
                        $percentage = $kandang->kapasitas_kandang > 0 ? round(($kandang->populasi_saat_ini / $kandang->kapasitas_kandang) * 100) : 0;
                        $percentage = min(100, max(0, $percentage)); // Clamp 0-100
                        
                        $gaugeColor = 'bg-emerald-500';
                        $bgGaugeColor = 'bg-emerald-100 dark:bg-emerald-900/50';
                        if ($percentage >= 90) {
                            $gaugeColor = 'bg-red-500';
                            $bgGaugeColor = 'bg-red-100 dark:bg-red-900/50';
                        } elseif ($percentage >= 70) {
                            $gaugeColor = 'bg-amber-500';
                            $bgGaugeColor = 'bg-amber-100 dark:bg-amber-900/50';
                        }
                    @endphp

                    <x-card class="border border-gray-200 dark:border-gray-700 flex flex-col h-full overflow-hidden">
                        <div class="p-5 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $kandang->nama_kandang }}</h3>
                                <div class="text-right">
                                    <span class="block text-2xl font-black text-gray-900 dark:text-gray-100">{{ number_format($kandang->populasi_saat_ini, 0, ',', '.') }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">Total Ekor</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kapasitas Maksimal: {{ number_format($kandang->kapasitas_kandang, 0, ',', '.') }} ekor</p>

                            {{-- Gauge Bar --}}
                            <div class="mb-2 flex justify-between text-xs font-bold">
                                <span class="text-gray-700 dark:text-gray-300">Kepenuhan</span>
                                <span class="{{ $percentage >= 90 ? 'text-red-600 dark:text-red-500' : ($percentage >= 70 ? 'text-amber-600 dark:text-amber-500' : 'text-emerald-600 dark:text-emerald-500') }}">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full {{ $bgGaugeColor }} rounded-full h-2.5 mb-6">
                                <div class="{{ $gaugeColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>

                            {{-- Accordion Batches --}}
                            <div x-data="{ expanded: false }" class="border border-gray-100 rounded-lg overflow-hidden">
                                <button @click="expanded = !expanded" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:bg-gray-700 flex justify-between items-center text-sm font-bold text-gray-700 dark:text-gray-300 transition-colors">
                                    <span>Detail Batch ({{ $kandang->batches->count() }})</span>
                                    <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                
                                <div x-show="expanded" x-collapse>
                                    <div class="divide-y divide-gray-100">
                                        @forelse($kandang->batches as $kb)
                                            <div class="p-3 bg-white dark:bg-gray-800 text-sm">
                                                <div class="flex justify-between font-bold text-gray-800 dark:text-gray-200 mb-1">
                                                    <span>{{ $kb->nama_batch }}</span>
                                                    <span>{{ number_format($kb->jumlah_sisa, 0, ',', '.') }} ekor</span>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                                    <span>{{ $kb->jenis_ayam }}</span>
                                                    <span>{{ \Carbon\Carbon::parse($kb->tgl_masuk)->translatedFormat('d M Y') }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-3 bg-white dark:bg-gray-800 text-sm text-center text-gray-500 dark:text-gray-400 italic">
                                                Tidak ada batch di kandang ini.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <div class="lg:col-span-2 xl:col-span-3">
                        <x-card class="border border-gray-200 dark:border-gray-700">
                            <x-empty-state 
                                icon="building-office" 
                                title="Tidak Ada Kandang Aktif" 
                                description="Sistem tidak menemukan kandang yang berstatus aktif." 
                            />
                        </x-card>
                    </div>
                @endforelse
            </div>
        </div>

@endsection
