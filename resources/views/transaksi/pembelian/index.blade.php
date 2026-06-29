@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi'],
        ['label' => 'Penerimaan Barang'],
    ]" />

    <x-page-header title="Penerimaan Barang" subtitle="Pilih jenis penerimaan barang untuk stok operasional kandang atau bibit ayam" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 max-w-4xl mx-auto">
        
        {{-- Pembelian Material Gudang --}}
        <x-card class="h-full flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center shrink-0">
                    <span class="text-3xl">📦</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Material Gudang</h3>
            </div>
            <div class="flex-1 text-sm text-gray-600 dark:text-gray-400 mb-6 flex flex-col">
                <p class="mb-3 font-semibold text-gray-700 dark:text-gray-300">Stok Material Saat Ini:</p>
                <div class="space-y-3 overflow-y-auto max-h-40 pr-2 custom-scrollbar">
                    @php
                        $materialColors = [
                            'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',
                            'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200',
                            'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200'
                        ];
                    @endphp
                    @forelse($stokMaterial as $index => $material)
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg {{ $materialColors[$index % count($materialColors)] }}">
                            <span class="truncate pr-2 font-medium">{{ $material->nama_barang }}:</span>
                            <span class="font-bold shrink-0">{{ number_format($material->stok_barang, 0, ',', '.') }} {{ $material->satuan }}</span>
                        </div>
                    @empty
                        <div class="flex justify-between items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                            <span class="font-medium">Material Gudang:</span>
                            <span class="font-bold">0</span>
                        </div>
                    @endforelse
                </div>
            </div>
            <a href="{{ route('transaksi.pembelian.create', ['jenis' => 'material']) }}" class="block mt-auto">
                <x-button type="button" class="w-full justify-center bg-blue-600 hover:bg-blue-700 text-white">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </x-button>
            </a>
        </x-card>

        {{-- Pembelian Pullet Ayam --}}
        <x-card class="h-full flex flex-col p-6 border border-gray-200 dark:border-gray-700 hover:border-orange-400 dark:hover:border-orange-500 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center shrink-0">
                    <span class="text-3xl">🐣</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Pullet (Bibit Ayam)</h3>
            </div>
            <div class="flex-1 text-sm text-gray-600 dark:text-gray-400 mb-6">
                <p class="mb-2">Beli ayam muda (pullet) untuk populasi kandang baru.</p>
                <div class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg border border-orange-100 dark:border-orange-800/50 mt-4">
                    <span class="block font-medium text-orange-800 dark:text-orange-300 mb-1">Informasi:</span>
                    Akan otomatis membuat <strong>Batch</strong> baru dengan status <em>"Pending"</em> yang siap dimasukkan ke kandang setelah transaksi selesai.
                </div>
            </div>
            <a href="{{ route('transaksi.pembelian.create', ['jenis' => 'pullet']) }}" class="block mt-auto">
                <x-button type="button" class="w-full justify-center bg-orange-600 hover:bg-orange-700 text-white">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </x-button>
            </a>
        </x-card>

    </div>
@endsection
