@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Inventory Gudang'],
    ]" />

    <x-page-header title="Inventory Gudang & Stock Opname" subtitle="Monitor persediaan barang dan lakukan penyesuaian stok jika diperlukan." />

    <div x-data="gudangPage()" class="mt-6 space-y-6">

        {{-- Banner Alert for Critical/Empty Stocks --}}
        @if($countKritis > 0 || $countHabis > 0)
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-md flex items-start shadow-sm">
                <div class="flex-shrink-0 pt-0.5">
                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Peringatan Persediaan Barang!</h3>
                    <div class="mt-1 text-sm text-red-700 dark:text-red-400">
                        <p>Terdapat <strong>{{ $countKritis }} barang</strong> dengan stok kritis dan <strong>{{ $countHabis }} barang</strong> yang kehabisan stok. Segera lakukan pengadaan (restock) untuk menghindari gangguan operasional.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Filter Bar --}}
        <x-card class="border border-gray-200 dark:border-gray-700">
            <div class="p-5">
                <form method="GET" action="{{ route('gudang.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Nama Barang</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cth: Pakan Starter..." class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori Barang</label>
                        <select name="kategori" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Kategori --</option>
                            @php $kategoris = ['Pakan', 'Vitamin', 'Obat', 'Telur', 'Pupuk', 'Ayam', 'Lainnya']; @endphp
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status Stok</label>
                        <select name="status" class="w-full text-sm rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Semua Status --</option>
                            <option value="Normal" {{ request('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Warning" {{ request('status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                            <option value="Kritis" {{ request('status') == 'Kritis' ? 'selected' : '' }}>Kritis</option>
                            <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <div class="flex-1">
                            <x-button type="submit" variant="primary" class="w-full h-9 px-4 flex items-center justify-center">
                                Filter
                            </x-button>
                        </div>
                        <div class="flex-1">
                            <a href="{{ route('gudang.index') }}" class="w-full h-9 px-4 inline-flex items-center justify-center border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:bg-gray-700/50 transition-colors">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </x-card>

        {{-- Tabel Inventory --}}
        <x-card class="border border-gray-200 dark:border-gray-700">
            <x-table :headers="['No', 'Nama Barang', 'Kategori', 'Stok Saat Ini', 'Satuan', 'Stok Minimum', 'Status', 'Aksi']">
                @forelse($paginatedBarang as $index => $barang)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $paginatedBarang->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $barang->kategori_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-gray-100">{{ number_format($barang->stok_barang, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $barang->satuan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ number_format($barang->stok_minimum, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            @if($barang->badge_color == 'dark')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-800 text-white">Habis</span>
                            @else
                                <x-badge :variant="$barang->badge_color">{{ $barang->status_stok }}</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                            <button type="button" @click="openAdjustModal({{ $barang->id_barang }}, '{{ addslashes($barang->nama_barang) }}', {{ $barang->stok_barang }}, '{{ $barang->satuan }}')" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                                Sesuaikan Stok
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10">
                            <x-empty-state 
                                icon="inbox" 
                                title="Barang Tidak Ditemukan" 
                                description="Tidak ada barang di inventory yang sesuai dengan filter pencarian Anda." 
                            />
                        </td>
                    </tr>
                @endforelse
            </x-table>
            
            @if($paginatedBarang->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $paginatedBarang->links() }}
                </div>
            @endif
        </x-card>

        {{-- Modal Stock Opname --}}
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
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <form method="POST" :action="getFormAction()">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-gray-100" id="modal-title">
                                        Stock Opname (Penyesuaian Stok)
                                    </h3>
                                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        Sesuaikan stok fisik barang di gudang dengan pencatatan di sistem.
                                    </div>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nama Barang</label>
                                            <div class="font-bold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700/50 p-2 rounded border border-gray-200 dark:border-gray-700" x-text="selectedItemName"></div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Stok Sistem Saat Ini</label>
                                                <div class="font-bold text-indigo-700 bg-indigo-50 p-2 rounded border border-indigo-100 flex justify-between">
                                                    <span x-text="selectedCurrentStock"></span>
                                                    <span x-text="selectedSatuan" class="text-xs self-center text-indigo-500 uppercase"></span>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="stok_fisik" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Stok Fisik Aktual <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <input type="number" step="0.01" name="stok_fisik" id="stok_fisik" x-model.number="inputStokFisik" min="0" required class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-bold">
                                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-xs uppercase" x-text="selectedSatuan"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <p x-show="isSameStock" class="text-xs font-bold text-amber-600 dark:text-amber-500 flex items-center bg-amber-50 dark:bg-amber-900/30 p-2 rounded">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                            Stok aktual sama dengan sistem. Tidak ada perubahan yang akan disimpan.
                                        </p>

                                        <div>
                                            <label for="alasan" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Alasan Penyesuaian <span class="text-red-500">*</span></label>
                                            <textarea name="alasan" id="alasan" x-model="inputAlasan" rows="3" required maxlength="255" class="w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Contoh: Barang tumpah/rusak, koreksi perhitungan bulan lalu..."></textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" :disabled="isSubmitDisabled" :class="isSubmitDisabled ? 'bg-indigo-300 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                Simpan Penyesuaian
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
    function gudangPage() {
        return {
            isModalOpen: false,
            selectedItemId: null,
            selectedItemName: '',
            selectedCurrentStock: 0,
            selectedSatuan: '',
            inputStokFisik: 0,
            inputAlasan: '',

            openAdjustModal(id, name, stock, satuan) {
                this.selectedItemId = id;
                this.selectedItemName = name;
                this.selectedCurrentStock = parseFloat(stock);
                this.selectedSatuan = satuan;
                this.inputStokFisik = parseFloat(stock);
                this.inputAlasan = '';
                
                this.isModalOpen = true;
                document.body.style.overflow = 'hidden';
            },

            closeModal() {
                this.isModalOpen = false;
                document.body.style.overflow = 'auto';
            },

            get isSameStock() {
                return this.inputStokFisik === this.selectedCurrentStock;
            },

            get isSubmitDisabled() {
                return this.isSameStock || 
                       this.inputStokFisik === '' || 
                       this.inputStokFisik < 0 || 
                       this.inputAlasan.trim() === '';
            },

            getFormAction() {
                if(this.selectedItemId) {
                    return `/gudang/adjust/${this.selectedItemId}`;
                }
                return '#';
            }
        }
    }
</script>
@endsection
