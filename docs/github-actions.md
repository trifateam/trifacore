# Dokumentasi GitHub Actions (CI/CD)

Repositori ini memanfaatkan fitur **GitHub Actions** untuk memastikan setiap kode yang terdorong (*pushed*) atau diusulkan (*pull request*) terjamin kualitas dan keutuhannya (Continuous Integration).

## Workflow yang digunakan
`Laravel CI` (Alur kerja utama yang menyatukan seluruh pemeriksaan kualitas kode Laravel). Meliputi proses instalasi dependensi, pemeriksaan gaya penulisan kode, pengetesan unit (Unit Test), dan kompilasi aset *frontend* murni dari lingkungan (environment) steril.

## Lokasi file konfigurasi
`.github/workflows/ci.yml`

## Trigger (Pemicu)
Workflow otomatis dijalankan pada saat terjadi:
- **Push** ke seluruh cabang (branches)
- **Pull Request**

## Tahapan Workflow
Berdasarkan berkas `.github/workflows/ci.yml`, alur (*job* `Laravel Test And Build`) dilakukan secara otomatis menggunakan OS `ubuntu-latest`:
1. **Checkout code:** Mengambil (kloning) *source code* dari repositori (menggunakan `actions/checkout@v4`).
2. **Setup PHP:** Menyiapkan lingkungan PHP 8.4 beserta semua *extension* yang relevan (seperti `dom`, `gd`, `zip`) menggunakan `shivammathur/setup-php@v2`.
3. **Setup Node.js:** Menyiapkan lingkungan NodeJS v20 untuk persiapan *build*.
4. **Composer install:** Mengunduh dan memasang paket pustaka dependensi (menggunakan `--optimize-autoloader`).
5. **Install NPM Dependencies:** Menarik semua pustaka JS pendukung dari `package.json`.
6. **Prepare Environment:** Membuat tiruan salinan `.env` & men-generate APP_KEY.
7. **Directory Permissions:** Memberikan hak akses penuh pada folder rentan yaitu `storage` dan `bootstrap/cache`.
8. **Check code syntax and style:** Memeriksa keseragaman standar gaya kode PHP secara ketat (menjalankan `vendor/bin/pint --test`).
9. **Build Frontend Assets:** Mengeksekusi kompilasi kode Tailwind & JS melalui Vite (`npm run build`).
10. **Execute tests:** Menjalankan Unit & Feature Testing (berbasis `sqlite :memory:`) menggunakan PHPUnit (`php artisan test`).

## Hasil Workflow

![GitHub Actions Status](https://github.com/trifateam/trifacore/actions/workflows/ci.yml/badge.svg)

> Status *badge* di atas secara *real-time* menampilkan warna hijau (Passing) apabila proses CI di *server* GitHub sukses dan lolos uji, atau menampilkan warna merah (Failing) jika ada kode yang melanggar standar (contoh: spasi *Pint* bermasalah) atau aplikasi mengalami *error* krusial (kompilasi aset gagal).

*Screenshot Workflow Actions di antarmuka GitHub:*
![GitHub Actions CI Workflow](screenshot/GitHub%20Actions%20CI.png)
*(Catatan: Screenshot ilustratif menyusul atau dapat dilihat langsung pada panel tab "Actions" di repositori)*
