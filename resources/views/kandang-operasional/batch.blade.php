@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Data Batch'],
    ]" />

    <x-page-header title="Data Batch" subtitle="Kelola seluruh data batch (Aktif, Non-Aktif, dan Pending) beserta penempatannya" />

    <div class="mt-6 space-y-6">
        @if(session('success'))
            <x-alert type="success" :message="session('success')" :dismissible="true" />
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" :dismissible="true" />
        @endif

        <x-card>
            <div class="overflow-x-auto">
                <x-table>
                    <x-slot:thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kode / Nama Batch</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis Ayam</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kandang</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tgl Masuk</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pop. Awal</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sisa / Hidup</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </x-slot:thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($batches as $batch)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $batch->kode_batch }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $batch->nama_batch }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $batch->jenis_ayam }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $batch->supplier->nama_supplier ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium {{ $batch->kandang ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 italic' }}">
                                        {{ $batch->kandang->nama_kandang ?? 'Belum ada' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($batch->tgl_masuk)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900 dark:text-gray-100">
                                    {{ number_format($batch->populasi_awal, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($batch->jumlah_sisa, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($batch->status_batch === 'Aktif')
                                        <x-badge variant="success">Aktif</x-badge>
                                    @elseif($batch->status_batch === 'Pending')
                                        <x-badge variant="warning">Pending</x-badge>
                                    @else
                                        <x-badge variant="secondary">Selesai</x-badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($batch->status_batch === 'Pending')
                                        <a href="{{ route('kandang-operasional.assign.form', $batch->id_batch) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                                            Assign ke Kandang
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400 italic">No action</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <x-empty-state 
                                        icon="clipboard-document-list" 
                                        title="Belum Ada Data Batch" 
                                        description="Data batch akan muncul saat pembelian pullet baru diverifikasi." 
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
