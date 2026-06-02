# TriFaCore - Sistem Informasi Manajemen Peternakan Ayam Petelur "Trifa Farm"

## Deskripsi Proyek
Proyek ini bertujuan untuk mengembangkan TriFaCore, sebuah Sistem Informasi Manajemen Peternakan Berbasis Web sebagai solusi digital untuk digitalisasi pencatatan operasional pada UMKM peternakan ayam petelur (Mitra: Trifa Farm). Sistem ini mengintegrasikan pengelolaan data operasional harian secara *real-time* dan dipadukan dengan teknologi *Artificial Intelligence* (AI) berbasis *Time Series Forecasting* serta *Linear Regression* untuk memprediksi produksi telur.

Dokumentasi proyek disusun agar aplikasi mudah dipasang, dipelihara, dikembangkan, dan digunakan sebagai dasar kolaborasi tim. Proyek ini juga dirancang untuk mendukung pencapaian SDGs 8 (Pekerjaan Layak dan Pertumbuhan Ekonomi) melalui mentransformasi manajemen UMKM peternakan menjadi lebih modern, efisien, dan transparan.

## Tujuan Proyek
- Mengembangkan aplikasi manajemen peternakan berbasis web untuk mendigitalisasi pencatatan produksi, inventaris, dan transaksi keuangan.
- Mengimplementasikan teknologi AI untuk memprediksi hasil produksi telur harian dan mendeteksi potensi penurunan hasil panen secara dini dengan target akurasi minimal 80%.
- Menyediakan *dashboard* interaktif untuk memonitor metrik penting peternakan seperti HDP (*Hen Day Production*) dan FCR (*Feed Conversion Ratio*) secara *real-time*.
- Menyajikan rekapitulasi laporan keuangan (pemasukan, pengeluaran, laba-rugi) secara otomatis untuk mendukung pengambilan keputusan bisnis mitra.
- Membangun struktur kode dan ekosistem perangkat lunak yang *scalable* agar mudah dipelihara serta dikembangkan secara kolaboratif.

## Masalah Yang Diselesaikan
- Proses pencatatan produksi telur, inventaris pakan, dan keuangan yang sebelumnya masih manual (menggunakan buku tulis) sehingga rentan hilang, terselip, atau rusak.
- Kesulitan dan lamanya waktu yang dibutuhkan manajemen untuk menghitung laporan laba-rugi operasional secara akurat.
- Tidak tersedianya perangkat visual (grafik) untuk menganalisis pola atau tren fluktuasi produksi telur dari waktu ke waktu.
- Ketidakmampuan pemilik usaha dalam memperkirakan risiko penurunan produksi di masa depan, yang menyulitkan perencanaan distribusi dan mitigasi risiko.

## Target Pengguna
- **Karyawan (Pegawai):** Bertindak sebagai operator lapangan yang bertugas menginput data harian seperti jumlah panen telur (berdasarkan *grade*), penggunaan dan pembelian stok pakan, serta mencatat transaksi penjualan.
- **Pemilik Usaha (Owner):** Memantau laporan kinerja bisnis dari jarak jauh melalui *dashboard* interaktif, melihat laporan keuangan otomatis, serta menganalisis hasil rekomendasi dan grafik prediksi AI.
- **Admin Sistem:** Mengelola hak akses pengguna (RBAC), memelihara fungsionalitas sistem, dan mengelola entitas *Master Data* (Data Kandang, Data Pelanggan, Data Inventaris).

## Fitur Utama

### Sisi Operasional & Transaksi (Karyawan)
- **Pencatatan Produksi Telur:** Menginput jumlah hasil panen harian berdasarkan *grade* (RB, MB, MK, Pecah/Rusak).
- **Manajemen Stok Pakan:** Mencatat barang masuk (pembelian pakan/obat) dan riwayat konsumsi harian untuk populasi ayam.
- **Manajemen Penjualan:** Mencatat transaksi penjualan telur ke pelanggan atau pengepul dan merilis struk/nota penjualan.

### Sisi Pemantauan & Bisnis (Owner & Admin)
- **Halaman Profil Publik (Landing Page):** Tampilan representasi digital dan *branding* profil Trifa Farm.
- **Autentikasi Multi-Role:** Login dan Logout dengan pembatasan hak akses *dashboard* berdasarkan peran.
- **Dashboard Monitoring Real-Time:** Menampilkan ringkasan metrik statistik populasi ayam, perbandingan pemasukan vs pengeluaran, dan grafik produksi (HDP & FCR).
- **Laporan Keuangan & Export:** Sistem kalkulasi otomatis untuk menghasilkan arsip laporan laba-rugi operasional (*export* ke PDF/Excel).
- **AI Production Forecasting (Fitur Cerdas):** Visualisasi prediksi tren produksi telur menggunakan *Linear Regression* pada *dashboard*, dilengkapi dengan sistem notifikasi/alert peringatan jika terdeteksi anomali penurunan produksi.
- **Manajemen Master Data:** Kelola Kandang, Kelola Pelanggan, dan Kelola Barang Gudang (Admin).

## Tech Stack
- **Framework & Language:** Laravel 13 / PHP 8.3+
- **AI / Machine Learning API:** Python (Flask / FastAPI)
- **Database:** MySQL / MariaDB
- **Frontend:** Tailwind CSS, Blade Templates, Alpine.js
- **Build Tool:** Vite, Node.js & NPM
- **Dependency / Packages:**
  - `barryvdh/laravel-dompdf` (Cetak PDF rekap laporan keuangan & produksi)
  - `blade-ui-kit/blade-heroicons` (Integrasi ikon UI)
  - `maatwebsite/excel` (Export/Import laporan format spreadsheet)
  - `spatie/laravel-activitylog` (Audit trail/Riwayat log aktivitas *user*)
  - `Chart.js` (Visualisasi metrik *dashboard* dan AI Forecasting)

## Instalasi Singkat

```bash
# Kloning repositori
git clone https://github.com/trifateam/trifacore.git
cd trifacore

# Install dependensi backend & frontend
composer install
npm install

# Salin environment file & buat key
cp .env.example .env
php artisan key:generate

# Link storage & jalankan migrasi beserta seeders
php artisan storage:link
php artisan migrate --seed

# Build aset frontend & jalankan server lokal
npm run build
php artisan serve
```

> [!NOTE]
> **Catatan Windows PowerShell:** Jika `npm` atau `npx` diblokir karena *execution policy*, gunakan `npm.cmd` dan `npx.cmd`.

Dokumentasi instalasi lengkap tersedia di [docs/installation.md](docs/installation.md).

## Struktur Dokumentasi

```plaintext
README.md
CHANGELOG.md
docs/
├── installation.md
├── features.md
├── dependency.md
├── refactoring.md
└── github-actions.md
```

## Screenshot Proyek

Screenshot aplikasi akan ditambahkan setelah halaman siap digunakan dan telah diverifikasi.

Rencana screenshot minimal:
- Halaman Landing Page representasi usaha Trifa Farm.
- Halaman Login dengan UI responsif.
- Halaman Dashboard pemantauan Owner (Grafik tren, HDP, FCR, dan Prediksi AI).
- Halaman formulir input produksi harian Karyawan.
- Halaman laporan laba-rugi keuangan otomatis.

## Documentation

| Dokumen | Deskripsi |
| :--- | :--- |
| [docs/installation.md](docs/installation.md) | Panduan instalasi lokal, setup database, dan troubleshooting |
| [docs/features.md](docs/features.md) | Dokumentasi Use Case dan alur kerja fitur aplikasi |
| [docs/dependency.md](docs/dependency.md) | Dokumentasi package Laravel pihak ketiga beserta analisa risikonya |
| [docs/refactoring.md](docs/refactoring.md) | Catatan refactoring dan perbaikan struktur kode |
| [docs/github-actions.md](docs/github-actions.md) | Rencana workflow CI/CD untuk repositori |
| [CHANGELOG.md](CHANGELOG.md) | Riwayat perubahan proyek dan evolusi sistem dari seluruh tim |

## Tim Pengembang (Kelompok 3 - PBL TRIFATEAM)

| Nama | NIM | Peran Proyek |
| :--- | :---: | :--- |
| Muhammad Ghalib Syabandi | 2411083024 | Project Manager |
| Nabila Mudika Putri | 2411081036 | System Analyst |
| Muhammad Ridho Syaputra | 2411081041 | Lead Programmer |
| Wildan Hafidh | 2411082034 | AI Specialist |
| Rafif Dirangga Martin | 2411082040 | Quality Assurance |

## Repository
Repositori ini digunakan sebagai pusat manajemen versi (version control), kolaborasi penulisan kode, penyimpanan dokumentasi teknis, serta pencatatan riwayat evolusi perangkat lunak (changelog) dari awal pengembangan hingga rilis final. Tautan repositori: https://github.com/trifateam/trifacore.git