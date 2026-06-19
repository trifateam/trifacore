@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi'],
        ['label' => 'Riwayat Penjualan'],
    ]" />

    <x-page-header title="Riwayat Penjualan" subtitle="Lihat dan filter semua log transaksi penjualan" />

    {{-- Summary Bar --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 my-6">
        <x-stat-card title="Jumlah Transaksi" :value="$totalTransaksi . ' Nota'" icon="document-text" color="blue" />
        <x-stat-card title="Total Penjualan" :value="\App\Helpers\RupiahFormatter::format($totalNominal)" icon="currency-dollar" color="green" />
        <x-stat-card title="Total Tempo (Belum Lunas)" :value="\App\Helpers\RupiahFormatter::format($totalTempo)" icon="clock" color="yellow" />
    </div>

    {{-- Filter Bar --}}
    <x-card class="mb-6 border border-gray-200 dark:border-gray-700">
        <div class="p-5">
            <form method="GET" action="{{ route('transaksi.riwayat-penjualan') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                    <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                    <input type="date" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Pelanggan</label>
                    <select name="id_pelanggan" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Semua Pelanggan --</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id_pelanggan }}" {{ request('id_pelanggan') == $p->id_pelanggan ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                    <select name="kategori_penjualan" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Semua Kategori --</option>
                        <option value="telur" {{ request('kategori_penjualan') == 'telur' ? 'selected' : '' }}>Telur</option>
                        <option value="afkir" {{ request('kategori_penjualan') == 'afkir' ? 'selected' : '' }}>Ayam Afkir</option>
                        <option value="pupuk" {{ request('kategori_penjualan') == 'pupuk' ? 'selected' : '' }}>Pupuk Kandang</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status_pembayaran" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Status --</option>
                            <option value="Lunas" {{ request('status_pembayaran') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="Tempo" {{ request('status_pembayaran') == 'Tempo' ? 'selected' : '' }}>Tempo (Belum Lunas)</option>
                            <option value="Lunas Sebagian" {{ request('status_pembayaran') == 'Lunas Sebagian' ? 'selected' : '' }}>Lunas Sebagian</option>
                        </select>
                    </div>
                    <div class="pt-5">
                        <x-button type="submit" variant="primary" class="h-9 px-4 flex items-center justify-center">
                            Filter
                        </x-button>
                    </div>
                    <div class="pt-5">
                        <a href="{{ route('transaksi.riwayat-penjualan') }}" class="h-9 px-4 inline-flex items-center justify-center border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:bg-gray-700/50">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </x-card>

    {{-- Tabel Riwayat --}}
    <x-card class="border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <x-table :headers="['No. Nota', 'Waktu Penjualan', 'Pelanggan', 'Kategori', 'Total (Rp)', 'Status', 'Aksi']">
                @forelse($penjualans as $p)
                        @php
                            $status = 'Lunas';
                            $badge = 'success';
                            if ($p->metode_pembayaran === 'PIUTANG' && $p->piutang) {
                                $status = $p->piutang->status_piutang;
                                $badge = $status === 'Lunas' ? 'success' : ($status === 'Belum Lunas' ? 'warning' : 'info');
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 dark:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $p->no_faktur_jual }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($p->tanggal_penjualan)->translatedFormat('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $p->pelanggan->nama_lengkap ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded text-xs">{{ strtoupper($p->kategori_penjualan) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100 text-right">
                                @rupiah($p->total_harga)
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <x-badge :variant="$badge">{{ $status }}</x-badge>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <a href="{{ route('transaksi.riwayat-penjualan.show', $p->id_penjualan) }}" class="text-indigo-600 hover:text-indigo-900 mx-1">Detail</a>
                                
                                @if($status === 'Belum Lunas' || $status === 'Lunas Sebagian')
                                    <span class="text-gray-300 mx-1">|</span>
                                    <a href="{{ route('keuangan.buku-piutang') }}" class="text-blue-600 dark:text-blue-500 hover:text-blue-900 mx-1 font-bold">Lunasi</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10">
                                <x-empty-state 
                                    icon="inbox" 
                                    title="Data Tidak Ditemukan" 
                                    description="Tidak ada riwayat penjualan yang sesuai dengan filter Anda." 
                                />
                            </td>
                        </tr>
                    @endforelse
            </x-table>
        </div>

        @if($penjualans->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $penjualans->links() }}
            </div>
        @endif

    </x-card>
@endsection
