# Dokumentasi Perbaikan Kode (Refactoring Documentation)

Catatan teknis perombakan arsitektur dan refactoring struktural untuk meningkatkan performa, kebersihan (*clean code*), dan kemudahan pemeliharaan (*maintainability*) dari aplikasi.

---

## 1. Pemisahan Halaman Detail Riwayat Transaksi (Penjualan & Pembelian)

**Sebelum**
Masalah: Halaman riwayat transaksi menangani dan me-render daftar data beserta *pop-up* detail (Modal) menggunakan iterasi statis Alpine.js dalam satu file Blade (`index.blade.php`). Akibatnya, struktur file menjadi sangat besar (ratusan baris kode *overhead*), peramban (browser) menjadi lambat memuat halaman, dan sering muncul masalah *scrolling* pada perangkat seluler.

**Perubahan**
Logic dipindahkan (di-ekstraksi) ke halaman khusus (*dedicated page*) berupa rute navigasi baru yaitu `show.blade.php`. Modal dihapus secara utuh.

**Alasan**
Memudahkan pemeliharaan (*maintenance*), menjadikan aplikasi lebih *mobile-friendly* (karena Modal sering bertabrakan dengan navigasi bawah seluler), dan merapikan tanggung jawab setiap halaman (Satu File = Satu Fungsi/Tampilan).

**Dampak**
Kode UI menjadi jauh lebih modular dan terisolasi. Ukuran file `index.blade.php` menyusut signifikan dan *loading time* antarmuka menjadi jauh lebih cepat. Rute dan sistem navigasi kini memiliki tautan spesifik untuk merujuk pada detail masing-masing ID transaksi.

---

## 2. Sentralisasi dan Server-Side Ekspor Pelaporan PDF (Laba-Rugi)

**Sebelum**
Masalah: Ekspor dokumen Laba-Rugi dan Performa mengandalkan pustaka Javascript `html2pdf.js` untuk merender halaman secara lokal (Client-Side). Pustaka tersebut seringkali tidak kuat (*crash*) menginterpretasikan struktur utilitas Tailwind modern seperti *color-function* `oklch()`. Akibatnya, sistem mengalami *freeze/hang* atau gagal merender file (Attempts to parse unsupported color function). Selain itu, fungsi di Controller (seperti `CetakProduksiController` dan `LabaRugiController`) tidak seragam.

**Perubahan**
Logic eksport dokumen dipindahkan 100% ke *backend* menggunakan *library* `barryvdh/laravel-dompdf`. Controller direfaktor agar memiliki konsistensi penamaan fungsi yang jelas: fungsi `generate()` untuk JSON, fungsi `preview()` untuk pratinjau browser, dan fungsi `pdf()` untuk mengunduh PDF hasil render *server*.

**Alasan**
Memudahkan maintenance dengan mengisolasi template khusus ekspor PDF (berbasis HTML/CSS tabel murni di luar Tailwind). Memastikan sistem tidak pernah crash di sisi pengguna (*browser client*) meskipun menggunakan sistem dan peramban usang. 

**Dampak**
Aplikasi kini lebih tangguh (*robust*). Pengguna mendapat dokumen bersih, berukuran kecil, langsung berbentuk *.pdf* tanpa harus melewati dialog konfirmasi pencetakan *browser*.

---

## 3. Pemisahan Komponen Menu Navigasi (Sidebar & Mobile Nav)

**Sebelum**
Masalah: Kode list navigasi sidebar (seperti URL menu dan ikon) di *hard-code* secara berulang pada komponen `<aside>` dan `<nav>` untuk *desktop* dan *mobile*. Menambah menu baru sangat menyusahkan karena harus mengedit berbagai fail.

**Perubahan**
Logic dipindahkan/diabstraksi dengan menyederhanakan data menu menggunakan struktur parameter di file utama, dan di-*loop* ke dalam komponen terpadu menggunakan variabel State/Konteks (Alpine.js).

**Alasan**
Memudahkan maintenance, menyingkirkan redundansi data.

**Dampak**
Kode lebih *modular*. Setiap perubahan tautan *route* (misalnya memisahkan "Riwayat" ke modul luar) hanya perlu di-ubah sekali di file konfigurasi pusat (sidebar menu config), dan akan berlaku otomatis untuk UI web maupun aplikasi seluler secara simetris.
