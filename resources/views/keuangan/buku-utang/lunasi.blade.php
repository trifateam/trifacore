@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Keuangan'],
        ['label' => 'Buku Utang', 'url' => route('keuangan.buku-utang')],
        ['label' => 'Pelunasan'],
    ]" />

    <x-page-header title="Pelunasan Utang" subtitle="Catat pembayaran untuk nota {{ $hutang->pembelian->no_faktur_beli ?? '-' }}" />

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-6">
            <x-alert type="error">{{ session('error') }}</x-alert>
        </div>
    @endif

    <div class="max-w-3xl">
        <x-card>
            <form action="{{ route('keuangan.buku-utang.lunasi', $hutang->id_hutang) }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Detail Info (Readonly) --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">No. Nota</label>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ $hutang->pembelian->no_faktur_beli ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Supplier</label>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ $hutang->pembelian->supplier->nama_supplier ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Utang</label>
                            <p class="mt-1 text-base font-bold text-gray-700 dark:text-gray-300">@rupiah($hutang->jumlah_hutang)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sisa Utang</label>
                            <p class="mt-1 text-lg font-bold text-red-600 dark:text-red-500">@rupiah($hutang->sisa_hutang)</p>
                        </div>
                    </div>
                </div>

                {{-- Input Fields --}}
                <div class="space-y-5" x-data="{
                    nominal: '',
                    sisaUtang: {{ $hutang->sisa_hutang }},
                    saldoWarning: false,
                    
                    checkSaldo() {
                        const selectedOption = document.querySelector('#id_akun option:checked');
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
                    <div>
                        <label for="nominal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nominal Pelunasan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <span class="text-gray-500 dark:text-gray-400 font-medium">Rp</span>
                            </div>
                            <input 
                                type="number" 
                                name="nominal" 
                                id="nominal"
                                x-model="nominal"
                                @input="validateNominal()"
                                min="1"
                                max="{{ $hutang->sisa_hutang }}"
                                required
                                placeholder="Masukkan nominal..."
                                class="block w-full pl-12 pr-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-lg font-bold transition-colors"
                            >
                        </div>
                        <div class="flex justify-between mt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Maksimal: @rupiah($hutang->sisa_hutang)</p>
                            <button type="button" @click="nominal = sisaUtang; checkSaldo();" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-bold transition-colors">
                                Lunasi Semua
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="id_akun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Dari Rekening/Kas <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="id_akun" 
                            id="id_akun" 
                            required
                            @change="checkSaldo()"
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm py-2.5 px-3"
                        >
                            <option value="">-- Pilih Rekening --</option>
                            @foreach($akunKas as $akun)
                                <option value="{{ $akun->id_akun }}" data-saldo="{{ $akun->saldo }}" {{ old('id_akun') == $akun->id_akun ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }} (Saldo: Rp {{ number_format($akun->saldo, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Saldo Warning --}}
                    <div x-show="saldoWarning" x-transition x-cloak class="rounded-lg p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-amber-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm text-amber-800 dark:text-amber-300 font-medium">Peringatan: Saldo rekening yang dipilih lebih kecil dari nominal pelunasan. Transaksi mungkin akan ditolak jika saldo tidak mencukupi.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('keuangan.buku-utang') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        Batal
                    </a>
                    <x-button variant="primary" type="submit" icon="check-circle">
                        Proses Pelunasan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
