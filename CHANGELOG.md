# Changelog

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

### Fixed
- bug redirect login
- Perbaikan masalah tampilan dan logika pada form pelunasan hutang & piutang
- Perbaikan perilaku pemuatan halaman (page load) dan riwayat recording
- Perbaikan error controller pada halaman pencatatan suhu kandang
- Penghapusan elemen alert ganda pada antarmuka
- Perbaikan kesalahan konten pada dokumentasi dependensi

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

---
