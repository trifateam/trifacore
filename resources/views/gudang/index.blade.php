@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Inventory Gudang'],
    ]" />

    <x-page-header :title="$pageTitle" :subtitle="$pageSubtitle" />

    <div class="mt-6 space-y-6">

        {{-- Banner Alert for Critical/Empty Stocks --}}
        @if($countKritis > 0 || $countHabis > 0)
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-md flex items-start shadow-sm">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Peringatan Persediaan Barang!</h3>
                    <div class="mt-1 text-sm text-red-700 dark:text-red-400">
                        <p>Terdapat <strong>{{ $countKritis }} barang</strong> dengan stok kritis dan <strong>{{ $countHabis }} barang</strong> yang kehabisan stok. Segera lakukan pengadaan (restock) untuk menghindari gangguan operasional.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter Bar --}}
        <x-card class="border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Nama Barang</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cth: Pakan Starter..." class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Barang</label>
                        <select name="kategori" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Kategori --</option>
                            @php $kategoris = ['Pakan', 'Vitamin', 'Obat', 'Telur', 'Pupuk', 'Ayam', 'Lainnya']; @endphp
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status Stok</label>
                        <select name="status" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Status --</option>
                            <option value="Normal" {{ request('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Warning" {{ request('status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                            <option value="Kritis" {{ request('status') == 'Kritis' ? 'selected' : '' }}>Kritis</option>
                            <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <x-button type="submit" variant="primary" class="w-full h-9 px-4 flex items-center justify-center">
                                Filter
                            </x-button>
                        </div>
                        <div class="flex-1">
                            <a href="{{ url()->current() }}" class="w-full h-9 px-4 inline-flex items-center justify-center border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:bg-gray-700/50 transition-colors">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </x-card>

        {{-- Tabel Inventory --}}
        <x-card class="border border-gray-200 dark:border-gray-700">
            <x-table :headers="['No', 'Nama Barang', 'Kategori', 'Stok Saat Ini', 'Satuan', 'Stok Minimum', 'Status', 'Aksi']">
                @forelse($paginatedBarang as $index => $barang)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paginatedBarang->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $barang->kategori_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">{{ number_format($barang->stok_barang, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $barang->satuan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($barang->stok_minimum, 2, ',', '.') }}</td>
                        <td class="whitespace-nowrap text-sm">
                            @if($barang->badge_color == 'dark')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-white">Habis</span>
                            @else
                                <x-badge :variant="$barang->badge_color">{{ $barang->status_stok }}</x-badge>
                            @endif
                        </td>
                        <td class="whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('gudang.adjust.form', $barang->id_barang) }}" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                                Sesuaikan Stok
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10">
                            <x-empty-state 
                                icon="inbox" 
                                title="Barang Tidak Ditemukan" 
                                description="Tidak ada barang di inventory yang sesuai dengan filter pencarian Anda." 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
            
            @if($paginatedBarang->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $paginatedBarang->links() }}
                </div>
            @endif
        </x-card>

@endsection
