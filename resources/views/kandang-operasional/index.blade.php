@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Kandang Operasional'],
    ]" />

    <x-page-header title="Kandang Operasional" subtitle="Monitor populasi kandang aktif dan kelola penempatan pullet (bibit ayam)" />

    <div x-data="kandangOperasional()" class="mt-6 space-y-10">

        {{-- SECTION 1: PULLET BELUM DITEMPATKAN (PENDING) --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Pullet Belum Ditempatkan
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        {{ $pendingBatches->count() }} Batch Pending
                    </span>
                </h2>
            </div>

            @if($pendingBatches->isEmpty())
                <x-card class="border border-gray-200 dark:border-gray-700">
                    <x-empty-state 
                        icon="inbox" 
                        title="Tidak Ada Pullet Pending" 
                        description="Semua pullet telah ditempatkan di kandang. Anda bisa membeli pullet baru dari menu Transaksi Pembelian." 
                    />
                </x-card>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($pendingBatches as $index => $batch)
                        <x-card class="border border-orange-200 hover:shadow-md transition-shadow bg-orange-50/30">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <div class="text-xs font-bold text-orange-600 mb-1">{{ $batch->kode_batch }}</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $batch->nama_batch }}</h3>
                                    </div>
                                    <x-badge variant="warning">Pending</x-badge>
                                </div>
                                
                                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400 mb-6">
                                    <div class="flex justify-between">
                                        <span>Jenis / Breed:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $batch->jenis_ayam }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Supplier:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $batch->supplier->nama_supplier ?? '-' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tanggal Masuk:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($batch->tgl_masuk)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Umur Masuk:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $batch->umur_awal_minggu }} Minggu</span>
                                    </div>
                                    <div class="flex justify-between border-t border-orange-100 pt-2 mt-2">
                                        <span class="font-bold text-gray-700 dark:text-gray-300">Sisa Belum Assign:</span>
                                        <span class="font-bold text-red-600 dark:text-red-500 text-base">{{ number_format($batch->jumlah_sisa, 0, ',', '.') }} Ekor</span>
                                    </div>
                                </div>

                                <button type="button" @click="openAssignModal({{ $index }})" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                                    Assign ke Kandang
                                </button>
                            </div>
                        </x-card>
                    @endforeach
                </div>
            @endif
        </div>

        <hr class="border-gray-200 dark:border-gray-700">

        {{-- SECTION 2: POPULASI KANDANG AKTIF --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Populasi Kandang Aktif
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($kandangs as $kandang)
                    @php
                        $percentage = $kandang->kapasitas_kandang > 0 ? round(($kandang->populasi_saat_ini / $kandang->kapasitas_kandang) * 100) : 0;
                        $percentage = min(100, max(0, $percentage)); // Clamp 0-100
                        
                        $gaugeColor = 'bg-emerald-500';
                        $bgGaugeColor = 'bg-emerald-100 dark:bg-emerald-900/50';
                        if ($percentage >= 90) {
                            $gaugeColor = 'bg-red-500';
                            $bgGaugeColor = 'bg-red-100 dark:bg-red-900/50';
                        } elseif ($percentage >= 70) {
                            $gaugeColor = 'bg-amber-500';
                            $bgGaugeColor = 'bg-amber-100 dark:bg-amber-900/50';
                        }
                    @endphp

                    <x-card class="border border-gray-200 dark:border-gray-700 flex flex-col h-full overflow-hidden">
                        <div class="p-5 flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $kandang->nama_kandang }}</h3>
                                <div class="text-right">
                                    <span class="block text-2xl font-black text-gray-900 dark:text-gray-100">{{ number_format($kandang->populasi_saat_ini, 0, ',', '.') }}</span>
                                    <span class="block text-xs text-gray-500 dark:text-gray-400">Total Ekor</span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kapasitas Maksimal: {{ number_format($kandang->kapasitas_kandang, 0, ',', '.') }} ekor</p>

                            {{-- Gauge Bar --}}
                            <div class="mb-2 flex justify-between text-xs font-bold">
                                <span class="text-gray-700 dark:text-gray-300">Kepenuhan</span>
                                <span class="{{ $percentage >= 90 ? 'text-red-600 dark:text-red-500' : ($percentage >= 70 ? 'text-amber-600 dark:text-amber-500' : 'text-emerald-600 dark:text-emerald-500') }}">{{ $percentage }}%</span>
                            </div>
                            <div class="w-full {{ $bgGaugeColor }} rounded-full h-2.5 mb-6">
                                <div class="{{ $gaugeColor }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>

                            {{-- Accordion Batches --}}
                            <div x-data="{ expanded: false }" class="border border-gray-100 rounded-lg overflow-hidden">
                                <button @click="expanded = !expanded" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:bg-gray-700 flex justify-between items-center text-sm font-bold text-gray-700 dark:text-gray-300 transition-colors">
                                    <span>Detail Batch ({{ $kandang->batches->count() }})</span>
                                    <svg class="w-4 h-4 transform transition-transform" :class="{'rotate-180': expanded}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                
                                <div x-show="expanded" x-collapse>
                                    <div class="divide-y divide-gray-100">
                                        @forelse($kandang->batches as $kb)
                                            <div class="p-3 bg-white dark:bg-gray-800 text-sm">
                                                <div class="flex justify-between font-bold text-gray-800 dark:text-gray-200 mb-1">
                                                    <span>{{ $kb->nama_batch }}</span>
                                                    <span>{{ number_format($kb->jumlah_sisa, 0, ',', '.') }} ekor</span>
                                                </div>
                                                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                                                    <span>{{ $kb->jenis_ayam }}</span>
                                                    <span>{{ \Carbon\Carbon::parse($kb->tgl_masuk)->translatedFormat('d M Y') }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-3 bg-white dark:bg-gray-800 text-sm text-center text-gray-500 dark:text-gray-400 italic">
                                                Tidak ada batch di kandang ini.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                @empty
                    <div class="lg:col-span-2 xl:col-span-3">
                        <x-card class="border border-gray-200 dark:border-gray-700">
                            <x-empty-state 
                                icon="building-office" 
                                title="Tidak Ada Kandang Aktif" 
                                description="Sistem tidak menemukan kandang yang berstatus aktif." 
                            />
                        </x-card>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL ASSIGNMENT --}}
        <div x-show="isModalOpen" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true"
             style="display: none;">
            
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0" 
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     @click="closeModal()"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div x-show="isModalOpen" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full">
                    
                    <form method="POST" :action="getFormAction()">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                        Assign Pullet ke Kandang
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            Tempatkan ayam muda (pullet) dari <span class="font-bold text-gray-900 dark:text-gray-100" x-text="selectedBatch ? selectedBatch.nama_batch : ''"></span> ke kandang produksi.
                                        </p>
                                        
                                        <div class="bg-orange-50 p-3 rounded border border-orange-100 mb-4 text-sm flex justify-between items-center">
                                            <span class="text-orange-800">Sisa Ayam di Batch Ini:</span>
                                            <span class="font-bold text-orange-900 text-lg" x-text="selectedBatch ? selectedBatch.jumlah_sisa : 0"></span>
                                        </div>

                                        <div class="space-y-4">
                                            <div>
                                                <label for="id_kandang" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pilih Kandang Target <span class="text-red-500">*</span></label>
                                                <select name="id_kandang" id="id_kandang" x-model="selectedKandangId" @change="checkCapacity" required class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                                                    <option value="">-- Pilih Kandang --</option>
                                                    <template x-for="k in kandangsData" :key="k.id_kandang">
                                                        <option :value="k.id_kandang" x-text="`${k.nama_kandang} (Populasi: ${k.populasi_saat_ini} / Kapasitas: ${k.kapasitas_kandang})`"></option>
                                                    </template>
                                                </select>
                                                
                                                <p x-show="selectedKandangId && sisaKapasitasTarget !== null" class="mt-1 text-xs" :class="sisaKapasitasTarget <= 0 ? 'text-red-600 dark:text-red-500 font-bold' : 'text-blue-600 dark:text-blue-500'">
                                                    Sisa Kapasitas Kandang: <span x-text="sisaKapasitasTarget"></span> ekor
                                                </p>
                                            </div>

                                            <div>
                                                <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Jumlah Ayam yang Ditempatkan <span class="text-red-500">*</span></label>
                                                <input type="number" name="jumlah" id="jumlah" x-model.number="assignAmount" min="1" :max="selectedBatch ? selectedBatch.jumlah_sisa : 1" required class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Contoh: 1000">
                                                
                                                <p x-show="isOverCapacity" class="mt-1 text-xs text-red-600 dark:text-red-500 font-bold flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                    Jumlah melebihi sisa kapasitas kandang!
                                                </p>
                                                <p x-show="isOverBatch" class="mt-1 text-xs text-red-600 dark:text-red-500 font-bold flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                    Jumlah melebihi sisa ayam di Batch ini!
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" :disabled="isSubmitDisabled" :class="isSubmitDisabled ? 'bg-orange-300 cursor-not-allowed' : 'bg-orange-600 hover:bg-orange-700'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Assign Sekarang
                            </button>
                            <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:bg-gray-700/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
<script>
    function kandangOperasional() {
        return {
            isModalOpen: false,
            batchesData: @json($pendingBatches),
            kandangsData: @json($kandangs),
            selectedBatch: null,
            selectedKandangId: '',
            assignAmount: 0,
            sisaKapasitasTarget: null,

            openAssignModal(index) {
                this.selectedBatch = this.batchesData[index];
                this.selectedKandangId = '';
                this.assignAmount = this.selectedBatch.jumlah_sisa;
                this.sisaKapasitasTarget = null;
                this.isModalOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.isModalOpen = false;
                setTimeout(() => { 
                    this.selectedBatch = null; 
                    this.selectedKandangId = '';
                }, 300);
                document.body.style.overflow = 'auto';
            },

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
                return this.selectedBatch && this.assignAmount > this.selectedBatch.jumlah_sisa;
            },

            get isSubmitDisabled() {
                return !this.selectedKandangId || 
                       !this.assignAmount || 
                       this.assignAmount <= 0 || 
                       this.isOverCapacity || 
                       this.isOverBatch;
            },

            getFormAction() {
                if(this.selectedBatch) {
                    return `/kandang-operasional/assign/${this.selectedBatch.id_batch}`;
                }
                return '#';
            }
        }
    }
</script>
@endsection
