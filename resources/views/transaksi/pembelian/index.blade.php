@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi'],
        ['label' => 'Pembelian'],
    ]" />

    <x-page-header title="Transaksi Pembelian" subtitle="Pilih jenis pembelian untuk stok operasional kandang atau bibit ayam" />

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 max-w-4xl mx-auto">
        
        {{-- Pembelian Material Gudang --}}
        <a href="{{ route('transaksi.pembelian.create', ['jenis' => 'material']) }}" class="group block h-full">
            <x-card class="h-full flex flex-col items-center justify-center p-10 text-center border-2 border-transparent hover:border-blue-400 hover:shadow-xl transition-all duration-300 bg-gradient-to-b from-white to-blue-50">
                <div class="w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-5xl">📦</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">Material Gudang</h3>
                <p class="text-gray-600 dark:text-gray-400">Beli Pakan, Vitamin, Obat, atau perlengkapan lainnya. Stok barang di gudang akan otomatis bertambah setelah transaksi berhasil.</p>
                <div class="mt-8 flex items-center text-blue-600 dark:text-blue-500 font-bold text-sm group-hover:translate-x-2 transition-transform duration-300 uppercase tracking-wider">
                    Buat Transaksi
                    <svg class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </x-card>
        </a>

        {{-- Pembelian Pullet Ayam --}}
        <a href="{{ route('transaksi.pembelian.create', ['jenis' => 'pullet']) }}" class="group block h-full">
            <x-card class="h-full flex flex-col items-center justify-center p-10 text-center border-2 border-transparent hover:border-orange-400 hover:shadow-xl transition-all duration-300 bg-gradient-to-b from-white to-orange-50">
                <div class="w-24 h-24 rounded-full bg-orange-100 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-5xl">🐣</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">Pullet (Bibit Ayam)</h3>
                <p class="text-gray-600 dark:text-gray-400">Beli ayam muda (pullet) untuk populasi kandang. Akan otomatis membuat Batch baru dengan status "Pending" yang siap dimasukkan ke kandang.</p>
                <div class="mt-8 flex items-center text-orange-600 font-bold text-sm group-hover:translate-x-2 transition-transform duration-300 uppercase tracking-wider">
                    Buat Transaksi
                    <svg class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </x-card>
        </a>

    </div>
@endsection
