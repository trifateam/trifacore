@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi'],
        ['label' => 'Penjualan'],
    ]" />

    <x-page-header title="Transaksi Penjualan" subtitle="Pilih jenis komoditas yang akan dijual kepada pelanggan" />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        
        {{-- Penjualan Telur --}}
        <x-card class="h-full flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center shrink-0">
                    <span class="text-3xl">🥚</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Penjualan Telur</h3>
            </div>
            <div class="flex-1 text-sm text-gray-600 dark:text-gray-400 mb-6 flex flex-col">
                <p class="mb-3 font-semibold text-gray-700 dark:text-gray-300">Stok Tersedia:</p>
                <div class="grid grid-cols-2 gap-3">
                    @php
                        $colors = [
                            'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200',
                            'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                            'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-200'
                        ];
                    @endphp
                    @forelse($stokTelur as $index => $telur)
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg {{ $colors[$index % count($colors)] }}">
                            <span class="font-medium">{{ str_replace('Telur ', '', $telur->nama_barang) }}:</span>
                            <span class="font-bold">{{ number_format($telur->stok_barang, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                            <span class="font-medium">Telur:</span>
                            <span class="font-bold">0</span>
                        </div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('transaksi.penjualan.create', ['jenis' => 'telur']) }}" class="block mt-auto">
                <x-button type="button" class="w-full justify-center bg-amber-600 hover:bg-amber-700 text-white">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </x-button>
            </a>
        </x-card>

        {{-- Penjualan Ayam Afkir --}}
        <x-card class="h-full flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:border-red-400 dark:hover:border-red-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-full bg-red-100 dark:bg-red-900/50 flex items-center justify-center shrink-0">
                    <span class="text-3xl">🐔</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Ayam Afkir</h3>
            </div>
            <div class="flex-1 text-sm text-gray-600 dark:text-gray-400 mb-6 flex flex-col">
                <p class="mb-3 font-semibold text-gray-700 dark:text-gray-300">Stok / Populasi Afkir:</p>
                <div class="space-y-3">
                    @php
                        $ayamColors = [
                            'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',
                            'bg-orange-100 text-orange-800 dark:bg-orange-900/40 dark:text-orange-200'
                        ];
                    @endphp
                    @forelse($stokAyam as $index => $ayam)
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg {{ $ayamColors[$index % count($ayamColors)] }}">
                            <span class="font-medium">{{ $ayam->nama_barang }}:</span>
                            <span class="font-bold">{{ number_format($ayam->stok_barang, 0, ',', '.') }} Ekor</span>
                        </div>
                    @empty
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200">
                            <span class="font-medium">Ayam Afkir:</span>
                            <span class="font-bold">0 Ekor</span>
                        </div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('transaksi.penjualan.create', ['jenis' => 'afkir']) }}" class="block mt-auto">
                <x-button type="button" class="w-full justify-center bg-red-600 hover:bg-red-700 text-white">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </x-button>
            </a>
        </x-card>

        {{-- Penjualan Pupuk --}}
        <x-card class="h-full flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center shrink-0">
                    <span class="text-3xl">💩</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Pupuk Kandang</h3>
            </div>
            <div class="flex-1 text-sm text-gray-600 dark:text-gray-400 mb-6 flex flex-col">
                <p class="mb-3 font-semibold text-gray-700 dark:text-gray-300">Stok Pupuk:</p>
                <div class="space-y-3">
                    @php
                        $pupukColors = [
                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
                            'bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-200'
                        ];
                    @endphp
                    @forelse($stokPupuk as $index => $pupuk)
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg {{ $pupukColors[$index % count($pupukColors)] }}">
                            <span class="font-medium">{{ $pupuk->nama_barang }}:</span>
                            <span class="font-bold">{{ number_format($pupuk->stok_barang, 0, ',', '.') }} Karung</span>
                        </div>
                    @empty
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">
                            <span class="font-medium">Pupuk Kandang:</span>
                            <span class="font-bold">0 Karung</span>
                        </div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('transaksi.penjualan.create', ['jenis' => 'pupuk']) }}" class="block mt-auto">
                <x-button type="button" class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 text-white">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </x-button>
            </a>
        </x-card>

    </div>
@endsection
