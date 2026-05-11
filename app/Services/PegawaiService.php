<?php

namespace App\Services;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Collection;

class PegawaiService
{
    public function getAll(): Collection
    {
        return Pegawai::latest()->get();
    }

    public function find(int $id): Pegawai
    {
        return Pegawai::findOrFail($id);
    }

    public function create(array $data): Pegawai
    {
        return Pegawai::create($data);
    }

    public function update(int $id, array $data): Pegawai
    {
        $pegawai = $this->find($id);
        $pegawai->update($data);
        return $pegawai;
    }

    public function delete(int $id): void
    {
        $this->find($id)->delete();
    }
}
