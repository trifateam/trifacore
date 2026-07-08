@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Penerimaan Barang'],
        ['label' => 'Order Masuk'],
        ['label' => 'Order Selesai'],
    ]" />

    <x-page-header title="Order Selesai" subtitle="Histori order penjualan yang telah selesai diproses dan diserahkan" />

    <div class="mt-6">
        @if($orders->isEmpty())
            <x-card class="p-8 text-center border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">Belum ada order selesai</p>
                </div>
            </x-card>
        @else
            <x-card class="border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Faktur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tgl Selesai</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Diselesaikan Oleh</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat & Lokasi</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">{{ $order->no_faktur_jual }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->updated_at->translatedFormat('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $order->pelanggan->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->pengguna->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->penggunaGudang->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="line-clamp-2" title="{{ $order->alamat_pengiriman }}">{{ $order->alamat_pengiriman ?? '-' }}</div>
                                        @if($order->pelanggan && $order->pelanggan->latitude && $order->pelanggan->longitude)
                                            <a href="https://maps.google.com/?q={{ $order->pelanggan->latitude }},{{ $order->pelanggan->longitude }}" target="_blank" class="mt-1 inline-flex items-center text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Buka Map
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            {{-- View Nota --}}
                                            <a href="{{ route('transaksi.order-masuk.surat-jalan', $order->id_penjualan) }}" target="_blank" class="p-1.5 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="View Surat Jalan">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            </a>
                                            {{-- Cetak Surat Jalan --}}
                                            <a href="{{ route('transaksi.order-masuk.surat-jalan', $order->id_penjualan) }}" target="_blank" onclick="setTimeout(() => window.open(this.href).print(), 500); return false;" class="p-1.5 rounded-md text-gray-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors" title="Cetak Surat Jalan">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 12h.008v.008h-.008V12zm-3 0h.008v.008h-.008V12z" /></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $orders->links() }}
                    </div>
                @endif
            </x-card>
        @endif
    </div>
@endsection
