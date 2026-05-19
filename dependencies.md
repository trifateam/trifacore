# ANALISIS DEPENDENCY / PACKAGE LARAVEL
## SISTEM INFORMASI MANAJEMEN OPERASIONAL DAN SISTEM PETERNAKAN "TRIFACORE" BERBASIS WEB

**Dosen Pengampu:**
* Defni, S.Si., M.Kom
* Mutia Rahmi Dewi, S.Kom., M.Kom

### Disusun Oleh

**Kelompok 3**
1. Muhammad Ghalib Syabandi
2. Muhammad Ridho Syaputra
3. Nabila Mudika Putri
4. Wildan Hafidh
5. Rafif Dirangga Martin


**PROGRAM STUDI TEKNOLOGI REKAYASA PERANGKAT LUNAK**
**JURUSAN TEKNOLOGI INFORMASI**
**POLITEKNIK NEGERI PADANG**

---

### 1. Laravel Breeze

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Laravel Breeze |
| **Why** | Digunakan untuk membangun sistem autentikasi pengguna (seperti login, logout, dan reset password) dengan aman, cepat, dan ringkas untuk membatasi hak akses sistem TriFaCore. |
| **Who** | Pegawai kandang/kasir dan Admin sistem TriFaCore. |
| **When** | Digunakan setiap kali pengguna (Pegawai atau Admin) ingin masuk ke dalam sistem sebelum dapat melakukan pencatatan harian atau mengelola transaksi peternakan. |
| **Where** | Diimplementasikan pada halaman autentikasi (Login Page) dan menjadi gerbang keamanan utama sebelum masuk ke dashboard aplikasi. |
| **How** | Diinstal melalui Composer menggunakan package Laravel Breeze, kemudian dijalankan menggunakan perintah Artisan untuk menghasilkan halaman login, middleware autentikasi, serta manajemen session user pada Laravel. |

* **Sumber Referensi:** https://laravel.com/docs/starter-kits#laravel-breeze

---

### 2. Laravel Livewire

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Laravel Livewire |
| **Why** | Digunakan untuk membangun antarmuka operasional yang dinamis dan realtime tanpa refresh halaman manual, seperti validasi stok pakan langsung saat diinput atau pemuatan jendela konfirmasi perbandingan data. |
| **Who** | Pegawai kandang yang menginput data operasional harian. |
| **When** | Digunakan saat pegawai mengisi form pencatatan produksi telur (UC-01), konsumsi pakan (UC-02), deplesi (UC-03), dan transaksi penjualan/pembelian secara interaktif. |
| **Where** | Modul formulir operasional harian kandang, modul transaksi, serta jendela pop-up konfirmasi verifikasi data (UC-01B, UC-02B, UC-03B). |
| **How** | Diinstal melalui Composer, kemudian diintegrasikan ke dalam template Blade sebagai komponen Livewire yang terhubung langsung ke backend Laravel sehingga manipulasi data di layar langsung merespons database secara asinkron. |

* **Sumber Referensi:** https://livewire.laravel.com/

---

### 3. Laravel Excel

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Laravel Excel |
| **Why** | Digunakan untuk mengekspor data rekapitulasi produksi telur harian, konsumsi pakan, deplesi ayam, dan data stock opname gudang ke dalam format spreadsheet (.xlsx) agar siap dianalisis atau dicetak secara berkala. |
| **Who** | Admin, Manajer Operasional, atau Owner peternakan TriFaCore. |
| **When** | Digunakan saat manajemen membutuhkan file laporan fisik bulanan atau mingguan untuk dianalisis lebih lanjut di Microsoft Excel. |
| **Where** | Diimplementasikan pada modul laporan produksi, inventaris gudang, dan halaman rekapitulasi data operasional. |
| **How** | Diinstal melalui Composer, dibuat menggunakan class export khusus Laravel Excel, kemudian dipicu melalui tombol ekspor di aplikasi yang memanggil fungsi `Excel::download()` untuk mengunduh data langsung dari database. |

* **Sumber Referensi:** https://laravel-excel.com/

---

### 4. Laravel DOMPDF

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Laravel DOMPDF |
| **Why** | Digunakan untuk menghasilkan (men-generate) dokumen resmi atau cetak bukti transaksi seperti Invoice Penjualan (UC-07) atau Nota Pembelian barang dari supplier dalam format PDF yang rapi dan tidak dapat diubah sepihak. |
| **Who** | Pegawai (Kasir), Pelanggan (pembeli telur/ayam), dan Supplier pakan/pullet. |
| **When** | Digunakan ketika proses transaksi penjualan/pembelian selesai disimpan atau saat pegawai memilih opsi "Cetak Invoice" atau "Unduh PDF" pada riwayat transaksi. |
| **Where** | Modul Cetak Invoice Transaksi Penjualan (UC-07) yard riwayat nota pembelian (UC-06). |
| **How** | Diinstal melalui Composer, layout diatur menggunakan template Blade standard HTML/CSS, kemudian diproses melalui fungsi `Pdf::loadView()` di controller untuk mengonversi tampilan Blade tersebut menjadi file PDF siap cetak atau unduh. |

* **Sumber Referensi:** https://github.com/barryvdh/laravel-dompdf

---

### 5. Laravel Charts (ConsoleTVs/Charts)

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Laravel Charts (ConsoleTVs/Charts) |
| **Why** | Digunakan untuk menyajikan visualisasi data statistik dalam bentuk grafik (tren produksi telur harian, fluktuasi suhu kandang (UC-04), dan tingkat deplesi populasi ayam) agar memudahkan evaluasi performa peternakan. |
| **Who** | Owner peternakan TriFaCore, Manajer, dan Admin. |
| **When** | Digunakan secara otomatis saat pengguna membuka dashboard utama sistem untuk melihat ringkasan performa dan tren data peternakan. |
| **Where** | Halaman utama Dashboard monitoring manajemen dan modul statistik produktivitas kandang. |
| **How** | Diinstal melalui Composer, dikonfigurasi melalui class Chart Laravel, kemudian controller mengambil data populasi atau produksi dari database dan mengirimkannya ke view untuk dirender sebagai grafik garis, batang, atau lingkaran. |

* **Sumber Referensi:** https://charts.erik.cat/

---

### 6. Laravel Debugbar

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Laravel Debugbar |
| **Why** | Digunakan untuk membantu tim developer dalam memantau performa aplikasi, melacak query database (agar tidak terjadi bottleneck data), mendeteksi error pada alur eksepsi (seperti validasi input), dan mempercepat proses development. |
| **Who** | Programmer / Developer sistem TriFaCore. |
| **When** | Aktif secara eksklusif selama tahap pengembangan (development mode) dan fase testing/pengujian sistem sebelum aplikasi disebarkan (production). |
| **Where** | Berjalan di backend Laravel dan tampil sebagai bilah informasi (toolbar) tambahan di bagian bawah browser developer. |
| **How** | Diinstal melalui Composer sebagai dev-dependency, berjalan otomatis saat aplikasi berada di environment `local` (`APP_DEBUG=true`), menampilkan rincian eksekusi query, waktu load halaman, request, dan memory usage secara real-time. |

* **Sumber Referensi:** https://github.com/barryvdh/laravel-debugbar

---

### 7. Filament

| 5 W + 1 H | Penjelasan |
| :--- | :--- |
| **What** | Filament (Filament PHP Panel Builder) |
| **Why** | Digunakan untuk membangun back-office admin panel atau modul Master Data Management (CRUD data kandang, pelanggan, supplier, manajemen user pegawai) dengan cepat, aman, dan berstandar industri menggunakan komponen TALL Stack. |
| **Who** | Admin Utama dan Owner peternakan TriFaCore. |
| **When** | Digunakan saat admin perlu melakukan konfigurasi data master, mendaftarkan kandang baru beserta kapasitas maksimalnya, mengelola daftar barang, dan mengonfigurasi hak akses pengguna sistem. |
| **Where** | Modul backend admin panel khusus manajemen data master dan konfigurasi sistem TriFaCore. |
| **How** | Diinstal melalui Composer (`composer require filament/filament`), dikonfigurasi menggunakan Panel Provider, dan resource dibuat menggunakan perintah Artisan (`php artisan make:filament-resource`) untuk menghasilkan form dan tabel manajemen data secara instan dan elegan. |

* **Sumber Referensi:** https://filamentphp.com/