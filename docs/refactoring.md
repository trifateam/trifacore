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

Halaman riwayat transaksi menangani dan me-render daftar data beserta pop-up detail (Modal) menggunakan iterasi statis Alpine.js dalam satu file Blade.

### Masalah

Struktur file menjadi sangat besar (ratusan baris kode overhead), peramban (browser) menjadi lambat memuat halaman, dan sering muncul masalah scrolling pada perangkat seluler akibat bentrokan Modal dengan UI native.

### Perubahan

Logic dipindahkan (di-ekstraksi) ke halaman khusus (dedicated page) berupa rute navigasi baru yaitu `show.blade.php`. Modal dihapus secara utuh.

```text
resources/views/riwayat/penjualan/show.blade.php
resources/views/riwayat/pembelian/show.blade.php
app/Http/Controllers/Riwayat/RiwayatPenjualanController.php
app/Http/Controllers/Riwayat/RiwayatPembelianController.php
```

### Alasan

Memudahkan maintenance, menjadikan aplikasi lebih mobile-friendly (karena Modal sering bertabrakan dengan navigasi bawah seluler), dan merapikan tanggung jawab setiap halaman (Satu File = Satu Fungsi/Tampilan).

### Dampak

- Kode UI menjadi jauh lebih modular dan terisolasi.
- Ukuran file `index.blade.php` menyusut signifikan dan loading time antarmuka menjadi jauh lebih cepat.
- Rute dan sistem navigasi kini memiliki tautan spesifik untuk merujuk pada detail masing-masing ID transaksi.

## 2. Sentralisasi dan Server-Side Ekspor Pelaporan PDF (Laba-Rugi)

### Sebelum

Ekspor dokumen Laba-Rugi dan Performa mengandalkan pustaka Javascript `html2pdf.js` untuk merender halaman secara lokal (Client-Side). 

### Masalah

Pustaka client-side seringkali tidak kuat (crash) menginterpretasikan struktur utilitas Tailwind modern seperti color-function `oklch()`. Akibatnya, sistem mengalami freeze/hang atau gagal merender file (Attempts to parse unsupported color function). Selain itu, fungsi di Controller tidak seragam.

### Perubahan

Logic eksport dokumen dipindahkan 100% ke backend menggunakan library `barryvdh/laravel-dompdf`. Controller direfaktor agar memiliki konsistensi penamaan fungsi yang jelas: fungsi `generate()` untuk JSON, fungsi `preview()` untuk pratinjau browser, dan fungsi `pdf()` untuk mengunduh PDF hasil render server.

```text
app/Http/Controllers/Laporan/LabaRugiController.php
app/Http/Controllers/Laporan/ProduksiPerformaController.php
resources/views/laporan/cetak/laba-rugi.blade.php
```

### Alasan

Memudahkan maintenance dengan mengisolasi template khusus ekspor PDF (berbasis HTML/CSS tabel murni di luar Tailwind). Memastikan sistem tidak pernah crash di sisi pengguna (browser client) meskipun menggunakan sistem dan peramban usang.

### Dampak

- Aplikasi kini lebih tangguh (robust).
- Pengguna mendapat dokumen bersih, berukuran kecil, langsung berbentuk `.pdf` tanpa harus melewati dialog konfirmasi pencetakan browser.

## 3. Pemisahan Komponen Menu Navigasi (Sidebar & Mobile Nav)

### Sebelum

Kode list navigasi sidebar (seperti URL menu dan ikon) di hard-code secara berulang pada komponen `<aside>` dan `<nav>` untuk desktop dan mobile.

### Masalah

Menambah menu baru sangat menyusahkan karena harus mengedit berbagai fail dan berpotensi memunculkan redundansi (bug inkonsistensi).

### Perubahan

Logic dipindahkan/diabstraksi dengan menyederhanakan data menu menggunakan struktur parameter di file utama, dan di-loop ke dalam komponen terpadu menggunakan variabel State/Konteks (Alpine.js).

```text
resources/views/layouts/sidebar.blade.php
resources/views/layouts/bottom-navbar.blade.php
resources/views/components/sidebar-dropdown.blade.php
```

### Alasan

Memudahkan maintenance dan menyingkirkan redundansi data. Setiap perubahan tautan route (misalnya memisahkan "Riwayat" ke modul luar) hanya perlu di-ubah sekali.

### Dampak

- Kode lebih modular.
- Konfigurasi menu pusat berlaku otomatis untuk UI web maupun aplikasi seluler secara simetris.

## 4. Migrasi Arsitektur Master Data CRUD (Modal ke Multi-Page)

### Sebelum

Semua entitas Master Data (Barang, Kandang, Pegawai, Pelanggan, Rekening, Kategori Biaya, Supplier) menggunakan sistem pop-up Modal dengan Alpine.js untuk fitur Tambah (Create) dan Edit data pada satu halaman `index.blade.php`.

### Masalah

Logika form, validasi error state, dan passing data untuk Edit menjadi sangat kompleks di dalam view. HTML DOM membesar seiring banyaknya entitas, menyulitkan developer untuk melacak error dan me-maintenance form yang panjang (seperti master data Barang dan Kandang).

### Perubahan

Arsitektur Modal dibongkar sepenuhnya dan digantikan dengan aliran navigasi Multi-Page klasik. Form "Tambah Data" dan "Edit Data" kini memiliki file view independen.

```text
app/Http/Controllers/MasterData/*Controller.php
resources/views/master-data/*/create.blade.php
resources/views/master-data/*/edit.blade.php
resources/views/master-data/*/index.blade.php
```

### Alasan

Pemisahan halaman membuat form lebih fokus. Binding `old()` dari sesi request dapat digunakan secara native, fallback parameter untuk validasi dapat diproses dengan jauh lebih andal ketimbang mengandalkan Javascript, dan performa halaman utama (`index`) menjadi sangat ringan.

### Dampak

- File `index.blade.php` pada master data menjadi lebih pendek rata-rata 50-70%.
- Potensi hilangnya state validasi form ketika halaman di-refresh menghilang.
- Interaksi pengguna menjadi lebih intuitif karena setiap aksi (Create/Edit) memiliki URL rute unik yang dapat ditautkan secara langsung.
