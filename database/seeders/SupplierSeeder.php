<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::query()->delete();
        $admin = Pengguna::where('role', 'Admin')->first();

        $data = [
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_supplier' => 'PT Japfa Comfeed',
                'alamat_supplier' => 'Kawasan Industri, Jakarta',
                'kontak_supplier' => '02188889999',
                'email' => 'sales@japfa.co.id',
                'nama_pic' => 'Bapak Hartono',
            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_supplier' => 'CV Charoen Pokphand',
                'alamat_supplier' => 'Jl. Raya Bogor',
                'kontak_supplier' => '02177776666',
                'email' => 'info@cp.co.id',
                'nama_pic' => 'Ibu Diana',
            ],
            [
                'id_pengguna' => $admin->id_pengguna ?? 1,
                'nama_supplier' => 'Toko Medion Poultry',
                'alamat_supplier' => 'Bandung, Jawa Barat',
                'kontak_supplier' => '02233334444',
                'email' => 'order@medion.co.id',
                'nama_pic' => 'Andi Medion',
            ],
        ];

        foreach ($data as $item) {
            Supplier::create($item);
        }
    }
}
