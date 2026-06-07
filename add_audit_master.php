<?php

$replacements = [
    'BarangController.php' => [
        'store' => [
            'pattern' => "/(Barang::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah barang baru: ' . \$request->nama_barang);"
        ],
        'update' => [
            'pattern' => "/(\\$barang->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit barang: ' . \$request->nama_barang);"
        ],
        'destroy' => [
            'pattern' => "/(\\$barang->delete\(\\);)/",
            'replace' => "\$barangName = \$barang->nama_barang;\n        $1\n        \App\Services\AuditService::log('Menghapus barang: ' . \$barangName);"
        ]
    ],
    'KandangController.php' => [
        'store' => [
            'pattern' => "/(Kandang::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah kandang baru: ' . \$request->nama_kandang);"
        ],
        'update' => [
            'pattern' => "/(\\$kandang->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit kandang: ' . \$request->nama_kandang);"
        ],
        'destroy' => [
            'pattern' => "/(\\$kandang->delete\(\\);)/",
            'replace' => "\$kandangName = \$kandang->nama_kandang;\n        $1\n        \App\Services\AuditService::log('Menghapus kandang: ' . \$kandangName);"
        ]
    ],
    'KategoriBiayaController.php' => [
        'store' => [
            'pattern' => "/(KategoriBiaya::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah kategori biaya baru: ' . \$request->nama_kategori);"
        ],
        'update' => [
            'pattern' => "/(\\$kategoriBiaya->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit kategori biaya: ' . \$request->nama_kategori);"
        ],
        'destroy' => [
            'pattern' => "/(\\$kategoriBiaya->delete\(\\);)/",
            'replace' => "\$kategoriName = \$kategoriBiaya->nama_kategori;\n        $1\n        \App\Services\AuditService::log('Menghapus kategori biaya: ' . \$kategoriName);"
        ]
    ],
    'PegawaiController.php' => [
        'store' => [
            'pattern' => "/(Pengguna::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah pegawai baru: ' . \$request->nama_lengkap);"
        ],
        'update' => [
            'pattern' => "/(\\$pegawai->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit pegawai: ' . \$request->nama_lengkap);"
        ],
        'destroy' => [
            'pattern' => "/(\\$pegawai->delete\(\\);)/",
            'replace' => "\$pegawaiName = \$pegawai->nama_lengkap;\n        $1\n        \App\Services\AuditService::log('Menghapus pegawai: ' . \$pegawaiName);"
        ]
    ],
    'PelangganController.php' => [
        'store' => [
            'pattern' => "/(Pelanggan::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah pelanggan baru: ' . \$request->nama_lengkap);"
        ],
        'update' => [
            'pattern' => "/(\\$pelanggan->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit pelanggan: ' . \$request->nama_lengkap);"
        ],
        'destroy' => [
            'pattern' => "/(\\$pelanggan->delete\(\\);)/",
            'replace' => "\$pelangganName = \$pelanggan->nama_lengkap;\n        $1\n        \App\Services\AuditService::log('Menghapus pelanggan: ' . \$pelangganName);"
        ]
    ],
    'RekeningController.php' => [
        'store' => [
            'pattern' => "/(AkunKas::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah rekening baru: ' . \$request->nama_akun);"
        ],
        'update' => [
            'pattern' => "/(\\$rekening->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit rekening: ' . \$request->nama_akun);"
        ],
        'destroy' => [
            'pattern' => "/(\\$rekening->delete\(\\);)/",
            'replace' => "\$rekeningName = \$rekening->nama_akun;\n        $1\n        \App\Services\AuditService::log('Menghapus rekening: ' . \$rekeningName);"
        ]
    ],
    'SupplierController.php' => [
        'store' => [
            'pattern' => "/(Supplier::create\(\[.*?\]\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Menambah supplier baru: ' . \$request->nama_supplier);"
        ],
        'update' => [
            'pattern' => "/(\\$supplier->update\(\\[.*?\\]\\);)/s",
            'replace' => "$1\n        \App\Services\AuditService::log('Mengedit supplier: ' . \$request->nama_supplier);"
        ],
        'destroy' => [
            'pattern' => "/(\\$supplier->delete\(\\);)/",
            'replace' => "\$supplierName = \$supplier->nama_supplier;\n        $1\n        \App\Services\AuditService::log('Menghapus supplier: ' . \$supplierName);"
        ]
    ]
];

$dir = __DIR__ . '/app/Http/Controllers/MasterData/';

foreach ($replacements as $file => $actions) {
    $path = $dir . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        foreach ($actions as $action => $data) {
            $content = preg_replace($data['pattern'], $data['replace'], $content);
        }
        file_put_contents($path, $content);
        echo "Updated $file\n";
    }
}
