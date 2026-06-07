@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Keuangan'],
        ['label' => 'Buku Kas'],
    ]" />

    <x-page-header title="Buku Kas - Ledger Mutasi" subtitle="Log sentral seluruh mutasi kas dari penjualan, pembelian, operasional, dan pelunasan." />

    {{-- ══════════════════════════════════════════ --}}
    {{-- FILTER BAR                                --}}
    {{-- ══════════════════════════════════════════ --}}
    <x-filter-bar :action="route('keuangan.buku-kas')">
        {{-- Dropdown: Rekening --}}
        <x-select 
            name="id_akun" 
            label="Rekening"
            placeholder="Semua Rekening"
            :options="$akunKasList->map(fn($a) => ['value' => $a->id_akun, 'label' => $a->nama_akun])->toArray()"
            :selected="request('id_akun')"
        />

        {{-- Dropdown: Tipe --}}
        <x-select 
            name="jenis" 
            label="Tipe"
            placeholder="Semua Tipe"
            :options="[
                ['value' => 'Masuk', 'label' => 'Masuk'],
                ['value' => 'Keluar', 'label' => 'Keluar'],
            ]"
            :selected="request('jenis')"
        />

        {{-- Date Range: Dari Tanggal --}}
        <x-input 
            type="date" 
            name="dari_tanggal" 
            label="Dari Tanggal"
            :value="request('dari_tanggal')"
        />

        {{-- Date Range: Sampai Tanggal --}}
        <x-input 
            type="date" 
            name="sampai_tanggal" 
            label="Sampai Tanggal"
            :value="request('sampai_tanggal')"
        />

        {{-- Search keterangan --}}
        <x-search-field 
            name="search" 
            placeholder="Cari deskripsi/keterangan..."
            :value="request('search')"
        />
    </x-filter-bar>

    {{-- ══════════════════════════════════════════ --}}
    {{-- SUMMARY CARDS                             --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        {{-- Total Kas Masuk --}}
        <x-stat-card 
            title="Total Kas Masuk" 
            :value="\App\Helpers\RupiahFormatter::format($totalMasuk)"
            icon="arrow-down-tray"
            color="green"
        />

        {{-- Total Kas Keluar --}}
        <x-stat-card 
            title="Total Kas Keluar" 
            :value="\App\Helpers\RupiahFormatter::format($totalKeluar)"
            icon="arrow-up-tray"
            color="red"
        />

        {{-- Net --}}
        <x-stat-card 
            title="Net (Masuk - Keluar)" 
            :value="\App\Helpers\RupiahFormatter::format(abs($net))"
            icon="scale"
            :color="$net >= 0 ? 'green' : 'red'"
            :trend="$net >= 0 ? 'up' : 'down'"
            :trendValue="$net >= 0 ? 'Surplus' : 'Defisit'"
        />

        {{-- Saldo Awal Periode --}}
        <x-stat-card 
            title="Saldo Awal Periode" 
            :value="\App\Helpers\RupiahFormatter::format(abs($saldoAwal))"
            icon="banknotes"
            color="blue"
        />

        {{-- Saldo Akhir Periode --}}
        <x-stat-card 
            title="Saldo Akhir Periode" 
            :value="\App\Helpers\RupiahFormatter::format(abs($saldoAkhir))"
            icon="wallet"
            color="purple"
        />
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- TABEL LEDGER MUTASI                       --}}
    {{-- ══════════════════════════════════════════ --}}
    <x-card class="border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-200 bg-white flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-bold text-gray-900">Ledger Mutasi Kas</h3>
            </div>
            <span class="text-sm text-gray-500">{{ $bukuKas->total() }} transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <x-table :headers="['Tanggal', 'Kode Jurnal', 'Deskripsi / Keterangan', 'Tipe', 'Nominal (Rp)', 'Rekening', 'Pencatat']">
                @forelse($bukuKas as $kas)
                    <tr>
                        {{-- Tanggal --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($kas->tanggal_transaksi)->translatedFormat('d M Y') }}
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ \Carbon\Carbon::parse($kas->tanggal_transaksi)->format('H:i') }}
                            </div>
                        </td>

                        {{-- Kode Jurnal --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700">
                            {{ $kas->kode_jurnal }}
                        </td>

                        {{-- Deskripsi/Keterangan --}}
                        <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate" title="{{ $kas->keterangan }}">
                            {{ $kas->keterangan ?? '-' }}
                            @if($kas->tipe_referensi)
                                <div class="text-xs text-gray-400 mt-0.5">
                                    Ref: {{ ucfirst(str_replace('_', ' ', $kas->tipe_referensi)) }}
                                </div>
                            @endif
                        </td>

                        {{-- Tipe (Badge Masuk/Keluar) --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <x-badge :variant="$kas->jenis === 'Masuk' ? 'success' : 'danger'" :dot="true">
                                {{ $kas->jenis }}
                            </x-badge>
                        </td>

                        {{-- Nominal --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-right {{ $kas->jenis === 'Masuk' ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $kas->jenis === 'Masuk' ? '+' : '-' }} @rupiah($kas->nominal)
                        </td>

                        {{-- Rekening --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $kas->akunKas->nama_akun ?? '-' }}
                        </td>

                        {{-- Pencatat --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                            {{ $kas->pengguna->nama_pengguna ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10">
                            <x-empty-state 
                                icon="document-text" 
                                message="Belum ada mutasi kas" 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        @if($bukuKas->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $bukuKas->links() }}
            </div>
        @endif
    </x-card>
@endsection
