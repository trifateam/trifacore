@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Penerimaan Barang'],
        ['label' => 'Order Masuk'],
    ]" />

    <x-page-header title="Order Masuk" subtitle="Order penjualan dari Sales yang perlu diproses dan disiapkan barangnya" />

    <div class="mt-6">
        @if($orders->isEmpty())
            <x-card class="p-8 text-center border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col items-center">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">Tidak ada order masuk</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Semua order telah selesai diproses</p>
                </div>
            </x-card>
        @else
            <x-card class="border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No. Faktur</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sales</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat & Lokasi</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                    <td class="px-4 py-3 text-sm font-mono font-semibold text-gray-900 dark:text-gray-100">{{ $order->no_faktur_jual }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->tanggal_penjualan->translatedFormat('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $order->pelanggan->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $order->pengguna->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">{{ strtoupper($order->kategori_penjualan) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                                        <div class="line-clamp-2" title="{{ $order->alamat_pengiriman }}">{{ $order->alamat_pengiriman ?? '-' }}</div>
                                        @if($order->pelanggan && $order->pelanggan->latitude && $order->pelanggan->longitude)
                                            <a href="https://maps.google.com/?q={{ $order->pelanggan->latitude }},{{ $order->pelanggan->longitude }}" target="_blank" class="mt-1 inline-flex items-center text-xs font-medium text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                Buka Map
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold text-gray-900 dark:text-gray-100">@rupiah($order->total_harga)</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($order->status_order === 'Menunggu')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300">⏳ Menunggu</span>
                                        @elseif($order->status_order === 'Diproses')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">🔄 Diproses</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('transaksi.order-masuk.proses', $order->id_penjualan) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" onclick="return confirm('Proses order ini dan siapkan barangnya?')">
                                                    Proses
                                                </button>
                                            </form>
                                            <form action="{{ route('transaksi.order-masuk.batalkan', $order->id_penjualan) }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('Apakah Anda yakin ingin membatalkan order ini?')">
                                                    Batalkan
                                                </button>
                                            </form>
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
