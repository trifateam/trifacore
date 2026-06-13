@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Riwayat Recording'],
    ]" />

    <x-page-header title="Riwayat Pencatatan Harian" subtitle="Log dan riwayat seluruh aktivitas pencatatan operasional harian" />

    {{-- Filter Bar --}}
    <x-card class="mb-6 p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
        <form method="GET" action="{{ route('pencatatan.riwayat.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            
            <div class="w-full md:w-1/4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal', $date) }}"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="w-full md:w-1/4">
                <label for="jenis_pencatatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jenis Pencatatan</label>
                <select name="jenis_pencatatan" id="jenis_pencatatan" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="telur" {{ request('jenis_pencatatan') == 'telur' ? 'selected' : '' }}>Produksi Telur</option>
                    <option value="pakan" {{ request('jenis_pencatatan') == 'pakan' ? 'selected' : '' }}>Konsumsi Pakan</option>
                    <option value="vitamin" {{ request('jenis_pencatatan') == 'vitamin' ? 'selected' : '' }}>Konsumsi Vitamin</option>
                    <option value="deplesi" {{ request('jenis_pencatatan') == 'deplesi' ? 'selected' : '' }}>Deplesi (Kematian/Afkir)</option>
                    <option value="suhu" {{ request('jenis_pencatatan') == 'suhu' ? 'selected' : '' }}>Suhu Kandang</option>
                    <option value="pupuk" {{ request('jenis_pencatatan') == 'pupuk' ? 'selected' : '' }}>Produksi Pupuk</option>
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label for="id_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kandang</label>
                <select name="id_kandang" id="id_kandang" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Kandang</option>
                    @foreach($kandangs as $kdg)
                        <option value="{{ $kdg->id_kandang }}" {{ request('id_kandang') == $kdg->id_kandang ? 'selected' : '' }}>
                            {{ $kdg->nama_kandang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-auto flex gap-2">
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </x-button>
                <a href="{{ route('pencatatan.riwayat.index') }}" class="btn btn-secondary">
                    <x-button variant="secondary" type="button">Reset</x-button>
                </a>
            </div>
        </form>
    </x-card>

    <div>

        <x-card class="border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Pencatatan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kandang & Batch</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data Ringkasan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pencatat</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                        @forelse($paginatedItems as $item)
                            <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['waktu']->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item['waktu']->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge variant="{{ $item['badge_variant'] }}">{{ $item['type_label'] }}</x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item['kandang_nama'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item['batch_nama'] !== '-' ? 'Batch: ' . $item['batch_nama'] : '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item['ringkasan'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $item['pencatat'] }}</div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10">
                                    <x-empty-state message="Tidak ada data pencatatan harian yang ditemukan." icon="document" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $paginatedItems->links() }}
            </div>
        </x-card>


    </div>
@endsection
