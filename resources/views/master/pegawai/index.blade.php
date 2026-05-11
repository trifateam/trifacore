<x-layouts.app title="Data Pegawai">
    <div class="page-header">
        <h1>👤 Data Pegawai</h1>
        <p>Manajemen data pegawai</p>
    </div>

    <x-data-table
        title="Data Pegawai"
        :headers="['No', 'Nama', 'Jabatan', 'No HP', 'Tgl Masuk', 'Status', 'Aksi']"
        :createRoute="route('pegawai.create')"
        createLabel="Tambah Pegawai"
    >
        @forelse($pegawais as $i => $pegawai)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ $pegawai->jabatan }}</td>
                <td>{{ $pegawai->no_hp ?? '-' }}</td>
                <td>{{ $pegawai->tanggal_masuk->format('d M Y') }}</td>
                <td>
                    <span class="badge {{ $pegawai->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                        {{ ucfirst($pegawai->status) }}
                    </span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="{{ route('pegawai.edit', $pegawai) }}" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $pegawai->id }}">
                            Hapus
                        </button>
                    </div>
                    <x-modal-delete :id="$pegawai->id" :action="route('pegawai.destroy', $pegawai)" />
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Belum ada data pegawai.</td>
            </tr>
        @endforelse
    </x-data-table>
</x-layouts.app>
