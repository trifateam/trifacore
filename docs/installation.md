# Panduan Instalasi (Installation Documentation)

## 1. Persyaratan Sistem
Pastikan perangkat server atau komputer lokal Anda telah terinstal perangkat lunak berikut:
- **PHP** versi 8.3 atau yang lebih baru
- **Composer** (untuk manajemen dependensi PHP)
- **Node.js & NPM** (untuk kompilasi aset *frontend*)
- **MySQL / MariaDB** (sebagai basis data)
- **Git** (opsional, untuk kloning repositori)

## 2. Langkah Instalasi
Ikuti langkah-langkah di bawah ini secara berurutan untuk menjalankan proyek di lingkungan lokal Anda:

**1. Clone repository**
Buka terminal dan jalankan perintah berikut untuk mengunduh kode sumber proyek:
```bash
git clone https://github.com/trifateam/trifacore.git
cd trifacore
```

**2. Install dependency**
Instal seluruh *library* dan paket (baik PHP maupun JavaScript) yang dibutuhkan aplikasi:
```bash
composer install
npm install
```

**3. Setup environment**
Salin file konfigurasi bawaan dan hasilkan kunci aplikasi (App Key):
```bash
cp .env.example .env
php artisan key:generate
```
Lalu, buka file `.env` di teks editor Anda dan sesuaikan konfigurasi *database*:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=
```

**4. Setup database & storage**
Kaitkan folder penyimpanan publik dan jalankan migrasi tabel beserta *seeder* (data sampel):
```bash
php artisan storage:link
php artisan migrate --seed
```

**5. Menjalankan aplikasi**
Lakukan proses *build* pada aset tampilan web (Tailwind CSS & JS), kemudian nyalakan peladen (server) lokal Laravel:
```bash
npm run build
php artisan serve
```
Akses aplikasi melalui peramban (browser) di alamat: `http://localhost:8000`

---

## 3. Troubleshooting
Jika Anda menemui kendala saat instalasi atau saat aplikasi dijalankan, coba perhatikan beberapa solusi umum berikut:

- **Error permission denied (terutama Linux/macOS):**
  Aplikasi memerlukan hak akses penuh untuk membuat *cache* dan log. Jalankan perintah ini:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

- **Perintah NPM tidak dikenali (Windows):**
  Jika Anda menggunakan Windows PowerShell dan mengalami kendala larangan pengeksekusian (*execution policy*), gunakan akhiran `.cmd`:
  ```bash
  npm.cmd run build
  ```

- **Gambar, logo, atau lampiran tidak muncul:**
  Pastikan Anda tidak lupa untuk menjalankan tautan penyimpanan lokal:
  ```bash
  php artisan storage:link
  ```

- **Class "Barryvdh\DomPDF\Facade\Pdf" not found:**
  Bila terjadi eror saat mencetak laporan PDF, pastikan *package* telah ter-install utuh dan bersihkan *cache* konfigurasi:
  ```bash
  composer install
  php artisan config:clear
  ```
