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
        <a href="{{ route('transaksi.penjualan.create', ['jenis' => 'telur']) }}" class="group block h-full">
            <x-card class="h-full flex flex-col items-center justify-center p-8 text-center border-2 border-transparent hover:border-amber-400 hover:shadow-xl transition-all duration-300 bg-gradient-to-b from-white to-amber-50">
                <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-4xl">🥚</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Penjualan Telur</h3>
                <p class="text-gray-600 text-sm">Jual telur RB, MB, MK, atau Pecah. Akan otomatis memotong stok telur di gudang.</p>
                <div class="mt-6 flex items-center text-amber-600 font-semibold text-sm group-hover:translate-x-2 transition-transform duration-300">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </x-card>
        </a>

        {{-- Penjualan Ayam Afkir --}}
        <a href="{{ route('transaksi.penjualan.create', ['jenis' => 'afkir']) }}" class="group block h-full">
            <x-card class="h-full flex flex-col items-center justify-center p-8 text-center border-2 border-transparent hover:border-red-400 hover:shadow-xl transition-all duration-300 bg-gradient-to-b from-white to-red-50">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-4xl">🐔</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Ayam Afkir</h3>
                <p class="text-gray-600 text-sm">Jual ayam afkir atau cacat. Akan otomatis mengurangi populasi ayam pada kandang target.</p>
                <div class="mt-6 flex items-center text-red-600 font-semibold text-sm group-hover:translate-x-2 transition-transform duration-300">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </x-card>
        </a>

        {{-- Penjualan Pupuk --}}
        <a href="{{ route('transaksi.penjualan.create', ['jenis' => 'pupuk']) }}" class="group block h-full">
            <x-card class="h-full flex flex-col items-center justify-center p-8 text-center border-2 border-transparent hover:border-emerald-400 hover:shadow-xl transition-all duration-300 bg-gradient-to-b from-white to-emerald-50">
                <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span class="text-4xl">💩</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Pupuk Kandang</h3>
                <p class="text-gray-600 text-sm">Jual limbah kotoran / pupuk kandang. Akan otomatis memotong stok pupuk di gudang.</p>
                <div class="mt-6 flex items-center text-emerald-600 font-semibold text-sm group-hover:translate-x-2 transition-transform duration-300">
                    Buat Transaksi
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </x-card>
        </a>

    </div>
@endsection
