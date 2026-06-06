@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Pencatatan Harian'],
        ['label' => 'Riwayat Recording'],
    ]" />

    <x-page-header title="Riwayat Pencatatan Harian" subtitle="Log dan riwayat seluruh aktivitas pencatatan operasional harian" />

    {{-- Filter Bar --}}
    <x-card class="mb-6 p-5 border border-gray-200 shadow-sm">
        <form method="GET" action="{{ route('pencatatan.riwayat.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            
            <div class="w-full md:w-1/4">
                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal', $date) }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <div class="w-full md:w-1/4">
                <label for="jenis_pencatatan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Pencatatan</label>
                <select name="jenis_pencatatan" id="jenis_pencatatan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Jenis</option>
                    <option value="telur" {{ request('jenis_pencatatan') == 'telur' ? 'selected' : '' }}>Produksi Telur</option>
                    <option value="pakan" {{ request('jenis_pencatatan') == 'pakan' ? 'selected' : '' }}>Konsumsi Pakan</option>
                    <option value="vitamin" {{ request('jenis_pencatatan') == 'vitamin' ? 'selected' : '' }}>Konsumsi Vitamin</option>
                    <option value="deplesi" {{ request('jenis_pencatatan') == 'deplesi' ? 'selected' : '' }}>Deplesi (Kematian/Afkir)</option>
                    <option value="suhu" {{ request('jenis_pencatatan') == 'suhu' ? 'selected' : '' }}>Suhu Kandang</option>
                    <option value="pupuk" {{ request('jenis_pencatatan') == 'pupuk' ? 'selected' : '' }}>Produksi Pupuk</option>
                </select>
            </div>

            <div class="w-full md:w-1/4">
                <label for="id_kandang" class="block text-sm font-medium text-gray-700 mb-1">Kandang</label>
                <select name="id_kandang" id="id_kandang" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Kandang</option>
                    @foreach($kandangs as $kdg)
                        <option value="{{ $kdg->id_kandang }}" {{ request('id_kandang') == $kdg->id_kandang ? 'selected' : '' }}>
                            {{ $kdg->nama_kandang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-auto flex gap-2">
                <x-button variant="primary" type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </x-button>
                <a href="{{ route('pencatatan.riwayat.index') }}" class="btn btn-secondary">
                    <x-button variant="secondary" type="button">Reset</x-button>
                </a>
            </div>
        </form>
    </x-card>

    <div x-data="{ 
        deleteModalOpen: false, 
        deleteUrl: '', 
        deleteTitle: '',
        openDeleteModal(url, title) {
            this.deleteUrl = url;
            this.deleteTitle = title;
            this.deleteModalOpen = true;
        }
    }">

        <x-card class="border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pencatatan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kandang & Batch</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Ringkasan</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pencatat</th>
                            @role('Admin')
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Aksi</span>
                            </th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($paginatedItems as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['waktu']->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item['waktu']->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge variant="{{ $item['badge_variant'] }}">{{ $item['type_label'] }}</x-badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['kandang_nama'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $item['batch_nama'] !== '-' ? 'Batch: ' . $item['batch_nama'] : '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $item['ringkasan'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item['pencatat'] }}</div>
                                </td>
                                @role('Admin')
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button type="button" 
                                        @click="openDeleteModal('{{ route('pencatatan.riwayat.destroy', ['type' => $item['type'], 'id' => $item['id']]) }}', '{{ $item['type_label'] }} pada {{ $item['waktu']->translatedFormat('d M Y') }}')"
                                        class="text-red-600 hover:text-red-900 p-1 rounded-md hover:bg-red-50 transition-colors duration-150">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@role('Admin') 6 @else 5 @endrole" class="px-6 py-10">
                                    <x-empty-state message="Tidak ada data pencatatan harian yang ditemukan." icon="document" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-4 border-t border-gray-200">
                {{ $paginatedItems->links() }}
            </div>
        </x-card>

        {{-- Modal Delete Hapus (Khusus Admin) --}}
        @role('Admin')
        <div x-show="deleteModalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true" @click="deleteModalOpen = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                    <form method="POST" :action="deleteUrl">
                        @csrf
                        @method('DELETE')
                        
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                    Hapus Data Pencatatan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Anda yakin ingin menghapus data <strong x-text="deleteTitle" class="text-gray-900"></strong>? Tindakan ini tidak dapat dibatalkan.
                                    </p>
                                    
                                    <div class="mt-4">
                                        <label for="alasan" class="block text-sm font-medium text-gray-700">Alasan Penghapusan <span class="text-red-500">*</span></label>
                                        <div class="mt-1">
                                            <textarea id="alasan" name="alasan" rows="3" required class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Tulis alasan singkat mengapa data ini dihapus..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <x-button variant="danger" type="submit" class="w-full inline-flex justify-center sm:ml-3 sm:w-auto">
                                Hapus Data
                            </x-button>
                            <x-button variant="secondary" type="button" @click="deleteModalOpen = false" class="mt-3 w-full inline-flex justify-center sm:mt-0 sm:w-auto">
                                Batal
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endrole
    </div>
@endsection
