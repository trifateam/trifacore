@extends('layouts.app')

@php
    $barangsArray = $barangs->map(function($b) {
        return [
            'id' => $b->id_barang,
            'nama' => $b->nama_barang,
            'satuan' => $b->satuan
        ];
    })->values()->toJson();
@endphp

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi', 'url' => route('transaksi.pembelian.index')],
        ['label' => 'Pembelian', 'url' => route('transaksi.pembelian.index')],
        ['label' => 'Material Gudang'],
    ]" />

    <x-page-header title="Pembelian Material Gudang" subtitle="Catat pembelian Pakan, Vitamin, Obat, atau material operasional lainnya" />

    <div class="mt-6" x-data="pembelianMaterialForm()">
        <form method="POST" action="{{ route('transaksi.pembelian.store') }}" @submit="submitForm">
            @csrf
            <input type="hidden" name="jenis" value="material">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kiri: Detail Transaksi & Item --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Header Transaksi --}}
                    <x-card class="border border-gray-200">
                        <div class="p-5 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Informasi Supplier</h3>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                                <input type="text" readonly value="{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}" class="w-full bg-gray-100 border-gray-300 rounded-md shadow-sm sm:text-sm text-gray-500 cursor-not-allowed">
                            </div>
                            
                            <div>
                                <label for="id_supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier <span class="text-red-500">*</span></label>
                                <select name="id_supplier" id="id_supplier" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($suppliers as $s)
                                        <option value="{{ $s->id_supplier }}" {{ old('id_supplier') == $s->id_supplier ? 'selected' : '' }}>
                                            {{ $s->nama_supplier }} ({{ $s->kontak }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_supplier') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </x-card>

                    {{-- Tabel Rincian Barang --}}
                    <x-card class="border border-gray-200">
                        <div class="p-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900">Daftar Barang Dibeli</h3>
                            <x-button type="button" variant="secondary" size="sm" @click="addItem()">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Tambah Baris
                            </x-button>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 border-b border-gray-200 text-xs uppercase text-gray-600">
                                        <th class="p-3 w-5/12">Barang</th>
                                        <th class="p-3 w-2/12">Qty Masuk</th>
                                        <th class="p-3 w-3/12">Harga Satuan (Rp)</th>
                                        <th class="p-3 w-3/12 text-right">Sub-Total (Rp)</th>
                                        <th class="p-3 w-1/12 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="item.key">
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                            <td class="p-3">
                                                <select :name="`items[${index}][id_barang]`" x-model="item.id_barang" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    <option value="">Pilih Barang...</option>
                                                    <template x-for="b in masterBarang" :key="b.id">
                                                        <option :value="b.id" x-text="`${b.nama} (${b.satuan})`"></option>
                                                    </template>
                                                </select>
                                            </td>
                                            <td class="p-3">
                                                <input type="number" step="0.01" min="0.01" :name="`items[${index}][kuantitas]`" x-model.number="item.kuantitas" @input="calculateRow(index)" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                            </td>
                                            <td class="p-3">
                                                <input type="number" step="1" min="1" :name="`items[${index}][harga_satuan]`" x-model.number="item.harga_satuan" @input="calculateRow(index)" required class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                            </td>
                                            <td class="p-3 text-right">
                                                <span class="font-medium text-gray-900" x-text="formatRupiah(item.sub_total)"></span>
                                            </td>
                                            <td class="p-3 text-center">
                                                <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="text-red-500 hover:text-red-700 p-1 rounded-md hover:bg-red-50 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="items.length === 0">
                                        <td colspan="5" class="p-6 text-center text-gray-500 text-sm">Belum ada barang ditambahkan.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 bg-blue-50 border-t border-blue-100">
                            <p class="text-xs text-blue-700 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Stok barang yang dibeli akan otomatis ditambahkan ke inventory Gudang setelah disimpan. Harga modal barang akan otomatis diperbarui.
                            </p>
                        </div>
                    </x-card>
                </div>

                {{-- Kanan: Ringkasan & Pembayaran --}}
                <div class="space-y-6">
                    <x-card class="border border-gray-200">
                        <div class="p-5 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Ringkasan Pembayaran</h3>
                        </div>
                        
                        <div class="p-5">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-6">
                                <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-3">Total Pengeluaran</h4>
                                <div class="text-3xl font-bold text-blue-700" x-text="formatRupiah(grandTotal)">Rp 0</div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" x-model="metodePembayaran" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm font-medium">
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="LUNAS">LUNAS (Bayar Sekarang)</option>
                                        <option value="TEMPO">TEMPO (Hutang)</option>
                                    </select>
                                    @error('metode_pembayaran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div x-show="metodePembayaran === 'LUNAS'" x-transition>
                                    <label for="id_akun_kas" class="block text-sm font-medium text-gray-700 mb-1">Rekening Sumber Dana <span class="text-red-500">*</span></label>
                                    <select name="id_akun_kas" id="id_akun_kas" :required="metodePembayaran === 'LUNAS'" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">-- Pilih Rekening --</option>
                                        @foreach($akunKas as $akun)
                                            <option value="{{ $akun->id_akun }}" {{ old('id_akun_kas') == $akun->id_akun ? 'selected' : '' }}>
                                                {{ $akun->nama_akun }} (Saldo: @rupiah($akun->saldo_sekarang))
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_akun_kas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div x-show="metodePembayaran === 'TEMPO'" class="p-3 bg-red-50 border border-red-200 rounded-md" x-transition>
                                    <p class="text-xs text-red-800">
                                        <span class="font-bold">Info:</span> Transaksi ini akan dicatat sebagai <strong>Hutang</strong> perusahaan. Saldo kas tidak akan berkurang saat ini.
                                    </p>
                                </div>

                                <hr class="border-gray-200">

                                <div>
                                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                                    <textarea name="catatan" id="catatan" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 border-t border-gray-200 bg-gray-50">
                            <button type="submit" 
                                :disabled="isSubmitting || items.length === 0" 
                                :class="(isSubmitting || items.length === 0) ? 'bg-blue-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500'"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200">
                                <span x-show="!isSubmitting">SIMPAN NOTA PEMBELIAN</span>
                                <span x-show="isSubmitting" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memproses...
                                </span>
                            </button>
                        </div>
                    </x-card>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    function pembelianMaterialForm() {
        return {
            masterBarang: {!! $barangsArray !!},
            metodePembayaran: '{{ old("metode_pembayaran", "") }}',
            isSubmitting: false,
            
            items: [
                { key: Date.now(), id_barang: '', kuantitas: 0, harga_satuan: 0, sub_total: 0 }
            ],

            get grandTotal() {
                return this.items.reduce((sum, item) => sum + (parseFloat(item.sub_total) || 0), 0);
            },

            addItem() {
                this.items.push({ 
                    key: Date.now(), 
                    id_barang: '', 
                    kuantitas: 0, 
                    harga_satuan: 0, 
                    sub_total: 0
                });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                }
            },

            calculateRow(index) {
                let item = this.items[index];
                item.sub_total = (parseFloat(item.kuantitas) || 0) * (parseFloat(item.harga_satuan) || 0);
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number || 0);
            },

            submitForm(e) {
                if(this.isSubmitting) {
                    e.preventDefault();
                    return;
                }
                this.isSubmitting = true;
            }
        }
    }
</script>
@endsection
