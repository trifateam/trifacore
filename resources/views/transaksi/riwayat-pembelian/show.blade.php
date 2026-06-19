@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi'],
        ['label' => 'Riwayat Pembelian', 'url' => route('transaksi.riwayat-pembelian')],
        ['label' => 'Detail Nota']
    ]" />

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <x-page-header title="Detail Transaksi Pembelian" subtitle="Faktur: {{ $pembelian->no_faktur_beli }}" />
        
        <div class="flex gap-2">
            <x-button variant="secondary" icon="arrow-left" href="{{ route('transaksi.riwayat-pembelian') }}">
                Kembali
            </x-button>
        </div>
    </div>

    @php
        $status = 'Lunas';
        $badge = 'success';
        if ($pembelian->metode_pembayaran === 'TEMPO' && $pembelian->hutang) {
            $status = $pembelian->hutang->status_hutang;
            $badge = $status === 'Lunas' ? 'success' : ($status === 'Belum Lunas' ? 'warning' : 'info');
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            {{-- Rincian Barang --}}
            <x-card class="border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Rincian Barang</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Barang</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kuantitas</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Satuan</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pembelian->detailPembelian as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $item->barang ? $item->barang->nama_barang : 'Barang Terhapus' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">{{ $item->kuantitas }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">@rupiah($item->harga_satuan)</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-right">@rupiah($item->subtotal)</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-gray-700/50">
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100 uppercase">Grand Total</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-indigo-600 dark:text-indigo-400 text-lg">@rupiah($pembelian->total_pembelian)</td>
                            </tr>
                            @if($pembelian->metode_pembayaran === 'TEMPO' && $pembelian->hutang)
                                <tr>
                                    <td colspan="3" class="px-6 py-3 text-right text-sm font-bold text-red-600 dark:text-red-500 uppercase">Sisa Hutang</td>
                                    <td class="px-6 py-3 text-right text-sm font-bold text-red-600 dark:text-red-500 text-lg">@rupiah($pembelian->hutang->sisa_hutang)</td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </x-card>

            @if($pembelian->catatan)
            <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <h4 class="text-sm font-bold text-yellow-800 dark:text-yellow-300 mb-1">Catatan Transaksi</h4>
                <p class="text-sm text-yellow-700 dark:text-yellow-400">{{ $pembelian->catatan }}</p>
            </div>
            @endif
        </div>

        {{-- Info Panel --}}
        <div class="space-y-6">
            <x-card class="border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Informasi Transaksi</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Transaksi</p>
                        <p class="mt-1 text-sm font-bold text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->translatedFormat('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kategori</p>
                        <p class="mt-1"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">{{ strtoupper($pembelian->kategori_pembelian) }}</span></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</p>
                        <p class="mt-1 text-sm font-bold text-gray-900 dark:text-gray-100">{{ $pembelian->supplier->nama_supplier ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kasir Pencatat</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $pembelian->pengguna->nama_lengkap ?? '-' }}</p>
                    </div>
                    <hr class="border-gray-200 dark:border-gray-700">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status Pembayaran</p>
                        <div class="mt-2">
                            <x-badge :variant="$badge">{{ $status }}</x-badge>
                        </div>
                    </div>
                    
                    @if($status === 'Belum Lunas' || $status === 'Lunas Sebagian')
                    <div class="pt-4">
                        <a href="{{ route('keuangan.buku-utang') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Proses Pelunasan
                        </a>
                    </div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>
@endsection
