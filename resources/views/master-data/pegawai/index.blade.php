@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pegawai'],
    ]" />

    <x-page-header title="Data Pegawai" subtitle="Kelola data pegawai dan pengguna sistem">
        <x-slot:action>
            <x-button variant="primary" tag="a" href="{{ route('master-data.pegawai.create') }}">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.pegawai.index')">
        <x-search-field name="search" placeholder="Cari nama atau username..." :value="$search" />
    </x-filter-bar>

    @if($pegawais->count() > 0)
        <x-table :headers="['No', 'Nama Lengkap', 'Username', 'Role', 'Status', 'Aksi']">
            @foreach($pegawais as $index => $pegawai)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $pegawais->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $pegawai->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-mono">{{ $pegawai->username }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($pegawai->role) {
                                'Admin' => 'danger',
                                'Owner' => 'purple',
                                'Pegawai Kandang' => 'info',
                                'Sales' => 'success',
                                'Pegawai Gudang' => 'warning',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $pegawai->role }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if(!$pegawai->trashed())
                            <x-badge variant="success">Aktif</x-badge>
                        @else
                            <x-badge variant="gray">Non-Aktif</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm" tag="a" href="{{ route('master-data.pegawai.edit', $pegawai->id_pengguna) }}">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            @if($pegawai->id_pengguna !== Auth::id())
                                @if(!$pegawai->trashed())
                                    <x-button variant="danger" size="sm"
                                        @click="$dispatch('confirm-delete', { action: '{{ route('master-data.pegawai.destroy', $pegawai->id_pengguna) }}' })">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        Non-Aktifkan
                                    </x-button>
                                @else
                                    <x-button variant="success" size="sm"
                                        @click="$dispatch('confirm-delete', { action: '{{ route('master-data.pegawai.destroy', $pegawai->id_pengguna) }}' })">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        Aktifkan
                                    </x-button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $pegawais->links() }}</div>
    @else
        <x-empty-state message="Belum ada data pegawai" icon="inbox" />
    @endif



    <x-confirm-dialog
        title="Ubah Status Pegawai"
        message="Apakah Anda yakin ingin mengubah status aktif pegawai ini?"
        confirmLabel="Ya, Ubah Status"
        confirmVariant="danger"
    />
@endsection


