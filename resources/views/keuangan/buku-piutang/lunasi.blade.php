@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Keuangan'],
        ['label' => 'Buku Piutang', 'url' => route('keuangan.buku-piutang')],
        ['label' => 'Pelunasan'],
    ]" />

    <x-page-header title="Pelunasan Piutang" subtitle="Catat pembayaran untuk nota penjualan {{ $piutang->penjualan->no_faktur_jual ?? '-' }}" />

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="mb-6">
            <x-alert type="error">{{ session('error') }}</x-alert>
        </div>
    @endif

    <div class="max-w-3xl">
        <x-card>
            <form action="{{ route('keuangan.buku-piutang.lunasi', $piutang->id_piutang) }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Detail Info (Readonly) --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">No. Nota</label>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ $piutang->penjualan->no_faktur_jual ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Pelanggan</label>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-gray-100">{{ $piutang->penjualan->pelanggan->nama_lengkap ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Piutang</label>
                            <p class="mt-1 text-base font-bold text-gray-700 dark:text-gray-300">@rupiah($piutang->jumlah_piutang)</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Sisa Piutang</label>
                            <p class="mt-1 text-lg font-bold text-emerald-600 dark:text-emerald-500">@rupiah($piutang->sisa_piutang)</p>
                        </div>
                    </div>
                </div>

                {{-- Input Fields --}}
                <div class="space-y-5" x-data="{
                    nominal: '',
                    sisaPiutang: {{ $piutang->sisa_piutang }},
                    
                    validateNominal() {
                        if (parseFloat(this.nominal) > this.sisaPiutang) {
                            this.nominal = this.sisaPiutang;
                        }
                    }
                }">
                    <div>
                        <label for="nominal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nominal Penerimaan <span class="text-red-500">*</span>
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
                                max="{{ $piutang->sisa_piutang }}"
                                required
                                placeholder="Masukkan nominal..."
                                class="block w-full pl-12 pr-4 py-3 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-lg font-bold transition-colors"
                            >
                        </div>
                        <div class="flex justify-between mt-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Maksimal tagihan: @rupiah($piutang->sisa_piutang)</p>
                            <button type="button" @click="nominal = sisaPiutang;" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-bold transition-colors">
                                Lunasi Semua
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="id_akun" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Ke Rekening/Kas <span class="text-red-500">*</span>
                        </label>
                        <select 
                            name="id_akun" 
                            id="id_akun" 
                            required
                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm py-2.5 px-3"
                        >
                            <option value="">-- Pilih Rekening --</option>
                            @foreach($akunKas as $akun)
                                <option value="{{ $akun->id_akun }}" {{ old('id_akun') == $akun->id_akun ? 'selected' : '' }}>
                                    {{ $akun->nama_akun }} (Saldo: Rp {{ number_format($akun->saldo, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Saldo rekening ini akan <strong>bertambah</strong> setelah pelunasan berhasil diproses.</p>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('keuangan.buku-piutang') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-semibold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                        Batal
                    </a>
                    <x-button variant="primary" type="submit" icon="check-circle">
                        Proses Penerimaan
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>
@endsection
