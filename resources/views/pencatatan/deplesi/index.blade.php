@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Deplesi (Kematian/Cacat)'],
    ]" />

    <x-page-header title="Pilih Kandang & Batch" subtitle="Pilih kandang untuk melakukan pencatatan deplesi harian ({{ \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y') }})" />

    @if($kandangData->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kandangData as $kandang)
                <x-card class="flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5 flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $kandang['nama_kandang'] }}</h3>
                            <div class="p-2 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-500 rounded-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
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
                                                    <x-button variant="secondary" size="sm" class="w-full justify-center opacity-50 cursor-not-allowed" disabled>
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                        Sudah Dicatat Hari Ini
                                                    </x-button>
                                                @else
                                                    <a href="{{ route('pencatatan.deplesi.create', $batch['id_batch']) }}">
                                                        <x-button variant="danger" size="sm" class="w-full justify-center">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                            Catat Deplesi
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
