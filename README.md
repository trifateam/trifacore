# Sistem Informasi Manajemen Peternakan Ayam Petelur "Trifa Farm"

## Deskripsi Proyek
Aplikasi berbasis web untuk membantu pengelolaan pencatatan operasional harian secara real-time pada UMKM peternakan ayam petelur (Mitra: Trifa Farm).
- **Tujuan aplikasi:** Mendigitalisasi pencatatan produksi, inventaris pakan, dan transaksi keuangan.
- **Masalah yang diselesaikan:** Pencatatan manual yang rentan hilang, kesulitan rekapitulasi laba-rugi, dan tidak adanya visualisasi tren produksi yang jelas.
- **Target pengguna:** Karyawan (Pencatat lapangan), Pemilik Usaha (Pemantauan bisnis & analisa), dan Admin Sistem (Pengelola akses & master data).

## Fitur Utama
- Autentikasi Multi-Role (RBAC)
- Dashboard Monitoring Real-Time
- Manajemen Master Data (Kandang, Pelanggan, Barang)
- Pencatatan Harian (Produksi Telur/Pupuk, Konsumsi Pakan, Deplesi, Suhu)
- Manajemen Transaksi (Penjualan & Pembelian)
- Manajemen Keuangan (Buku Kas, Piutang, Utang)
- Laporan & Rekapitulasi (Laba-Rugi, Performa Produksi)
- Export Laporan (PDF & Excel)

## Tech Stack
- Laravel 13 (PHP 8.3+)
- MySQL / MariaDB
- Tailwind CSS & Alpine.js
- Composer & NPM (Vite)
- GitHub Actions (CI/CD)

## Instalasi Singkat
```bash
git clone https://github.com/trifateam/trifacore.git
cd trifacore
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
npm run build
php artisan serve
```

## Screenshot Proyek

**Halaman Landing Page**
![Landing Page](docs/screenshot/Landing%20Page.png)

**Autentikasi Login**
![Login](docs/screenshot/Autentikasi%20Login.png)

**Dashboard Monitoring**
![Dashboard](docs/screenshot/Dashboard%20Monitoring.png)

**Transaksi Penjualan Telur**
![Transaksi](docs/screenshot/Transaksi%20Penjualan%20Telur.png)

## Tim Pengembang (Kelompok 3 - PBL TRIFATEAM)
- Muhammad Ghalib Syabandi (2411083024) - Project Manager
- Nabila Mudika Putri (2411081036) - System Analyst
- Muhammad Ridho Syaputra (2411081041) - Lead Programmer
- Wildan Hafidh (2411082034) - AI Specialist
- Rafif Dirangga Martin (2411082040) - Quality Assurance