@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Supplier'],
    ]" />

    <x-page-header title="Data Supplier" subtitle="Kelola data supplier dan mitra pemasok">
        <x-slot:action>
            <x-button variant="primary" tag="a" href="{{ route('master-data.supplier.create') }}">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.supplier.index')">
        <x-search-field name="search" placeholder="Cari nama supplier..." :value="$search" />
    </x-filter-bar>

    @if($suppliers->count() > 0)
        <x-table :headers="['No', 'Nama Supplier', 'Kontak', 'Alamat', 'PIC', 'Email', 'Aksi']">
            @foreach($suppliers as $index => $supplier)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $suppliers->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $supplier->nama_supplier }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $supplier->kontak_supplier }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate" title="{{ $supplier->alamat_supplier }}">{{ Str::limit($supplier->alamat_supplier, 40) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $supplier->nama_pic ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $supplier->email ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm" tag="a" href="{{ route('master-data.supplier.edit', $supplier->id_supplier) }}">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.supplier.destroy', $supplier->id_supplier) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $suppliers->links() }}</div>
    @else
        <x-empty-state message="Belum ada data supplier" icon="inbox" />
    @endif



    <x-confirm-dialog
        title="Hapus Data Supplier"
        message="Apakah Anda yakin ingin menghapus data supplier ini? Supplier yang masih memiliki transaksi pembelian tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection


