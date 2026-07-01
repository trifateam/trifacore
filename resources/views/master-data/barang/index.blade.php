@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Barang/Item'],
    ]" />

    <x-page-header title="Data Barang/Item" subtitle="Kelola data barang dan material peternakan">
        <x-slot:action>
            <x-button variant="primary" tag="a" href="{{ route('master-data.barang.create') }}">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.barang.index')">
        <x-search-field name="search" placeholder="Cari nama barang..." :value="$search" />
        <x-select
            name="kategori"
            label="Kategori"
            :options="array_map(fn($k) => ['value' => $k, 'label' => $k], $kategoriList)"
            :selected="$kategori"
            placeholder="Semua Kategori"
        />
    </x-filter-bar>

    @if($barangs->count() > 0)
        <x-table :headers="['No', 'Nama Barang', 'Kategori', 'SKU', 'Stok', 'Satuan', 'Harga', 'Jual/Beli', 'Aksi']">
            @foreach($barangs as $index => $barang)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $barangs->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $barang->nama_barang }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($barang->kategori_barang) {
                                'Telur' => 'warning',
                                'Pakan' => 'success',
                                'Vitamin' => 'info',
                                'Pupuk' => 'gray',
                                'Obat' => 'danger',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $barang->kategori_barang }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $barang->sku ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                        <span class="{{ $barang->stok_barang <= $barang->stok_minimum && $barang->stok_minimum > 0 ? 'text-red-600 dark:text-red-500 font-semibold' : '' }}">
                            {{ number_format($barang->stok_barang, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $barang->satuan }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">@rupiah($barang->harga)</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-3">
                            <span title="Dapat Dijual">
                                @if($barang->dapat_dijual)
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @endif
                            </span>
                            <span title="Dapat Dibeli">
                                @if($barang->dapat_dibeli)
                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm" tag="a" href="{{ route('master-data.barang.edit', $barang->id_barang) }}">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.barang.destroy', $barang->id_barang) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $barangs->links() }}</div>
    @else
        <x-empty-state message="Belum ada data barang" icon="inbox" />
    @endif



    <x-confirm-dialog
        title="Hapus Data Barang"
        message="Apakah Anda yakin ingin menghapus data barang ini? Barang yang sudah digunakan dalam transaksi tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection


