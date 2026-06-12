@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Keuangan'],
        ['label' => 'Buku Piutang'],
    ]" />

    <x-page-header title="Buku Piutang" subtitle="Tracking piutang penjualan tempo dan penerimaan pelunasan dari pelanggan." />

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
            title="Total Piutang Belum Lunas" 
            :value="\App\Helpers\RupiahFormatter::format($totalPiutangBelumLunas)"
            icon="banknotes"
            color="blue"
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
    <x-filter-bar :action="route('keuangan.buku-piutang')">
        <x-select 
            name="id_pelanggan" 
            label="Pelanggan"
            placeholder="Semua Pelanggan"
            :options="$pelanggans->map(fn($p) => ['value' => $p->id_pelanggan, 'label' => $p->nama_lengkap])->toArray()"
            :selected="request('id_pelanggan')"
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
    {{-- TABEL DAFTAR PIUTANG                      --}}
    {{-- ══════════════════════════════════════════ --}}
    <x-card class="border border-gray-200 dark:border-gray-700">
        <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Daftar Piutang</h3>
            </div>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $piutangs->total() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <x-table :headers="['No. Nota', 'Tanggal', 'Pelanggan', 'Total Piutang (Rp)', 'Sisa Piutang (Rp)', 'Jatuh Tempo', 'Status', 'Aksi']">
                @forelse($piutangs as $p)
                    @php
                        $today = now()->startOfDay();
                        $jatuhTempo = $p->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->startOfDay() : null;
                        $diffDays = $jatuhTempo ? $today->diffInDays($jatuhTempo, false) : null;
                        
                        // Indikator jatuh tempo (hanya untuk yang belum lunas)
                        $isLunas = $p->status_piutang === 'Lunas';
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
                        $statusVariant = match($p->status_piutang) {
                            'Lunas' => 'success',
                            'Lunas Sebagian' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <tr class="{{ $tempoBg }}">
                        {{-- No. Nota --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-700 dark:text-gray-300">
                            {{ $p->penjualan->no_faktur_jual ?? '-' }}
                        </td>

                        {{-- Tanggal Penjualan --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $p->penjualan->tanggal_penjualan ? \Carbon\Carbon::parse($p->penjualan->tanggal_penjualan)->translatedFormat('d M Y') : '-' }}
                        </td>

                        {{-- Pelanggan --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $p->penjualan->pelanggan->nama_lengkap ?? '-' }}
                        </td>

                        {{-- Total Piutang --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300 text-right">
                            @rupiah($p->jumlah_piutang)
                        </td>

                        {{-- Sisa Piutang --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-right {{ $p->sisa_piutang > 0 ? 'text-blue-600 dark:text-blue-500' : 'text-emerald-600 dark:text-emerald-500' }}">
                            @rupiah($p->sisa_piutang)
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
                                {{ $p->status_piutang }}
                            </x-badge>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 whitespace-nowrap text-sm">
                            @if($p->status_piutang !== 'Lunas')
                                <x-button 
                                    variant="primary" 
                                    size="sm" 
                                    icon="banknotes"
                                    @click="
                                        $dispatch('open-modal-pelunasan-piutang');
                                        piutangId = {{ $p->id_piutang }};
                                        noNota = '{{ $p->penjualan->no_faktur_jual ?? '-' }}';
                                        pelangganName = '{{ addslashes($p->penjualan->pelanggan->nama_lengkap ?? '-') }}';
                                        totalPiutang = '{{ number_format($p->jumlah_piutang, 0, ',', '.') }}';
                                        sisaPiutang = {{ $p->sisa_piutang }};
                                        sisaPiutangFormatted = '{{ number_format($p->sisa_piutang, 0, ',', '.') }}';
                                    "
                                >
                                    Lunasi
                                </x-button>
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
                                message="Belum ada data piutang" 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
        </div>

        @if($piutangs->hasPages())
            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                {{ $piutangs->links() }}
            </div>
        @endif
    </x-card>

    {{-- ══════════════════════════════════════════ --}}
    {{-- MODAL PELUNASAN PIUTANG                   --}}
    {{-- ══════════════════════════════════════════ --}}
    <div x-data="{
        piutangId: null,
        noNota: '',
        pelangganName: '',
        totalPiutang: '',
        sisaPiutang: 0,
        sisaPiutangFormatted: '',
        nominal: '',
        
        validateNominal() {
            if (parseFloat(this.nominal) > this.sisaPiutang) {
                this.nominal = this.sisaPiutang;
            }
        }
    }">
        <x-modal id="pelunasan-piutang" title="Pelunasan Piutang" size="lg">
            {{-- Info Readonly --}}
            <div class="space-y-3 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">No. Nota</label>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100" x-text="noNota"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pelanggan</label>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100" x-text="pelangganName"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Piutang</label>
                        <p class="mt-1 text-sm font-bold text-gray-700 dark:text-gray-300">Rp <span x-text="totalPiutang"></span></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sisa Piutang</label>
                        <p class="mt-1 text-sm font-bold text-blue-600 dark:text-blue-500">Rp <span x-text="sisaPiutangFormatted"></span></p>
                    </div>
                </div>
            </div>

            <hr class="mb-4 border-gray-200 dark:border-gray-700">

            {{-- Form Pelunasan --}}
            <form :action="'/keuangan/buku-piutang/lunasi/' + piutangId" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="modal_nominal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nominal Pelunasan <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
                        </div>
                        <input 
                            type="number" 
                            name="nominal" 
                            id="modal_nominal"
                            x-model="nominal"
                            @input="validateNominal()"
                            min="1"
                            :max="sisaPiutang"
                            required
                            placeholder="Masukkan nominal..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pl-10 font-bold"
                        >
                    </div>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Maks: Rp <span x-text="sisaPiutangFormatted"></span></p>
                        <button type="button" @click="nominal = sisaPiutang" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            Lunasi Semua
                        </button>
                    </div>
                </div>

                <div>
                    <label for="modal_id_akun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Masuk ke Rekening/Kas <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="id_akun" 
                        id="modal_id_akun" 
                        required
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        <option value="">-- Pilih Rekening --</option>
                        @foreach($akunKas as $akun)
                            <option value="{{ $akun->id_akun }}">
                                {{ $akun->nama_akun }} (Saldo: @rupiah($akun->saldo))
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-500">
                        💰 Saldo rekening akan bertambah setelah pelunasan.
                    </p>
                </div>

                <x-slot:footer>
                    <x-button variant="secondary" type="button" @click="$dispatch('close-modal-pelunasan-piutang')">
                        Batal
                    </x-button>
                    <x-button variant="primary" type="submit" icon="check-circle">
                        Proses Pelunasan
                    </x-button>
                </x-slot:footer>
            </form>
        </x-modal>
    </div>
@endsection
