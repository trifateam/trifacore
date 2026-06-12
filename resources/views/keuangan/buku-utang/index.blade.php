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
                                <x-button 
                                    variant="success" 
                                    size="sm" 
                                    icon="banknotes"
                                    @click="
                                        $dispatch('open-modal-pelunasan-utang');
                                        hutangId = {{ $h->id_hutang }};
                                        noNota = '{{ $h->pembelian->no_faktur_beli ?? '-' }}';
                                        supplierName = '{{ addslashes($h->pembelian->supplier->nama_supplier ?? '-') }}';
                                        totalUtang = '{{ number_format($h->jumlah_hutang, 0, ',', '.') }}';
                                        sisaUtang = {{ $h->sisa_hutang }};
                                        sisaUtangFormatted = '{{ number_format($h->sisa_hutang, 0, ',', '.') }}';
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

    {{-- ══════════════════════════════════════════ --}}
    {{-- MODAL PELUNASAN UTANG                     --}}
    {{-- ══════════════════════════════════════════ --}}
    <div x-data="{
        hutangId: null,
        noNota: '',
        supplierName: '',
        totalUtang: '',
        sisaUtang: 0,
        sisaUtangFormatted: '',
        nominal: '',
        saldoWarning: false,
        
        checkSaldo() {
            const selectedOption = document.querySelector('#modal_id_akun option:checked');
            if (selectedOption && selectedOption.dataset.saldo && this.nominal) {
                const saldo = parseFloat(selectedOption.dataset.saldo);
                this.saldoWarning = parseFloat(this.nominal) > saldo;
            } else {
                this.saldoWarning = false;
            }
        },
        
        validateNominal() {
            if (parseFloat(this.nominal) > this.sisaUtang) {
                this.nominal = this.sisaUtang;
            }
            this.checkSaldo();
        }
    }">
        <x-modal id="pelunasan-utang" title="Pelunasan Utang" size="lg">
            {{-- Info Readonly --}}
            <div class="space-y-3 mb-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">No. Nota</label>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100" x-text="noNota"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Supplier</label>
                        <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100" x-text="supplierName"></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Utang</label>
                        <p class="mt-1 text-sm font-bold text-gray-700 dark:text-gray-300">Rp <span x-text="totalUtang"></span></p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sisa Utang</label>
                        <p class="mt-1 text-sm font-bold text-red-600 dark:text-red-500">Rp <span x-text="sisaUtangFormatted"></span></p>
                    </div>
                </div>
            </div>

            <hr class="mb-4 border-gray-200 dark:border-gray-700">

            {{-- Form Pelunasan --}}
            <form :action="'/keuangan/buku-utang/lunasi/' + hutangId" method="POST" class="space-y-4">
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
                            :max="sisaUtang"
                            required
                            placeholder="Masukkan nominal..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm pl-10 font-bold"
                        >
                    </div>
                    <div class="flex justify-between mt-1">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Maks: Rp <span x-text="sisaUtangFormatted"></span></p>
                        <button type="button" @click="nominal = sisaUtang; checkSaldo();" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                            Lunasi Semua
                        </button>
                    </div>
                </div>

                <div>
                    <label for="modal_id_akun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Dari Rekening/Kas <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="id_akun" 
                        id="modal_id_akun" 
                        required
                        @change="checkSaldo()"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >
                        <option value="">-- Pilih Rekening --</option>
                        @foreach($akunKas as $akun)
                            <option value="{{ $akun->id_akun }}" data-saldo="{{ $akun->saldo }}">
                                {{ $akun->nama_akun }} (Saldo: @rupiah($akun->saldo))
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Saldo Warning --}}
                <div x-show="saldoWarning" x-transition class="rounded-lg p-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-amber-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-amber-700 dark:text-amber-400 font-medium">Saldo rekening tidak mencukupi untuk nominal ini.</p>
                    </div>
                </div>

                <x-slot:footer>
                    <x-button variant="secondary" type="button" @click="$dispatch('close-modal-pelunasan-utang')">
                        Batal
                    </x-button>
                    <x-button variant="success" type="submit" icon="check-circle">
                        Proses Pelunasan
                    </x-button>
                </x-slot:footer>
            </form>
        </x-modal>
    </div>
@endsection
