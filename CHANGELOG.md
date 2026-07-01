# Changelog

## v1.1.0

### Added
- Fitur cetak (preview & export) laporan PDF *server-side* untuk laporan Laba Rugi

### Changed
- Sentralisasi modul "Riwayat" (*History*) dari halaman masing-masing menu ke dalam menu induk tersendiri (*Top-Level Menu*)
- Pemolesan dan standarisasi kartu (*cards*) antarmuka manajemen riwayat & transaksi
- Redesign tata letak (*layout*) dan navigasi *sidebar* menggunakan arsitektur Alpine.js dinamis
- Pembatasan dan modifikasi hak akses *routing* sub-menu berdasarkan *role* di *sidebar* & *bottom navbar*
- Pembaruan dokumentasi README, panduan instalasi, struktur dependensi, dan *changelog*

### Fixed
- Penanganan dan penangkapan (*handling*) masalah fatal `Throwable` pada pembuatan ekspor laporan PDF 
- Masalah konflik fungsi CSS khusus modern (`oklch()`) dengan pustaka *render* HTML bawaan
- Perbaikan masalah pemilihan/seleksi elemen Canvas Chart akibat bentrokan penamaan ID di halaman laporan performa
- Solusi atas kegagalan perenderan *layout print* akibat penimpaan *style class hidden*

### Dependency
- add `barryvdh/laravel-dompdf` (Cetak *server-side* PDF laporan Laba Rugi dan Produksi Telur; ukuran 3.x; bebas dari konflik CSS modern)
- remove `html2pdf.js` (Pustaka ini dihapus karena konflik dengan variabel/fungsi CSS modern bawaan Tailwind)

### Refactor
- Memindahkan logika antarmuka Riwayat Transaksi (Penjualan/Pembelian) dari integrasi Modal UI menjadi Halaman Khusus (`show.blade.php`) untuk menjaga aksesibilitas responsif
- Mengubah alur routing modul "Riwayat" menjadi satu keluarga *Controller* terpadu guna meringankan beban kode di *controller* pencatatan
- Melakukan standardisasi penamaan metode (`generate()`, `pdf()`, `preview()`) di dalam kelompok fitur Laporan (Laba Rugi, Performa, dll)
- Pemisahan (ekstraksi) komponen antarmuka *sidebar* statis ke konfigurasi dinamis JSON/Blade agar mudah disinkronkan dengan navigasi *mobile* (Bottom Navbar)

---

## v1.0.0

### Added
- fitur login
- fitur CRUD data
- Fitur peringatan stok pakan minimum pada dashboard
- Tampilan responsif (mobile view) dengan navigasi bawah (bottom navbar) dan panel submenu interaktif untuk perangkat seluler
- Halaman dashboard monitoring dengan ringkasan data, grafik tren, dan alert stok
- Fitur pengunggahan logo untuk pengaturan profil dan sistem
- Pencatatan riwayat aktivitas pengguna (audit trail) terintegrasi
- Pencatatan produksi harian (telur dan pupuk) dengan pembaruan otomatis stok
- Pencatatan konsumsi pakan dan vitamin harian dengan pembaruan otomatis stok
- Pencatatan deplesi ayam harian dengan pembaruan otomatis populasi kandang
- Pencatatan suhu kandang harian
- Halaman riwayat recording untuk pencatatan harian
- Transaksi penjualan (telur, ayam afkir, pupuk)
- Transaksi pembelian (material gudang, pullet ayam)
- Halaman riwayat transaksi penjualan dan pembelian dengan filter dan detail
- Halaman kandang operasional (penempatan pullet dan monitoring populasi)
- Halaman gudang inventory (stock opname dan peringatan stok kritis)
- Pencatatan biaya operasional dengan pembaruan otomatis saldo kas
- Halaman buku kas utama
- Buku utang dengan pelunasan partial dan tracking jatuh tempo
- Buku piutang dengan pelunasan partial dan tracking jatuh tempo
- Laporan performa produksi dengan grafik Chart.js
- Laporan laba rugi (P&L) dengan rincian arus kas
- Fitur cetak PDF untuk laporan produksi telur, penjualan, dan pembelian
- Middleware role-based access control (RBAC) untuk proteksi route
- Library komponen UI reusable berbasis Blade dan Tailwind CSS
- Layout utama dengan navigasi sidebar dan Alpine.js
- Migrasi database untuk seluruh tabel sistem
- Dummy data seeder untuk keperluan demonstrasi
- Integrasi alur CI/CD untuk otomatisasi Pull Request dan Merge
- Halaman landing page (Landing Page)

### Changed
- perbaikan dashboard
- Query dashboard untuk menambahkan filter barang kategori Pakan
- Scope baru pada model Barang (`scopePakan`, `scopeStokRendah`)
- Pembatasan hak akses menu riwayat (pencatatan dan transaksi) berdasarkan role pengguna pada sidebar
- Redesign tata letak, navigasi, dan perilaku sidebar UI
- Restrukturisasi komponen UI dan implementasi mode gelap (dark mode)
- Pemolesan antarmuka pengguna (UI Polish) secara menyeluruh
- Pembaruan dan penyesuaian palette warna baru pada sistem
- Pembaruan dan peningkatan dokumentasi proyek (README, panduan instalasi, dependensi, dan fitur)
- Penghapusan berkas-berkas yang tidak digunakan
- Redesign form login dan menu kandang
- Redesign branding dan judul logo aplikasi

### Fixed
- bug redirect login
- Perbaikan masalah tampilan dan logika pada form pelunasan hutang & piutang
- Perbaikan perilaku pemuatan halaman (page load) dan riwayat recording
- Perbaikan error controller pada halaman pencatatan suhu kandang
- Penghapusan elemen alert ganda pada antarmuka
- Perbaikan kesalahan konten pada dokumentasi dependensi
- Perbaikan pesan peringatan (warning message) pada berkas app.css
- Perbaikan/penghapusan fitur `is_active` yang tidak digunakan

---

### Removed
- Fitur "Remember me" pada form login
- Fitur registrasi akun (Sign up)
- Skrip utilitas sekali pakai (`add_audit_master.php`, script format rupiah)

---

### Dependency
- add Laravel Excel
- upgrade package
- Instalasi dan konfigurasi awal dependensi framework Laravel 13
- Integrasi package Spatie Laravel Activitylog untuk pencatatan log aktivitas
- Integrasi package Barryvdh Laravel DOMPDF untuk ekspor PDF
- Integrasi library frontend (Chart.js, SweetAlert2, Alpine.js, @alpinejs/collapse)
- Penambahan font keluarga Inter (@fontsource/inter) secara lokal

---

### Refactor
- memindahkan logic ke service
- Refaktor helper generator kode otomatis (CodeGenerator) dan pemformatan mata uang Rupiah (RupiahFormatter) untuk konsistensi
