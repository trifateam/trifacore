@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Produksi Telur'],
    ]" />

    <x-page-header title="Pilih Kandang & Batch" subtitle="Pilih kandang untuk melakukan pencatatan produksi telur harian ({{ \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y') }})" />

    @if($kandangData->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kandangData as $kandang)
                <x-card class="flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5 flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $kandang['nama_kandang'] }}</h3>
                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
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
                                                    <span class="block text-xs text-gray-500 dark:text-gray-400">{{ number_format($batch['populasi_saat_ini'], 0, ',', '.') }} ekor</span>
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
                                                @if($batch['sudah_tercatat'])
                                                    <a href="{{ route('pencatatan.produksi-telur.edit', ['batch' => $batch['id_batch'], 'produksi' => $batch['id_produksi']]) }}">
                                                        <x-button variant="secondary" size="sm" class="w-full justify-center">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                                            Edit Data
                                                        </x-button>
                                                    </a>
                                                @else
                                                    <a href="{{ route('pencatatan.produksi-telur.create', $batch['id_batch']) }}">
                                                        <x-button variant="primary" size="sm" class="w-full justify-center">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                            Catat Sekarang
                                                        </x-button>
                                                    </a>
                                                @endif
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
