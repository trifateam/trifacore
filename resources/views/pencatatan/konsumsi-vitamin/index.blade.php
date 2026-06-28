@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Konsumsi Vitamin'],
    ]" />

    <x-page-header title="Pilih Kandang & Batch" subtitle="Pilih kandang untuk melakukan pencatatan konsumsi vitamin harian ({{ \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y') }})" />

    @if($kandangData->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kandangData as $kandang)
                <x-card class="flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5 flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $kandang['nama_kandang'] }}</h3>
                            <div class="p-2 bg-violet-50 text-violet-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                </svg>
                            </div>
                        </div>


                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700 pb-2">Daftar Batch Aktif</h4>
                            
                            @if(count($kandang['batches']) > 0)
                                <div class="space-y-3">
                                    @foreach($kandang['batches'] as $batch)
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-100">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <span class="block font-semibold text-gray-900 dark:text-gray-100">{{ $batch['nama_batch'] }}</span>
                                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ number_format($batch['jumlah_sisa'], 0, ',', '.') }} ekor</span>
                                                </div>
                                                <div>
                                                    @if($batch['sudah_tercatat'])
                                                        <x-badge variant="success">Sudah Tercatat</x-badge>
                                                    @else
                                                        <x-badge variant="danger">Belum Tercatat</x-badge>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 flex justify-end">
                                                <a href="{{ route('pencatatan.konsumsi-vitamin.create', $batch['id_batch']) }}">
                                                    <x-button variant="primary" size="sm" class="w-full justify-center">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                        Catat Vitamin
                                                    </x-button>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 dark:text-gray-400 italic">Tidak ada batch aktif di kandang ini.</p>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @else
        <x-empty-state message="Belum ada data kandang yang aktif" icon="home" />
    @endif
@endsection
