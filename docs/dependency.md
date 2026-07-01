# Dependency Documentation

Dokumen ini menjelaskan dependency proyek TriFaCore berdasarkan kebutuhan sistem dan kondisi repository saat ini.

## Identitas

* **Nama proyek:** TriFaCore - Sistem Informasi Manajemen Peternakan Ayam Petelur "Trifa Farm"
* **Framework utama:** Laravel 13
* **Tujuan:** Mengidentifikasi dependency/package Laravel yang digunakan maupun yang direncanakan untuk mendukung pengembangan sistem, menjelaskan kegunaannya menggunakan pendekatan 5W+1H, serta menganalisis dampaknya terhadap evolusi perangkat lunak.

## Ringkasan

Dependency dikelola menggunakan Composer untuk package PHP/Laravel dan NPM untuk package frontend. Dependency yang digunakan maupun yang direncanakan dicatat dan dianalisis berdasarkan kebutuhan fitur pada proyek TriFaCore.

Dependency pada dokumen ini dikelompokkan menjadi:

* Dependency Backend
* Dependency Development dan Testing
* Dependency Frontend
* Dependency Rencana Pengembangan

---

# Dependency Backend

| Package | Fungsi | Alasan | Versi | Risiko |
| :--- | :--- | :--- | :--- | :--- |
| `laravel/framework` | Framework utama aplikasi | Menyediakan routing, MVC, ORM (Eloquent), migration, middleware, dan fitur inti Laravel | `^13.7` | Perubahan versi major dapat memerlukan penyesuaian kode |
| `barryvdh/laravel-dompdf` | Pembuatan laporan PDF | Digunakan untuk mencetak rekap laporan keuangan dan produksi telur secara otomatis | `^3.1` | Membutuhkan memori yang cukup besar jika merender data berskala sangat besar |
| `blade-ui-kit/blade-heroicons` | Integrasi Ikon UI | Menyediakan set ikon SVG Heroicons yang ringan dan mudah digunakan langsung pada template Blade | `^2.7` | Ketergantungan pada pustaka ikon pihak ketiga, pembaruan versi dapat mengubah nama ikon |
| `maatwebsite/excel` | Ekspor/Impor data Excel/CSV | Mendukung ekspor data transaksi penjualan telur dan laporan keuangan Mitra/Owner ke format spreadsheet | `^3.1` | Membutuhkan memori tinggi pada ekspor data besar jika tidak menggunakan chunking |
| `spatie/laravel-activitylog` | Log Aktivitas Pengguna | Merekam dan melacak riwayat log aktivitas pengguna (audit trail) untuk keperluan keamanan dan transparansi | `^5.0` | Ukuran database membengkak jika data log tidak dibersihkan atau diarsipkan secara rutin |

# Dependency Development dan Testing

| Package | Fungsi | Alasan | Versi | Risiko |
| :--- | :--- | :--- | :--- | :--- |
| `phpunit/phpunit` | Framework Testing | Pustaka pengujian standar untuk memvalidasi integritas kode backend (Unit/Feature Testing) | `^12.5.12` | Penulisan test case memerlukan waktu tambahan selama proses development |
| `fakerphp/faker` | Pembuat Data Dummy | Membantu mengisi data simulasi transaksi, inventaris pakan, dan populasi ayam untuk testing | `^1.23` | Data dummy tidak boleh masuk ke database production secara tidak sengaja |
| `laravel/pail` | Log Streaming | Membantu developer melakukan log monitoring secara real-time langsung melalui command line | `^1.2.5` | Tidak dibutuhkan pada lingkungan production |
| `laravel/pint` | PHP Code Style Fixer | Menjaga konsistensi gaya penulisan kode PHP (formatting) sesuai standar tim pengembang | `^1.27` | Perubahan otomatis format kode dapat menyebabkan konflik minor saat merge pull request |
| `laravel/tinker` | REPL Laravel | Interaksi langsung dengan database dan model Laravel melalui console untuk kebutuhan debug | `^3.0` | Kesalahan eksekusi perintah di Tinker pada server produksi berisiko merusak data |

# Dependency Frontend

| Package | Fungsi | Alasan | Versi | Risiko |
| :--- | :--- | :--- | :--- | :--- |
| `vite` | Frontend Build Tool | Mengompilasi dan mengemas aset-aset CSS dan JavaScript secara cepat | `^8.0.0` | Membutuhkan versi Node.js yang kompatibel di server |
| `@tailwindcss/vite` & `tailwindcss` | Utility-first CSS Framework | Menyusun gaya antarmuka (UI) aplikasi yang modern, responsif, dan konsisten dengan Tailwind CSS v4 | `^4.0.0` | Perubahan sintaks utama di Tailwind v4 memerlukan adaptasi penulisan class |
| `laravel-vite-plugin` | Integrasi Laravel dengan Vite | Menghubungkan template Blade dengan hasil kompilasi aset frontend dari Vite | `^3.1` | Aset frontend gagal dimuat jika konfigurasi build atau file manifest bermasalah |
| `alpinejs` & `@alpinejs/collapse` | Interaktivitas Frontend Ringan | Menangani aksi interaktif sederhana seperti sidebar toggle, dropdown menu, modal, dan accordion | `^3.15.12` | Kurang cocok untuk pengelolaan state aplikasi yang sangat kompleks |
| `chart.js` | Visualisasi Grafik | Merender grafik tren produksi telur harian (HDP) dan fluktuasi keuangan di dashboard | `^4.5.1` | Konfigurasi grafik yang kompleks memerlukan waktu pemahaman dokumentasi tersendiri |
| `sweetalert2` | Notifikasi Pop-up Kustom | Menampilkan dialog konfirmasi interaktif (misalnya konfirmasi hapus data) yang lebih estetik | `^11.26.25` | Ketergantungan eksternal untuk interaksi antarmuka dasar |
| `@fontsource/inter` | Font Keluarga Inter | Menyediakan tipografi Sans-Serif Inter secara lokal untuk kenyamanan visual antarmuka | `^5.2.8` | Menambah ukuran unduhan aset statis font |

# Dependency Rencana Pengembangan

| Package | Fungsi | Modul Rencana | Alasan |
| :--- | :--- | :--- | :--- |
| `maatwebsite/excel` | Import dan Export Excel/CSV | Laporan keuangan dan produksi | Memudahkan pengolahan laporan bagi Mitra/Owner |
| `barryvdh/laravel-dompdf` | Generate PDF dari Blade | Cetak struk transaksi/invoice dan laporan | Dokumen mudah dicetak dan diarsipkan |
| `consoletvs/charts` | Visualisasi Grafik | Dashboard laporan admin | Membantu visualisasi tren produksi dan keuangan |
| `spatie/laravel-permission` (Opsional) | Role & Permission Management | Sistem Autentikasi / RBAC | Alternatif jika diperlukan pengaturan izin akses (permission) yang lebih granular di masa depan |

---

# Analisis 5W+1H Dependency Utama

## 1. Spatie Activitylog (Log Aktivitas Pengguna)

| 5W+1H | Penjelasan |
| ----- | ---------- |
| What | Package Laravel untuk merekam riwayat aktivitas pengguna dan perubahan data pada model secara otomatis ke database. |
| Why | Penting untuk audit trail, transparansi, dan keamanan operasional peternakan Trifa Farm (misal: melacak siapa yang mengubah data inventaris pakan). |
| Who | Admin Sistem dan Owner (sebagai pihak yang berwenang meninjau log). |
| When | Terpicu otomatis saat terjadi transaksi data (Create, Update, Delete) pada model yang dipantau. |
| Where | Modul log audit pada panel admin. |
| How | Dengan menambahkan trait `LogsActivity` pada model Laravel yang ingin diawasi. |

**Referensi:**
* https://spatie.be/docs/laravel-activitylog
* https://github.com/spatie/laravel-activitylog

## 2. Barryvdh Laravel DOMPDF (Pembuatan Dokumen PDF)

| 5W+1H | Penjelasan |
| ----- | ---------- |
| What | Package Laravel yang mengonversi kode HTML/Blade menjadi dokumen PDF yang siap cetak. |
| Why | Untuk mencetak struk transaksi penjualan telur serta rekapitulasi laporan laba-rugi keuangan secara fisik/dokumen digital. |
| Who | Owner (untuk arsip keuangan) dan Karyawan (untuk nota transaksi pelanggan). |
| When | Saat pengguna menekan tombol "Cetak PDF" pada menu laporan atau penjualan. |
| Where | Modul laporan keuangan dan riwayat transaksi penjualan. |
| How | Blade view dirender oleh library DOMPDF menjadi berkas biner PDF untuk kemudian diunduh pengguna. |

**Referensi:**
* https://github.com/barryvdh/laravel-dompdf

## 3. Maatwebsite Excel (Eksportasi Spreadsheet)

| 5W+1H | Penjelasan |
| ----- | ---------- |
| What | Pustaka Laravel untuk mengelola proses ekspor dan impor data dengan format Excel (XLSX, CSV). |
| Why | Memudahkan Owner/Mitra memproses analisis data keuangan atau produksi telur secara eksternal menggunakan Microsoft Excel. |
| Who | Owner dan Admin Sistem. |
| When | Saat proses evaluasi bisnis bulanan/tahunan atau migrasi data massal. |
| Where | Menu ekspor data pada modul laporan keuangan dan produksi harian. |
| How | Query database dipetakan ke dalam kelas Export khusus dan diunduh sebagai file spreadsheet. |

**Referensi:**
* https://docs.laravel-excel.com
* https://github.com/SpartnerNL/Laravel-Excel

## 4. Chart.js (Visualisasi Dashboard)

| 5W+1H | Penjelasan |
| ----- | ---------- |
| What | Library Javascript berbasis canvas untuk membuat grafik interaktif dan responsif di halaman web. |
| Why | Memvisualisasikan data tren produksi telur harian (HDP) dan perbandingan pengeluaran-pemasukan secara grafis agar mudah dianalisis. |
| Who | Owner dan Admin Sistem. |
| When | Saat melihat statistik data pada dashboard utama setelah login. |
| Where | Halaman Dashboard Monitoring Utama. |
| How | Data agregasi dikirim dari backend Laravel melalui objek JSON dan dirender oleh Chart.js menjadi grafik garis/batang. |

**Referensi:**
* https://www.chartjs.org/docs
* https://github.com/chartjs/Chart.js

## 5. ConsoleTVs Charts (Rencana Visualisasi Grafik Backend)

| 5W+1H | Penjelasan |
| ----- | ---------- |
| What | Package Laravel untuk membuat dan mengelola konfigurasi grafik dari sisi backend PHP sebelum dirender di frontend. |
| Why | Membantu mengabstraksi pembuatan grafik laporan penjualan dan tren produksi agar dapat dikontrol dari backend secara dinamis. |
| Who | Tim Pengembang dan Admin/Mitra. |
| When | Saat mempersiapkan data dashboard laporan yang membutuhkan penyajian visual data yang kompleks. |
| Where | Modul dashboard admin dan halaman laporan performa peternakan. |
| How | Diinstal via Composer dan dirender pada Blade templates menggunakan helper grafik bawaan package. |

**Referensi:**
* https://charts.erik.cat/
* https://github.com/ConsoleTVs/Charts

---

# Cara Install Dependency

## Install Dependency Composer

```bash
composer require nama-vendor/nama-package
```

## Contoh Install Backend

```bash
composer require barryvdh/laravel-dompdf
composer require spatie/laravel-activitylog
```

## Install Dependency Development

```bash
composer require nama-vendor/nama-package --dev
```

## Install Dependency Frontend

```bash
npm install nama-package
```

## Build Asset Frontend

```bash
npm run build
```

---

# Analisis Perubahan File Dependency

## composer.json

Mencatat package backend utama yang dipasang langsung secara deklaratif oleh tim pengembang beserta rentang versi yang diperbolehkan.

## composer.lock

Mengunci versi spesifik dari setiap package backend beserta package turunannya, memastikan seluruh lingkungan pengembang (lokal maupun production) menggunakan kode dependency yang identik.

## package.json

Mencatat package frontend dan perkakas pengembang (seperti Vite dan Tailwind CSS) yang dipasang untuk menyusun tampilan antarmuka.

## package-lock.json

Mengunci versi spesifik dari semua package frontend npm untuk menjaga konsistensi kompilasi aset frontend.

# Dampak Dependency pada Proyek

* **Mempercepat Proses Development:** Mengurangi pembuatan fungsionalitas umum dari nol (seperti konversi PDF, ekspor excel, atau log transaksi).
* **Standarisasi Keamanan:** Menggunakan pustaka yang didukung oleh komunitas aktif sehingga meminimalisir celah keamanan buatan sendiri.
* **Visualisasi dan Interaktivitas Premium:** Membantu memvisualisasikan data rumit peternakan ayam petelur dengan grafik interaktif dan pop-up yang estetik.
* **Konsistensi Lingkungan Kerja:** Membantu seluruh tim pengembang PBL bekerja dengan basis dependensi yang sama berkat file lock.

# Risiko Umum Dependency

* **Kompatibilitas Versi:** Pembaruan framework utama (misal migrasi ke versi Laravel selanjutnya) berisiko mematahkan fungsionalitas package pihak ketiga yang belum diperbarui.
* **Keamanan Pihak Ketiga:** Jika package tidak lagi dipelihara (*deprecated*), kerentanan keamanan baru tidak akan ditambal.
* **Konsumsi Sumber Daya:** Pustaka berat (seperti DOMPDF) berpotensi memperlambat response time server jika memproses data skala besar tanpa limitasi.
* **Vendor Lock-in:** Ketergantungan yang terlalu dalam membuat migrasi atau penggantian pustaka di kemudian hari membutuhkan perombakan kode yang signifikan.

# Evaluasi Dependency

Untuk saat ini, seluruh dependency inti yang terpasang pada TriFaCore (Laravel Framework, DOMPDF, Excel, Activitylog, Tailwind CSS, Alpine.js, dan Chart.js) telah disesuaikan dengan kebutuhan modul operasional peternakan Trifa Farm. Evaluasi secara berkala mutlak diperlukan sebelum merilis aplikasi ke tahap produksi, terutama terkait stabilitas integrasi endpoint API Python untuk modul AI Forecasting. Semua penambahan package baru harus melalui pengujian terisolasi terlebih dahulu di lokal sebelum digabungkan ke repositori utama.
