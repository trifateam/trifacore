@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Kategori Biaya Operasional'],
    ]" />

    <x-page-header title="Kategori Biaya Operasional" subtitle="Kelola data kategori untuk pengeluaran biaya operasional">
        <x-slot:action>
            <x-button variant="primary" tag="a" href="{{ route('master-data.kategori-biaya.create') }}">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Kategori
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.kategori-biaya.index')">
        <x-search-field name="search" placeholder="Cari nama kategori..." :value="$search" />
    </x-filter-bar>

    @if($kategoris->count() > 0)
        <x-table :headers="['No', 'Nama Kategori', 'Keterangan', 'Aksi']">
            @foreach($kategoris as $index => $kategori)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $kategoris->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $kategori->nama_kategori }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $kategori->keterangan ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm" tag="a" href="{{ route('master-data.kategori-biaya.edit', $kategori->id_kategori_biaya) }}">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            <x-button variant="danger" size="sm"
                                @click="$dispatch('confirm-delete', { action: '{{ route('master-data.kategori-biaya.destroy', $kategori->id_kategori_biaya) }}' })">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $kategoris->links() }}</div>
    @else
        <x-empty-state message="Belum ada data kategori biaya" icon="tag" />
    @endif



    <x-confirm-dialog
        title="Hapus Kategori Biaya"
        message="Apakah Anda yakin ingin menghapus kategori ini? Kategori yang sudah digunakan dalam transaksi pengeluaran operasional tidak dapat dihapus."
        confirmLabel="Ya, Hapus"
        confirmVariant="danger"
    />
@endsection


