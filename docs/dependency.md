# Dokumentasi Dependensi Pihak Ketiga (Dependency Documentation)

## Daftar Dependensi (Packages) Utama

| Package | Fungsi | Alasan | Versi | Risiko |
| :--- | :--- | :--- | :--- | :--- |
| **barryvdh/laravel-dompdf** | Mengonversi template Blade HTML ke dokumen PDF. | Pustaka server-side terstabil untuk mencetak dokumen/laporan keuangan bebas konflik CSS/kanvas client-side. | `^3.1` | **Rendah** (Ukuran library cukup besar, butuh penyesuaian CSS konvensional tanpa flex/grid CSS3) |
| **maatwebsite/excel** | Mengekspor (dan mengimpor) data laporan menjadi format spreadsheet (XLSX, CSV). | Library standar industri di ekosistem Laravel dengan dukungan kustomisasi format cell yang luas. | `^3.1` | **Sedang** (Memori pemrosesan bisa membesar untuk export data masif > 10.000 baris data) |
| **spatie/laravel-activitylog** | Mencatat segala riwayat aktivitas, manipulasi, dan akses (Audit Trail). | Pustaka terbaik & teraman dari Spatie untuk mencatat (log) aktivitas CRUD pengguna tanpa konfigurasi rumit. | `^5.0` | **Rendah** (Bisa menyebabkan pembengkakan *size* database jika log tidak dibersihkan secara berkala) |
| **blade-ui-kit/blade-heroicons** | Menyediakan ikon UI/UX terpadu melalui Blade Component `<x-heroicon-* />`. | Mengurangi waktu pengembangan dan tidak memerlukan load HTTP (seperti CDN FontAwesome). | `^2.7` | **Sangat Rendah** (Bisa memperlambat kompilasi view Blade di lingkungan lokal tanpa *cache*) |

---

## Integrasi Frontend Tambahan

| Library | Fungsi | Alasan | Versi | Risiko |
| :--- | :--- | :--- | :--- | :--- |
| **Chart.js** | Visualisasi diagram HDP, FCR, dan tren bisnis pada dashboard. | Pustaka charting JavaScript bebas, ringan, dan kompatibel murni dengan Canvas HTML5. | `4.x` | **Rendah** (Responsivitas perenderan ulang kanvas saat *resize* layar butuh *event listener* khusus) |
| **SweetAlert2** | Menampilkan *alert dialog*, popup persetujuan (konfirmasi), & pesan sukses. | Pengalaman antarmuka UI jauh lebih modern dan tidak menghalangi eksekusi UI. | `11.x` | **Sangat Rendah** (Tidak ada risiko berarti) |
| **Alpine.js** | Sistem reaktivitas JS (mis. *dropdown*, *collapse*, *modal*, dll). | Eksekusi langsung di HTML (tanpa JS file terpisah), cocok dengan *utility-first* Tailwind CSS. | `3.x` | **Rendah** (Perlu kehati-hatian pada perenderan variabel turunan Blade) |

---

## 1. barryvdh/laravel-dompdf

**Cara Install**
```bash
composer require barryvdh/laravel-dompdf
```

**Dampak pada proyek**
- **Menambah fitur:** Membuka kemampuan mengekspor faktur penjualan dan laporan laba-rugi secara utuh dalam bentuk PDF, langsung di-download tanpa membebani browser klien.
- **Menambah ukuran dependency:** Pustaka ini membawa mesin `dompdf/dompdf`, sehingga ukuran paket aplikasi (vendor) bertambah sekitar 10-15 MB.
- **Risiko update versi:** Jika HTML/CSS tidak sesuai standar yang dimengerti `dompdf`, fitur cetak akan *broken layout*.

## 2. maatwebsite/excel

**Cara Install**
```bash
composer require maatwebsite/excel
```

**Dampak pada proyek**
- **Menambah fitur:** Laporan data dan hasil panen dapat diunduh (export) menjadi file XLSX sehingga bisa dikelola lanjutan oleh Owner di MS Excel.
- **Risiko sistem:** Membutuhkan *library/extension* spesifik seperti `php_zip` dan `php_xml` pada server.

## 3. spatie/laravel-activitylog

**Cara Install**
```bash
composer require spatie/laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan migrate
```

**Dampak pada proyek**
- **Menambah tabel baru:** Memerlukan tabel khusus (`activity_log`) di database yang berpotensi terus membengkak ukurannya dari waktu ke waktu (terutama karena aplikasi rutin digunakan).
- **Menambah keamanan:** Jejak manipulasi pencatatan (siapa yang mengubah/menghapus) tercatat tak terbantahkan.
