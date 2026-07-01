# Refactoring Documentation

Status: Updated 2026-07-01. Dokumen ini diperbarui setiap ada perubahan struktur kode yang berdampak pada maintainability.

Dokumen ini mencatat perubahan struktur kode yang dilakukan untuk meningkatkan keterbacaan, maintainability, dan kesiapan evolusi sistem.

## Tujuan Refactoring

- Mengurangi duplikasi kode.
- Memisahkan tanggung jawab tampilan, konfigurasi UI, dan logic aplikasi.
- Membuat struktur kode lebih mudah dipelihara.
- Menyiapkan fondasi untuk pengembangan fitur berikutnya.
- Meningkatkan performa aplikasi secara keseluruhan (pengurangan overhead).

## Refactoring Awal Yang Sudah Dilakukan

## 1. Pemisahan Halaman Detail Riwayat Transaksi (Penjualan & Pembelian)

### Sebelum
Halaman riwayat transaksi menangani dan me-render daftar data beserta pop-up detail (Modal) menggunakan iterasi statis Alpine.js dalam satu file Blade (`index.blade.php`).

### Masalah
Struktur file menjadi sangat besar (ratusan baris kode overhead), peramban (browser) menjadi lambat memuat halaman, dan sering muncul masalah scrolling pada perangkat seluler akibat bentrokan Modal dengan UI native.

### Perubahan
Logic dipindahkan (di-ekstraksi) ke halaman khusus (dedicated page) berupa rute navigasi baru yaitu `show.blade.php`. Modal dihapus secara utuh.


## 2. Sentralisasi dan Server-Side Ekspor Pelaporan PDF (Laba-Rugi)

### Sebelum
Ekspor dokumen Laba-Rugi dan Performa mengandalkan pustaka Javascript `html2pdf.js` untuk merender halaman secara lokal (Client-Side). 

### Masalah
Pustaka client-side seringkali tidak kuat (crash) menginterpretasikan struktur utilitas Tailwind modern seperti color-function `oklch()`. Akibatnya, sistem mengalami freeze/hang atau gagal merender file (Attempts to parse unsupported color function). Selain itu, fungsi di Controller tidak seragam.

### Perubahan
Logic eksport dokumen dipindahkan 100% ke backend menggunakan library `barryvdh/laravel-dompdf`. Controller direfaktor agar memiliki konsistensi penamaan fungsi yang jelas: fungsi `generate()` untuk JSON, fungsi `preview()` untuk pratinjau browser, dan fungsi `pdf()` untuk mengunduh PDF hasil render server.


## 3. Pemisahan Komponen Menu Navigasi (Sidebar & Mobile Nav)

### Sebelum
Kode list navigasi sidebar (seperti URL menu dan ikon) di hard-code secara berulang pada komponen `<aside>` dan `<nav>` untuk desktop dan mobile.

### Masalah
Menambah menu baru sangat menyusahkan karena harus mengedit berbagai fail dan berpotensi memunculkan redundansi (bug inkonsistensi).

### Perubahan
Logic dipindahkan/diabstraksi dengan menyederhanakan data menu menggunakan struktur parameter di file utama, dan di-loop ke dalam komponen terpadu menggunakan variabel State/Konteks (Alpine.js).


## 4. Migrasi Arsitektur Master Data CRUD (Modal ke Multi-Page)

### Sebelum
Semua entitas Master Data (Barang, Kandang, Pegawai, Pelanggan, Rekening, Kategori Biaya, Supplier) menggunakan sistem pop-up Modal dengan Alpine.js untuk fitur Tambah (Create) dan Edit data pada satu halaman `index.blade.php`.

### Masalah
Logika form, validasi error state, dan passing data untuk Edit menjadi sangat kompleks di dalam view. HTML DOM membesar seiring banyaknya entitas, menyulitkan developer untuk melacak error dan me-maintenance form yang panjang (seperti master data Barang dan Kandang).

### Perubahan
Arsitektur Modal dibongkar sepenuhnya dan digantikan dengan aliran navigasi Multi-Page klasik. Form "Tambah Data" dan "Edit Data" kini memiliki file view independen (`create.blade.php` dan `edit.blade.php`) dan ditangani oleh route/method khusus di controller masing-masing entitas. Form `edit` sekarang menggunakan binding `old()` dengan parameter fallback dari model untuk memastikan keandalan saat terjadi error validasi.
