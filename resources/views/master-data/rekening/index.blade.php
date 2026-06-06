@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Rekening Kas/Bank'],
    ]" />

    <x-page-header title="Data Rekening Kas/Bank" subtitle="Kelola data rekening bank dan akun kas perusahaan">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-rekening'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Rekening
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.rekening.index')">
        <x-search-field name="search" placeholder="Cari nama akun, no rekening..." :value="$search" />
        <x-select
            name="kategori"
            label="Kategori"
            :options="array_map(fn($k) => ['value' => $k, 'label' => $k], $kategoriList)"
            :selected="$kategori"
            placeholder="Semua Kategori"
        />
        <x-select
            name="status"
            label="Status"
            :options="[
                ['value' => '1', 'label' => 'Aktif'],
                ['value' => '0', 'label' => 'Non-Aktif'],
            ]"
            :selected="$status"
            placeholder="Semua Status"
        />
    </x-filter-bar>

    @if($rekenings->count() > 0)
        <x-table :headers="['No', 'Nama Akun', 'Kategori', 'No. Rekening', 'Nama Pemilik', 'Saldo', 'Status', 'Aksi']">
            @foreach($rekenings as $index => $rekening)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $rekenings->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $rekening->nama_akun }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($rekening->kategori_akun) {
                                'Bank' => 'info',
                                'E-Wallet' => 'warning',
                                'Tunai' => 'success',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $rekening->kategori_akun }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $rekening->no_rekening ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $rekening->nama_pemilik ?? '-' }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">
                        Rp {{ number_format($rekening->saldo, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($rekening->is_active)
                            <x-badge variant="success">Aktif</x-badge>
                        @else
                            <x-badge variant="danger">Non-Aktif</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <x-button variant="secondary" size="sm"
                            @click="editRekening({{ json_encode([
                                'id' => $rekening->id_akun,
                                'nama_akun' => $rekening->nama_akun,
                                'kategori_akun' => $rekening->kategori_akun,
                                'no_rekening' => $rekening->no_rekening,
                                'nama_pemilik' => $rekening->nama_pemilik,
                                'saldo' => $rekening->saldo,
                                'is_active' => $rekening->is_active ? '1' : '0',
                            ]) }})">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                            Edit
                        </x-button>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $rekenings->links() }}</div>
    @else
        <x-empty-state message="Belum ada data rekening kas/bank" icon="document-text" />
    @endif

    {{-- Modal Tambah --}}
    <x-modal id="tambah-rekening" title="Tambah Rekening Baru" size="lg">
        <form id="formTambahRekening" method="POST" action="{{ route('master-data.rekening.store') }}">
            @csrf
            <x-form-section title="Informasi Rekening" description="Lengkapi data akun kas atau bank baru">
                <div class="space-y-4">
                    <x-input name="nama_akun" label="Nama Bank/Kas" placeholder="Contoh: BCA Utama / Kas Kecil" :required="true" hint="Maksimal 50 karakter, harus unik" />
                    
                    <x-select name="kategori_akun" label="Kategori Akun" :required="true"
                        :options="[
                            ['value' => 'Bank', 'label' => 'Bank'],
                            ['value' => 'Tunai', 'label' => 'Tunai'],
                            ['value' => 'E-Wallet', 'label' => 'E-Wallet'],
                        ]"
                        x-data="{}"
                        x-on:change="document.getElementById('no_rekening_group').style.display = $event.target.value === 'Tunai' ? 'none' : 'block'"
                    />

                    <div id="no_rekening_group">
                        <x-input name="no_rekening" label="Nomor Rekening" placeholder="Contoh: 1234567890" hint="Wajib diisi untuk Bank/E-Wallet" />
                    </div>

                    <x-input name="nama_pemilik" label="Nama Pemilik (Atas Nama)" placeholder="Contoh: PT TriFaCore" :required="true" />
                    
                    <x-input name="saldo" label="Saldo Awal" type="number" placeholder="0" value="0" :required="true" prefix="Rp" hint="Saldo saat pertama ditambahkan" />
                    
                    <div class="pt-2">
                        <x-toggle name="is_active" label="Status Aktif" :checked="true" />
                    </div>
                </div>
            </x-form-section>
            
            <x-slot:footer>
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-rekening')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Rekening
                </x-button>
            </x-slot:footer>
        </form>
    </x-modal>

    {{-- Modal Edit --}}
    <x-modal id="edit-rekening" title="Edit Data Rekening" size="lg">
        <form id="formEditRekening" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Rekening" description="Perbarui data akun kas atau bank">
                <div class="space-y-4">
                    <div class="mb-4">
                        <label for="edit_nama_akun" class="block text-sm font-medium text-gray-700 mb-1">Nama Bank/Kas <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_akun" id="edit_nama_akun" required maxlength="50" placeholder="Contoh: BCA Utama" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label for="edit_kategori_akun" class="block text-sm font-medium text-gray-700 mb-1">Kategori Akun <span class="text-red-500">*</span></label>
                        <select name="kategori_akun" id="edit_kategori_akun" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm" onchange="document.getElementById('edit_no_rekening_group').style.display = this.value === 'Tunai' ? 'none' : 'block'">
                            <option value="Bank">Bank</option>
                            <option value="Tunai">Tunai</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                    </div>

                    <div class="mb-4" id="edit_no_rekening_group">
                        <label for="edit_no_rekening" class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                        <input type="text" name="no_rekening" id="edit_no_rekening" maxlength="50" placeholder="Contoh: 1234567890" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                        <p class="mt-1 text-sm text-gray-500">Wajib diisi untuk Bank/E-Wallet</p>
                    </div>

                    <div class="mb-4">
                        <label for="edit_nama_pemilik" class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik (Atas Nama) <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pemilik" id="edit_nama_pemilik" required maxlength="100" placeholder="Contoh: PT TriFaCore" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Saat Ini</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"><span class="text-gray-500 sm:text-sm">Rp</span></div>
                            <input type="text" id="edit_saldo_display" disabled class="w-full rounded-lg border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm sm:text-sm pl-12">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Saldo hanya berubah otomatis melalui transaksi.</p>
                    </div>

                    <div class="pt-2" x-data="{ active: true }" x-ref="editToggle">
                        <input type="hidden" name="is_active" :value="active ? '1' : '0'">
                        <div class="flex items-center">
                            <button type="button" role="switch" :aria-checked="active.toString()" @click="active = !active"
                                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2"
                                :class="active ? 'bg-indigo-600' : 'bg-gray-200'">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                            <label class="ml-3 block text-sm font-medium text-gray-700 cursor-pointer" @click="active = !active">Status Aktif</label>
                        </div>
                    </div>
                </div>
            </x-form-section>
            
            <x-slot:footer>
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-rekening')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Perubahan
                </x-button>
            </x-slot:footer>
        </form>
    </x-modal>
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahRekening');
        if (form) {
            form.reset();
            // Reset visibility for no_rekening
            document.getElementById('no_rekening_group').style.display = 'block';
        }
    }

    function editRekening(data) {
        const form = document.getElementById('formEditRekening');
        form.action = '/master-data/rekening/' + data.id;

        document.getElementById('edit_nama_akun').value = data.nama_akun;
        document.getElementById('edit_kategori_akun').value = data.kategori_akun;
        document.getElementById('edit_no_rekening').value = data.no_rekening || '';
        document.getElementById('edit_nama_pemilik').value = data.nama_pemilik || '';
        
        // Format saldo
        document.getElementById('edit_saldo_display').value = parseFloat(data.saldo).toLocaleString('id-ID');
        
        // Handle no_rekening visibility
        document.getElementById('edit_no_rekening_group').style.display = data.kategori_akun === 'Tunai' ? 'none' : 'block';

        // Set Alpine toggle state
        const toggleEl = document.querySelector('[x-ref="editToggle"]');
        if (toggleEl && toggleEl.__x) {
            toggleEl.__x.$data.active = data.is_active === '1';
        } else {
            // Fallback: dispatch after Alpine init
            setTimeout(() => {
                const el = document.querySelector('[x-ref="editToggle"]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].active = data.is_active === '1';
                }
            }, 100);
        }

        window.dispatchEvent(new CustomEvent('open-modal-edit-rekening'));
    }
</script>
@endsection
