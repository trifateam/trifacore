@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Riwayat Batch'],
    ]" />

    <x-page-header title="Riwayat Batch" subtitle="Daftar batch yang sudah selesai atau tidak aktif." />

    <div class="mt-6 space-y-6">
        <x-card>
            <div class="overflow-x-auto">
                <x-table>
                    <x-slot:thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Batch</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Populasi Awal</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Populasi Akhir</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Telur</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </x-slot:thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($batches as $batch)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $batch->nama_batch ?? $batch->kode_batch }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $batch->kode_batch }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($batch->populasi_awal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ number_format($batch->populasi_saat_ini, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($batch->total_telur, 0, ',', '.') }} butir
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <x-badge variant="secondary">Selesai</x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <x-empty-state 
                                        icon="archive-box" 
                                        title="Belum Ada Riwayat Batch" 
                                        description="Data batch yang telah selesai akan muncul di sini." 
                                    />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </x-table>
            </div>
        </x-card>
    </div>
@endsection
