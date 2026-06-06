{{-- 
    Partial: Modal Detail Transaksi (Penjualan / Pembelian)
    Requires Alpine context with:
    - isModalOpen: boolean
    - selectedData: object (format from AlpineData injection)
    - closeDetailModal(): function
--}}

<div x-show="isModalOpen" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true"
     style="display: none;">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        {{-- Background overlay --}}
        <div x-show="isModalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
             @click="closeDetailModal()"
             aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        {{-- Modal Panel --}}
        <div x-show="isModalOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
            
            {{-- Modal Header --}}
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                    Detail Nota Transaksi
                </h3>
                <button type="button" @click="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="px-4 pt-5 pb-4 sm:p-6" x-show="selectedData">
                <template x-if="selectedData">
                    <div class="space-y-6">
                        
                        {{-- Header Info Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <div>
                                <span class="block text-gray-500 font-medium">No. Faktur</span>
                                <span class="font-bold text-gray-900" x-text="selectedData.no_faktur"></span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-medium">Tanggal</span>
                                <span class="font-bold text-gray-900" x-text="selectedData.tanggal"></span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-medium">Kategori</span>
                                <span class="font-bold text-gray-900" x-text="selectedData.kategori"></span>
                            </div>
                            <div>
                                <span class="block text-gray-500 font-medium">Status</span>
                                <span :class="`px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-${selectedData.badge}-100 text-${selectedData.badge}-800`" x-text="selectedData.status"></span>
                            </div>
                            
                            {{-- Dynamic Entity Label (Pelanggan/Supplier) --}}
                            <div class="col-span-2">
                                <span class="block text-gray-500 font-medium" x-text="selectedData.pelanggan ? 'Pelanggan' : 'Supplier'"></span>
                                <span class="font-bold text-gray-900" x-text="selectedData.pelanggan || selectedData.supplier"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-gray-500 font-medium">Petugas Kasir</span>
                                <span class="font-bold text-gray-900" x-text="selectedData.kasir"></span>
                            </div>
                        </div>

                        {{-- Tabel Rincian --}}
                        <div>
                            <h4 class="font-bold text-gray-900 mb-3 border-b pb-2">Rincian Barang</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Barang</th>
                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Kuantitas</th>
                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                                            <th scope="col" class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <template x-for="item in selectedData.details" :key="item.nama_barang">
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900" x-text="item.nama_barang"></td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right" x-text="item.kuantitas"></td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-right" x-text="formatRupiah(item.harga_satuan)"></td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 font-medium text-right" x-text="formatRupiah(item.subtotal)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50">
                                            <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-900 uppercase">Grand Total</td>
                                            <td class="px-4 py-3 text-right text-sm font-bold text-gray-900" x-text="formatRupiah(selectedData.total)"></td>
                                        </tr>
                                        <tr x-show="selectedData.metode === 'PIUTANG' || selectedData.metode === 'TEMPO'">
                                            <td colspan="3" class="px-4 py-2 text-right text-sm font-bold text-red-600 uppercase" x-text="selectedData.metode === 'PIUTANG' ? 'Sisa Piutang' : 'Sisa Hutang'"></td>
                                            <td class="px-4 py-2 text-right text-sm font-bold text-red-600" x-text="formatRupiah(selectedData.sisa_piutang || selectedData.sisa_hutang)"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        <div x-show="selectedData.catatan" class="bg-gray-50 p-3 rounded-md text-sm text-gray-600 italic">
                            <span class="font-bold">Catatan:</span> <span x-text="selectedData.catatan"></span>
                        </div>

                    </div>
                </template>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                <button type="button" @click="closeDetailModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
                <template x-if="selectedData && (selectedData.status === 'Belum Lunas' || selectedData.status === 'Lunas Sebagian')">
                    <a :href="selectedData.pelanggan ? '{{ route('keuangan.buku-piutang') }}' : '{{ route('keuangan.buku-utang') }}'" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto sm:text-sm">
                        Proses Pelunasan
                    </a>
                </template>
            </div>
        </div>
    </div>
</div>
