@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Riwayat Penyesuaian Stok Gudang'],
    ]" />

    <x-page-header title="Riwayat Penyesuaian Stok Gudang" subtitle="Daftar riwayat stock opname atau penyesuaian manual persediaan barang." />

    <div class="mt-6">
        <x-card class="border border-gray-200 dark:border-gray-700">
            <x-table :headers="['No', 'Tanggal', 'Nama Barang', 'Oleh', 'Stok Lama', 'Stok Baru', 'Alasan']">
                @forelse($logs as $index => $log)
                    <tr>
                        <td class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $logs->firstItem() + $index }}</td>
                        <td class="whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                        <td class="whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">{{ $log->barang->nama_barang ?? '-' }}</td>
                        <td class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $log->pengguna->nama_lengkap ?? '-' }}</td>
                        <td class="whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($log->stok_lama, 2, ',', '.') }}</td>
                        <td class="whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">{{ number_format($log->stok_baru, 2, ',', '.') }}</td>
                        <td class="text-sm text-gray-500 dark:text-gray-400 max-w-xs whitespace-normal break-words">{{ $log->alasan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10">
                            <x-empty-state 
                                icon="inbox" 
                                title="Belum Ada Riwayat" 
                                description="Belum ada aktivitas penyesuaian stok atau stock opname yang tercatat." 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
            
            @if($logs->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </x-card>
    </div>
@endsection
