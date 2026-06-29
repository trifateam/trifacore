@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Batch', 'url' => route('batch.index')],
        ['label' => 'Assign Pullet']
    ]" />

    <x-page-header title="Assign Pullet ke Kandang" subtitle="Tempatkan ayam muda (pullet) dari {{ $batch->nama_batch }} ke kandang produksi." />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6" x-data="assignKandang()">
        <div class="md:col-span-2">
            <x-card class="border border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('batch.assign', $batch->id_batch) }}">
                    @csrf
                    <div class="p-5 space-y-6">
                        
                        <div class="bg-orange-50 dark:bg-orange-900/30 p-4 rounded-lg border border-orange-100 dark:border-orange-800 text-sm flex justify-between items-center">
                            <span class="text-orange-800 dark:text-orange-300 font-medium">Sisa Ayam di Batch Ini:</span>
                            <span class="font-bold text-orange-900 dark:text-orange-100 text-xl">{{ number_format($batch->jumlah_sisa, 0, ',', '.') }} Ekor</span>
                        </div>

                        <div>
                            <label for="id_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Kandang Target <span class="text-red-500">*</span></label>
                            <select name="id_kandang" id="id_kandang" x-model="selectedKandangId" @change="checkCapacity" required class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                <option value="">-- Pilih Kandang --</option>
                                @foreach($kandangs as $k)
                                    <option value="{{ $k->id_kandang }}">{{ $k->nama_kandang }} (Populasi: {{ $k->populasi_saat_ini }} / Kapasitas: {{ $k->kapasitas_kandang }})</option>
                                @endforeach
                            </select>
                            
                            <p x-show="selectedKandangId && sisaKapasitasTarget !== null" class="mt-2 text-sm" :class="sisaKapasitasTarget <= 0 ? 'text-red-600 dark:text-red-500 font-bold' : 'text-blue-600 dark:text-blue-500'">
                                Sisa Kapasitas Kandang: <span x-text="sisaKapasitasTarget"></span> ekor
                            </p>
                        </div>

                        <div>
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Ayam yang Ditempatkan <span class="text-red-500">*</span></label>
                            <input type="number" name="jumlah" id="jumlah" x-model.number="assignAmount" min="1" max="{{ $batch->jumlah_sisa }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" placeholder="Contoh: 1000">
                            
                            <p x-show="isOverCapacity" class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                Jumlah melebihi sisa kapasitas kandang!
                            </p>
                            <p x-show="isOverBatch" class="mt-2 text-sm text-red-600 dark:text-red-500 font-bold flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                Jumlah melebihi sisa ayam di Batch ini!
                            </p>
                        </div>

                    </div>
                    <div class="px-5 py-4 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <x-button type="button" variant="secondary" href="{{ route('batch.index') }}">
                            Batal
                        </x-button>
                        <button type="submit" :disabled="isSubmitDisabled" :class="isSubmitDisabled ? 'bg-orange-300 dark:bg-orange-800 cursor-not-allowed' : 'bg-orange-600 hover:bg-orange-700'" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                            Assign Sekarang
                        </button>
                    </div>
                </form>
            </x-card>
        </div>

        <div>
            <x-card class="border border-gray-200 dark:border-gray-700">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Informasi Batch</h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Kode Batch</p>
                        <p class="mt-1 text-sm font-bold text-gray-900 dark:text-gray-100">{{ $batch->kode_batch }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jenis/Breed</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $batch->jenis_ayam }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Supplier</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $batch->supplier->nama_supplier ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Masuk</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($batch->tgl_masuk)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Umur Masuk</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $batch->umur_awal_minggu }} Minggu</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function assignKandang() {
        return {
            kandangsData: @json($kandangs),
            jumlahSisaBatch: {{ $batch->jumlah_sisa }},
            selectedKandangId: '',
            assignAmount: {{ $batch->jumlah_sisa }},
            sisaKapasitasTarget: null,

            checkCapacity() {
                if(!this.selectedKandangId) {
                    this.sisaKapasitasTarget = null;
                    return;
                }
                const k = this.kandangsData.find(x => x.id_kandang == this.selectedKandangId);
                if(k) {
                    this.sisaKapasitasTarget = k.kapasitas_kandang - k.populasi_saat_ini;
                }
            },

            get isOverCapacity() {
                return this.selectedKandangId && this.sisaKapasitasTarget !== null && this.assignAmount > this.sisaKapasitasTarget;
            },

            get isOverBatch() {
                return this.assignAmount > this.jumlahSisaBatch;
            },

            get isSubmitDisabled() {
                return !this.selectedKandangId || 
                       !this.assignAmount || 
                       this.assignAmount <= 0 || 
                       this.isOverCapacity || 
                       this.isOverBatch;
            }
        }
    }
</script>
@endsection
