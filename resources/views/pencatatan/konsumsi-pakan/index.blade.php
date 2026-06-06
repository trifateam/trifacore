@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Konsumsi Pakan'],
    ]" />

    <x-page-header title="Pilih Kandang & Batch" subtitle="Pilih kandang untuk melakukan pencatatan konsumsi pakan harian ({{ \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y') }})" />

    @if($kandangData->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kandangData as $kandang)
                <x-card class="flex flex-col h-full border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5 flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">{{ $kandang['nama_kandang'] }}</h3>
                            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Kapasitas Maksimal</span>
                                <span class="font-medium text-gray-900">{{ number_format($kandang['kapasitas_kandang'], 0, ',', '.') }} ekor</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Populasi Saat Ini</span>
                                <span class="font-medium text-gray-900">{{ number_format($kandang['populasi_saat_ini'], 0, ',', '.') }} ekor</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2">Daftar Batch Aktif</h4>
                            
                            @if(count($kandang['batches']) > 0)
                                <div class="space-y-3">
                                    @foreach($kandang['batches'] as $batch)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <span class="block font-semibold text-gray-900">{{ $batch['nama_batch'] }}</span>
                                                    <span class="block text-xs text-gray-500">{{ number_format($batch['jumlah_sisa'], 0, ',', '.') }} ekor</span>
                                                </div>
                                                <div>
                                                    @if($batch['jumlah_sesi'] == 0)
                                                        <x-badge variant="danger">Belum Tercatat</x-badge>
                                                    @elseif($batch['jumlah_sesi'] == 1)
                                                        <x-badge variant="warning">Tercatat (1 Sesi)</x-badge>
                                                    @else
                                                        <x-badge variant="success">Penuh (2 Sesi)</x-badge>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="mt-3 flex justify-end">
                                                @if($batch['jumlah_sesi'] >= 2)
                                                    <x-button variant="secondary" size="sm" class="w-full justify-center opacity-50 cursor-not-allowed" disabled>
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                        Penuh (Maks 2x)
                                                    </x-button>
                                                @else
                                                    <a href="{{ route('pencatatan.konsumsi-pakan.create', $batch['id_batch']) }}">
                                                        <x-button variant="primary" size="sm" class="w-full justify-center">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                                            Catat Sesi {{ $batch['jumlah_sesi'] + 1 }}
                                                        </x-button>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada batch aktif di kandang ini.</p>
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
