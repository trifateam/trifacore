@extends('layouts.app')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Master Data'],
        ['label' => 'Data Pegawai'],
    ]" />

    <x-page-header title="Data Pegawai" subtitle="Kelola data pegawai dan pengguna sistem">
        <x-slot:action>
            <x-button variant="primary" @click="$dispatch('open-modal-tambah-pegawai'); resetForm()">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Data
            </x-button>
        </x-slot:action>
    </x-page-header>

    <x-filter-bar :action="route('master-data.pegawai.index')">
        <x-search-field name="search" placeholder="Cari nama atau username..." :value="$search" />
    </x-filter-bar>

    @if($pegawais->count() > 0)
        <x-table :headers="['No', 'Nama Lengkap', 'Username', 'Role', 'Status', 'Aksi']">
            @foreach($pegawais as $index => $pegawai)
                <tr>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $pegawais->firstItem() + $index }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $pegawai->nama_lengkap }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-mono">{{ $pegawai->username }}</td>
                    <td class="px-4 py-3 text-sm">
                        @php
                            $badgeVariant = match($pegawai->role) {
                                'Admin' => 'danger',
                                'Owner' => 'purple',
                                'Pegawai Kandang' => 'info',
                                'Sales' => 'success',
                                'Pegawai Gudang' => 'warning',
                                default => 'gray',
                            };
                        @endphp
                        <x-badge :variant="$badgeVariant">{{ $pegawai->role }}</x-badge>
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if(!$pegawai->trashed())
                            <x-badge variant="success">Aktif</x-badge>
                        @else
                            <x-badge variant="gray">Non-Aktif</x-badge>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <x-button variant="secondary" size="sm"
                                @click="editPegawai({{ json_encode([
                                    'id' => $pegawai->id_pengguna,
                                    'nama_lengkap' => $pegawai->nama_lengkap,
                                    'username' => $pegawai->username,
                                    'role' => $pegawai->role,
                                ]) }})">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                Edit
                            </x-button>
                            @if($pegawai->id_pengguna !== Auth::id())
                                @if(!$pegawai->trashed())
                                    <x-button variant="danger" size="sm"
                                        @click="$dispatch('confirm-delete', { action: '{{ route('master-data.pegawai.destroy', $pegawai->id_pengguna) }}' })">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                        Non-Aktifkan
                                    </x-button>
                                @else
                                    <x-button variant="success" size="sm"
                                        @click="$dispatch('confirm-delete', { action: '{{ route('master-data.pegawai.destroy', $pegawai->id_pengguna) }}' })">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        Aktifkan
                                    </x-button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-table>
        <div class="mt-6">{{ $pegawais->links() }}</div>
    @else
        <x-empty-state message="Belum ada data pegawai" icon="inbox" />
    @endif

    {{-- Modal Tambah --}}
    <x-modal id="tambah-pegawai" title="Tambah Pegawai Baru" size="xl">
        <form id="formTambahPegawai" method="POST" action="{{ route('master-data.pegawai.store') }}">
            @csrf
            <x-form-section title="Informasi Pegawai" description="Lengkapi data pegawai baru">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <x-input name="nama_lengkap" label="Nama Lengkap" placeholder="Contoh: Budi Santoso" :required="true" hint="Maksimal 100 karakter" />
                    <x-input name="username" label="Username" placeholder="Contoh: budisantoso" :required="true" hint="Alfanumerik, maksimal 50 karakter, harus unik" />
                    <x-input name="password" label="Password" type="password" placeholder="Minimal 8 karakter" :required="true" hint="Minimal 8 karakter" />
                    <x-input name="password_confirmation" label="Konfirmasi Password" type="password" placeholder="Ulangi password" :required="true" hint="Harus sama dengan password" />
                    <x-select name="role" label="Role" :required="true"
                        :options="[
                            ['value' => 'Admin', 'label' => 'Admin'],
                            ['value' => 'Owner', 'label' => 'Owner'],
                            ['value' => 'Pegawai Kandang', 'label' => 'Pegawai Kandang'],
                            ['value' => 'Sales', 'label' => 'Sales'],
                            ['value' => 'Pegawai Gudang', 'label' => 'Pegawai Gudang'],
                        ]" />
                </div>
            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-tambah-pegawai')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Pegawai
                </x-button>
            </div>
        </form>
    </x-modal>

    {{-- Modal Edit --}}
    <x-modal id="edit-pegawai" title="Edit Data Pegawai" size="xl">
        <form id="formEditPegawai" method="POST" action="">
            @csrf
            @method('PUT')
            <x-form-section title="Informasi Pegawai" description="Perbarui data pegawai">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="mb-4">
                        <label for="edit_nama_lengkap" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="edit_nama_lengkap" required maxlength="100" placeholder="Contoh: Budi Santoso" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maksimal 100 karakter</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                        <input type="text" id="edit_username" disabled readonly class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm text-sm bg-gray-100 dark:bg-gray-700 cursor-not-allowed text-gray-500 dark:text-gray-400">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Username tidak dapat diubah setelah dibuat</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                        <input type="password" name="password" id="edit_password" minlength="8" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="edit_password_confirmation" minlength="8" placeholder="Ulangi password baru" class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Harus sama dengan password baru</p>
                    </div>
                    <div class="mb-4">
                        <label for="edit_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role <span class="text-red-500">*</span></label>
                        <select name="role" id="edit_role" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="Admin">Admin</option>
                            <option value="Owner">Owner</option>
                            <option value="Pegawai Kandang">Pegawai Kandang</option>
                            <option value="Sales">Sales</option>
                            <option value="Pegawai Gudang">Pegawai Gudang</option>
                        </select>
                    </div>
                </div>
            </x-form-section>
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-button variant="secondary" type="button" @click="$dispatch('close-modal-edit-pegawai')">Batal</x-button>
                <x-button variant="primary" type="submit">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-confirm-dialog
        title="Ubah Status Pegawai"
        message="Apakah Anda yakin ingin mengubah status aktif pegawai ini?"
        confirmLabel="Ya, Ubah Status"
        confirmVariant="danger"
    />
@endsection

@section('scripts')
<script>
    function resetForm() {
        const form = document.getElementById('formTambahPegawai');
        if (form) form.reset();
    }

    function editPegawai(data) {
        const form = document.getElementById('formEditPegawai');
        form.action = '/master-data/pegawai/' + data.id;

        document.getElementById('edit_nama_lengkap').value = data.nama_lengkap;
        document.getElementById('edit_username').value = data.username;
        document.getElementById('edit_role').value = data.role;

        // Clear password fields
        document.getElementById('edit_password').value = '';
        document.getElementById('edit_password_confirmation').value = '';

        window.dispatchEvent(new CustomEvent('open-modal-edit-pegawai'));
    }
</script>
@endsection
