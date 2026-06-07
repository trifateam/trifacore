@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Transaksi', 'url' => route('transaksi.pembelian.index')],
        ['label' => 'Pembelian', 'url' => route('transaksi.pembelian.index')],
        ['label' => 'Pullet Ayam'],
    ]" />

    <x-page-header title="Pembelian Pullet Ayam" subtitle="Catat pembelian bibit ayam (pullet) baru untuk kandang" />

    <div class="mt-6" x-data="pembelianPulletForm()">
        <form method="POST" action="{{ route('transaksi.pembelian.store') }}" @submit="submitForm">
            @csrf
            <input type="hidden" name="jenis" value="pullet">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kiri: Detail Pullet & Supplier --}}
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
                                <select name="id_supplier" id="id_supplier" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
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

                    {{-- Detail Bibit --}}
                    <x-card class="border border-gray-200">
                        <div class="p-5 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-900">Rincian Ayam Pullet</h3>
                        </div>
                        
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="jenis_ayam" class="block text-sm font-medium text-gray-700 mb-1">Jenis / Breed Ayam <span class="text-red-500">*</span></label>
                                <input type="text" name="jenis_ayam" id="jenis_ayam" value="{{ old('jenis_ayam') }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Contoh: Lohmann Brown, Isa Brown, dll">
                                @error('jenis_ayam') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="umur_masuk" class="block text-sm font-medium text-gray-700 mb-1">Umur Masuk (Minggu) <span class="text-red-500">*</span></label>
                                <input type="number" name="umur_masuk" id="umur_masuk" x-model.number="umur" min="0" max="52" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="0">
                                
                                <p x-show="umur > 8" class="mt-1 text-xs text-orange-600 flex items-center" x-transition>
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    Peringatan: Umur ayam melebihi standar pullet normal (> 8 minggu).
                                </p>
                                @error('umur_masuk') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="jumlah_awal" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Ayam (Ekor) <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_awal" id="jumlah_awal" x-model.number="jumlah" min="1" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Contoh: 1000">
                                @error('jumlah_awal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="harga_per_ekor" class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan per Ekor (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="harga_per_ekor" id="harga_per_ekor" x-model.number="harga" min="1" step="1" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Contoh: 50000">
                                @error('harga_per_ekor') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="p-4 bg-orange-50 border-t border-orange-100">
                            <p class="text-xs text-orange-800 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Sistem akan otomatis membuat Batch baru dengan status "Pending" setelah pembelian ini disimpan.
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
                            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200 mb-6">
                                <h4 class="text-xs font-bold text-orange-800 uppercase tracking-wider mb-3">Total Pengeluaran</h4>
                                <div class="text-3xl font-bold text-orange-700" x-text="formatRupiah(grandTotal)">Rp 0</div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                                    <select name="metode_pembayaran" id="metode_pembayaran" x-model="metodePembayaran" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm font-medium">
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="LUNAS">LUNAS (Bayar Sekarang)</option>
                                        <option value="TEMPO">TEMPO (Hutang)</option>
                                    </select>
                                    @error('metode_pembayaran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div x-show="metodePembayaran === 'LUNAS'" x-transition>
                                    <label for="id_akun_kas" class="block text-sm font-medium text-gray-700 mb-1">Rekening Sumber Dana <span class="text-red-500">*</span></label>
                                    <select name="id_akun_kas" id="id_akun_kas" :required="metodePembayaran === 'LUNAS'" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
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
                                    <textarea name="catatan" id="catatan" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">{{ old('catatan') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="p-5 border-t border-gray-200 bg-gray-50">
                            <button type="submit" 
                                :disabled="isSubmitting || !jumlah || !harga" 
                                :class="(isSubmitting || !jumlah || !harga) ? 'bg-orange-300 cursor-not-allowed' : 'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500'"
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
    function pembelianPulletForm() {
        return {
            metodePembayaran: '{{ old("metode_pembayaran", "") }}',
            umur: {{ old('umur_masuk', 0) }},
            jumlah: {{ old('jumlah_awal', 0) }},
            harga: {{ old('harga_per_ekor', 0) }},
            isSubmitting: false,

            get grandTotal() {
                return (parseFloat(this.jumlah) || 0) * (parseFloat(this.harga) || 0);
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
