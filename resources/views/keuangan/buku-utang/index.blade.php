@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Keuangan'],
        ['label' => 'Buku Utang'],
    ]" />

    <x-page-header title="Buku Utang" subtitle="Tracking utang pembelian tempo dan pelunasan partial/full." />

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6">
            <x-alert type="success">{{ session('success') }}</x-alert>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6">
            <x-alert type="error">{{ session('error') }}</x-alert>
        </div>
    @endif

    {{-- ══════════════════════════════════════════ --}}
    {{-- SUMMARY CARDS                             --}}
    {{-- ══════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <x-stat-card 
            title="Total Utang Belum Lunas" 
            :value="\App\Helpers\RupiahFormatter::format($totalUtangBelumLunas)"
            icon="banknotes"
            color="red"
        />
        <x-stat-card 
            title="Jumlah Faktur Tempo" 
            :value="$jumlahFakturTempo . ' nota'"
            icon="document-text"
            color="yellow"
        />
    </div>

    {{-- ══════════════════════════════════════════ --}}
    {{-- FILTER BAR                                --}}
    {{-- ══════════════════════════════════════════ --}}
    <x-filter-bar :action="route('keuangan.buku-utang')">
        <x-select 
            name="id_supplier" 
            label="Supplier"
            placeholder="Semua Supplier"
            :options="$suppliers->map(fn($s) => ['value' => $s->id_supplier, 'label' => $s->nama_supplier])->toArray()"
            :selected="request('id_supplier')"
        />

        <x-select 
            name="status" 
            label="Status"
            placeholder="Semua Status"
            :options="[
                ['value' => 'Belum Lunas', 'label' => 'Belum Lunas'],
                ['value' => 'Lunas Sebagian', 'label' => 'Lunas Sebagian'],
                ['value' => 'Lunas', 'label' => 'Lunas'],
            ]"
            :selected="request('status')"
        />
    </x-filter-bar>

    {{-- ══════════════════════════════════════════ --}}
    {{-- TABEL DAFTAR UTANG                        --}}
    {{-- ══════════════════════════════════════════ --}}
    <x-card class="border border-gray-200 dark:border-gray-700">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Daftar Utang</h3>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $hutangs->total() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <x-table :headers="['No. Nota', 'Tanggal', 'Supplier', 'Total Utang (Rp)', 'Sisa Utang (Rp)', 'Jatuh Tempo', 'Status', 'Aksi']">
                @forelse($hutangs as $h)
                    @php
                        $today = now()->startOfDay();
                        $jatuhTempo = $h->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($h->tanggal_jatuh_tempo)->startOfDay() : null;
                        $diffDays = $jatuhTempo ? $today->diffInDays($jatuhTempo, false) : null;
                        
                        // Indikator jatuh tempo (hanya untuk yang belum lunas)
                        $isLunas = $h->status_hutang === 'Lunas';
                        if ($isLunas || $jatuhTempo === null) {
                            $tempoIndicator = 'neutral';
                        } elseif ($diffDays < 0) {
                            $tempoIndicator = 'overdue';
                        } elseif ($diffDays <= 5) {
                            $tempoIndicator = 'warning';
                        } else {
                            $tempoIndicator = 'safe';
                        }

                        $tempoClasses = match($tempoIndicator) {
                            'overdue' => 'text-red-600 dark:text-red-500 font-bold',
                            'warning' => 'text-amber-600 dark:text-amber-500 font-semibold',
                            'safe' => 'text-emerald-600 dark:text-emerald-500',
                            default => 'text-gray-500 dark:text-gray-400',
                        };

                        $tempoBg = match($tempoIndicator) {
                            'overdue' => 'bg-red-50 dark:bg-red-900/30',
                            'warning' => 'bg-amber-50 dark:bg-amber-900/30',
                            default => '',
                        };

                        // Status badge variant
                        $statusVariant = match($h->status_hutang) {
                            'Lunas' => 'success',
                            'Lunas Sebagian' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <tr class="{{ $tempoBg }}">
                        {{-- No. Nota --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700 dark:text-gray-300">
                            {{ $h->pembelian->no_faktur_beli ?? '-' }}
                        </td>

                        {{-- Tanggal Pembelian --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $h->pembelian->tanggal_pembelian ? \Carbon\Carbon::parse($h->pembelian->tanggal_pembelian)->translatedFormat('d M Y') : '-' }}
                        </td>

                        {{-- Supplier --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $h->pembelian->supplier->nama_supplier ?? '-' }}
                        </td>

                        {{-- Total Utang --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                            @rupiah($h->jumlah_hutang)
                        </td>

                        {{-- Sisa Utang --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-right {{ $h->sisa_hutang > 0 ? 'text-red-600 dark:text-red-500' : 'text-emerald-600 dark:text-emerald-500' }}">
                            @rupiah($h->sisa_hutang)
                        </td>

                        {{-- Jatuh Tempo --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm {{ $tempoClasses }}">
                            @if($jatuhTempo)
                                {{ $jatuhTempo->translatedFormat('d M Y') }}
                                @if(!$isLunas)
                                    <div class="text-xs mt-0.5">
                                        @if($tempoIndicator === 'overdue')
                                            🔴 Terlambat {{ abs($diffDays) }} hari
                                        @elseif($tempoIndicator === 'warning')
                                            🟡 {{ $diffDays }} hari lagi
                                        @else
                                            🟢 {{ $diffDays }} hari lagi
                                        @endif
                                    </div>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            <x-badge :variant="$statusVariant" :dot="true">
                                {{ $h->status_hutang }}
                            </x-badge>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            @if($h->status_hutang !== 'Lunas')
                                <a href="{{ route('keuangan.buku-utang.lunasi.form', $h->id_hutang) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lunasi
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Lunas</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10">
                            <x-empty-state 
                                icon="document-text" 
                                message="Belum ada data utang" 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        @if($hutangs->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $hutangs->links() }}
            </div>
        @endif
    </x-card>

@endsection
