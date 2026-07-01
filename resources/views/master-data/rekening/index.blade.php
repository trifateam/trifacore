@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Rekening Kas/Bank'],
    ]" />

    <x-page-header title="Data Rekening Kas/Bank" subtitle="Kelola data rekening bank dan akun kas perusahaan">
        <x-slot:action>
            <x-button variant="primary" tag="a" href="{{ route('master-data.rekening.create') }}">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Rekening
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.rekening.index')">
        <x-search-field name="search" placeholder="Cari nama akun, no rekening..." :value="$search" />
        <x-select
            name="kategori"
            label="Kategori"
            :options="array_map(fn($k) => ['value' => $k, 'label' => $k], $kategoriList)"
            :selected="$kategori"
            placeholder="Semua Kategori"
        />
        <x-select
            name="status"
            label="Status"
            :options="[
                ['value' => '1', 'label' => 'Aktif'],
                ['value' => '0', 'label' => 'Non-Aktif'],
            ]"
            :selected="$status"
            placeholder="Semua Status"
        />
    </x-filter-bar>

    @if($rekenings->count() > 0)
        <x-table :headers="['No', 'Nama Akun', 'Kategori', 'No. Rekening', 'Nama Pemilik', 'Saldo', 'Status', 'Aksi']">
            @foreach($rekenings as $index => $rekening)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $rekenings->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $rekening->nama_akun }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($rekening->kategori_akun) {
                                'Bank' => 'info',
                                'E-Wallet' => 'warning',
                                'Tunai' => 'success',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $rekening->kategori_akun }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $rekening->no_rekening ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $rekening->nama_pemilik ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap">
                        @rupiah($rekening->saldo)
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if(!$rekening->trashed())
                            <x-badge variant="success">Aktif</x-badge>
                        @else
                            <x-badge variant="danger">Non-Aktif</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <x-button variant="secondary" size="sm" tag="a" href="{{ route('master-data.rekening.edit', $rekening->id_akun) }}">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                            Edit
                        </x-button>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $rekenings->links() }}</div>
    @else
        <x-empty-state message="Belum ada data rekening kas/bank" icon="document-text" />
    @endif


@endsection
