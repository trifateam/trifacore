@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        @if($stokKritisCount > 0)
            <x-alert type="error" title="Peringatan Stok Minimum" :dismissible="true">
                <ul class="list-disc pl-5 mt-1">
                    @foreach($barangKritis as $barang)
                        <li>Stok <strong>{{ $barang->nama_barang }}</strong> tersisa {{ number_format($barang->stok_barang, 0) }}
                            {{ $barang->satuan }} (Minimum: {{ number_format($barang->stok_minimum, 0) }} {{ $barang->satuan }}).</li>
                    @endforeach
                </ul>
            </x-alert>
        @endif

        {{-- Page Header --}}
        <x-page-header title="Dashboard Pegawai Gudang"
            subtitle="Ringkasan operasional gudang dan penerimaan barang bulan ini — {{ now()->translatedFormat('l, d F Y') }}">
            <x-slot:action>
                <x-button variant="secondary" onclick="window.location.reload()">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </x-button>
            </x-slot:action>
        </x-page-header>

        {{-- ═══════════════════════════════════════════════════════════
        1. SUMMARY CARDS
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Total Transaksi --}}
            <x-stat-card title="Penerimaan Barang (Bulan Ini)" :value="$totalTransaksi . ' Transaksi'" icon="document-text"
                color="blue" />

            {{-- Total Pembelian --}}
            <x-stat-card title="Total Pembelian (Bulan Ini)" :value="\App\Helpers\RupiahFormatter::format($totalNominal)"
                icon="currency-dollar" color="orange" />

            {{-- Total Tempo --}}
            <x-stat-card title="Total Tempo (Bulan Ini)" :value="\App\Helpers\RupiahFormatter::format($totalTempo)"
                icon="clock" color="red" />

            {{-- Stok Kritis --}}
            <x-stat-card title="Stok Kritis Gudang" :value="$stokKritisCount . ' Barang'" icon="exclamation-triangle"
                color="red" />
        </div>

        {{-- ═══════════════════════════════════════════════════════════
        2. ALERT STOK KRITIS
        ═══════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 gap-5">
            <x-card title="Alert Stok Kritis Gudang" subtitle="Barang dengan stok di bawah batas minimum">
                @if($barangKritis->isEmpty())
                    <x-empty-state message="Semua stok dalam kondisi aman" icon="check-circle" />
                @else
                    <div class="overflow-x-auto">
                        <x-table :headers="['Nama Barang', 'Stok Saat Ini', 'Stok Minimum', 'Status']">
                            @foreach($barangKritis as $barang)
                                <tr>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $barang->nama_barang }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($barang->stok_barang, 0) }} {{ $barang->satuan }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        {{ number_format($barang->stok_minimum, 0) }} {{ $barang->satuan }}</td>
                                    <td class="px-4 py-3">
                                        <x-badge variant="danger" dot>Kritis</x-badge>
                                    </td>
                                </tr>
                            @endforeach
                        </x-table>
                    </div>
                @endif
            </x-card>
        </div>

    </div>
@endsection
