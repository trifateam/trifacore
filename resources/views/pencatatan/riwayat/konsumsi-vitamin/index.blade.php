@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Riwayat'],
        ['label' => 'Konsumsi Vitamin'],
    ]" />

    <x-page-header title="Riwayat Konsumsi Vitamin" subtitle="Data riwayat lengkap pencatatan konsumsi vitamin harian" />

    <x-card class="mb-6 p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
        <form method="GET" action="{{ route('pencatatan.riwayat.konsumsi-vitamin') }}" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/4">
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ request('tanggal_mulai') }}"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="w-full md:w-1/4">
                <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="w-full md:w-1/4">
                <label for="id_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kandang</label>
                <select name="id_kandang" id="id_kandang" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Kandang</option>
                    @foreach($kandangs as $kdg)
                        <option value="{{ $kdg->id_kandang }}" {{ request('id_kandang') == $kdg->id_kandang ? 'selected' : '' }}>{{ $kdg->nama_kandang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-auto flex gap-2">
                <x-button variant="primary" type="submit">Filter</x-button>
                <a href="{{ route('pencatatan.riwayat.konsumsi-vitamin') }}"><x-button variant="secondary" type="button">Reset</x-button></a>
            </div>
        </form>
    </x-card>

    <x-card class="border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kandang</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Vitamin</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dosis</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Penggunaan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metode</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pencatat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dicatat Pada</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($records as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-900 dark:text-gray-100">{{ $item->kode_vitamin }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->tanggal_konsumsi->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->waktu_pemberian ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->batch->kandang->nama_kandang ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->batch->nama_batch ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">{{ number_format($item->dosis, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right text-gray-900 dark:text-gray-100">{{ number_format($item->total_penggunaan, 2) }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->metode_pemberian ?? '-' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $item->pengguna->nama_lengkap ?? 'Sistem' }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->created_at?->translatedFormat('d M Y H:i:s') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-10">
                                <x-empty-state message="Tidak ada data riwayat konsumsi vitamin." icon="document" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $records->links() }}</div>
    </x-card>
@endsection
