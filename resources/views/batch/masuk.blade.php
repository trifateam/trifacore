@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Batch Masuk'],
    ]" />

    <x-page-header title="Batch Masuk (Pending)" subtitle="Daftar batch pullet baru yang belum ditempatkan ke kandang" />

    <div class="mt-6 space-y-6">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" :dismissible="true" />
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" :dismissible="true" />
        @endif

        @if($batches->count() > 0)
            <x-card>
                <div class="overflow-x-auto">
                    <x-table>
                        <x-slot:thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode Batch</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Ayam</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tgl Masuk</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pop. Awal</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </x-slot:thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($batches as $batch)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $batch->kode_batch }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $batch->jenis_ayam }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $batch->supplier->nama_supplier ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">
                                        {{ \Carbon\Carbon::parse($batch->tgl_masuk)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">
                                        {{ number_format($batch->populasi_awal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <x-badge variant="warning">Pending</x-badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('batch.assign.form', $batch->id_batch) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                                            Assign ke Kandang
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </x-table>
                </div>
            </x-card>
        @else
            <x-card class="flex flex-col items-center justify-center p-12">
                <x-empty-state 
                    icon="clipboard-document-list" 
                    title="Semua Batch Telah Ditempatkan" 
                    description="Tidak ada Batch yang belum ditempatkan ke kandang saat ini." 
                />
            </x-card>
        @endif
    </div>
@endsection
