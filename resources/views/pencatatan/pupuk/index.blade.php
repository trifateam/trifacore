@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Produksi Pupuk Kandang'],
    ]" />

    <x-page-header title="Pilih Kandang" subtitle="Pilih kandang untuk melakukan pencatatan produksi pupuk ({{ \Carbon\Carbon::parse($hariIni)->translatedFormat('l, d F Y') }})" />

    @if($kandangData->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kandangData as $kandang)
                <x-card class="flex flex-col h-full border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                    <div class="p-5 flex-1">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $kandang['nama_kandang'] }}</h3>
                            <div class="p-2 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-500 rounded-lg">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                </svg>
                            </div>
                        </div>

                        <div class="space-y-2 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Populasi Saat Ini</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($kandang['populasi_saat_ini'], 0, ',', '.') }} ekor</span>
                            </div>
                        </div>

                        <div class="mt-3 flex justify-end">
                            <a href="{{ route('pencatatan.pupuk.create', $kandang['id_kandang']) }}" class="w-full">
                                <x-button variant="primary" size="sm" class="w-full justify-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                    Catat Pupuk
                                </x-button>
                            </a>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @else
        <x-empty-state message="Belum ada data kandang yang aktif" icon="home" />
    @endif
@endsection
