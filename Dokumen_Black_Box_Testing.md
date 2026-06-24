# Dokumen Pengujian Sistem (Black Box Testing)

**Sistem:** Sistem Manajemen Peternakan Ayam Petelur

Dokumen ini berisi skenario pengujian Black Box untuk fungsionalitas sistem manajemen peternakan ayam petelur. Pengujian dilakukan berdasarkan spesifikasi kebutuhan untuk memastikan setiap fitur berjalan sesuai dengan hasil yang diharapkan.

---

## A. Test Case Pelanggan

### 1. Landing Page
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-LDP-001 | Menampilkan halaman utama Landing Page | 1. Buka browser<br>2. Masukkan URL sistem<br>3. Tekan Enter | - | Sistem berhasil memuat dan menampilkan halaman utama Landing Page dengan informasi peternakan yang lengkap. | [Lampiran Screenshot Hasil Pengujian] | Pass |

---

## B. Test Case Admin

### 1. Login
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-LGN-001 | Login berhasil | 1. Buka halaman login<br>2. Masukkan username valid<br>3. Masukkan password valid<br>4. Klik tombol "Login" | Username: "admin"<br>Password: "password123" | Sistem mengautentikasi pengguna, mengarahkan ke halaman Dashboard Admin, dan menampilkan pesan sukses. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-LGN-002 | Password salah | 1. Buka halaman login<br>2. Masukkan username valid<br>3. Masukkan password yang salah<br>4. Klik tombol "Login" | Username: "admin"<br>Password: "salah123" | Sistem menolak akses dan menampilkan pesan peringatan "Password salah". | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-LGN-003 | Username tidak ditemukan | 1. Buka halaman login<br>2. Masukkan username yang belum terdaftar<br>3. Masukkan password<br>4. Klik tombol "Login" | Username: "anonim"<br>Password: "password123" | Sistem menolak akses dan menampilkan pesan peringatan "Username tidak ditemukan". | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-LGN-004 | Field kosong | 1. Buka halaman login<br>2. Kosongkan field username dan password<br>3. Klik tombol "Login" | - | Sistem menolak akses dan menampilkan validasi form yang meminta pengguna untuk mengisi field yang wajib. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 2. Dashboard
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-DSH-001 | Menampilkan halaman Dashboard | 1. Login sebagai Admin<br>2. Navigasi ke menu "Dashboard" | - | Sistem menampilkan halaman Dashboard dengan metrik, grafik, dan ringkasan data peternakan secara akurat. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 3. Riwayat Pencatatan Produksi Telur
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PTL-001 | Menampilkan data riwayat produksi | 1. Navigasi ke menu "Riwayat Produksi Telur" | - | Sistem memuat dan menampilkan tabel berisi seluruh data riwayat produksi telur. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PTL-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Produksi Telur"<br>2. Masukkan kata kunci atau rentang tanggal pada kolom pencarian/filter<br>3. Tekan Enter / klik tombol "Cari" | Kata kunci: "Kandang A"<br>atau Tanggal: "01/01/2026" | Sistem memperbarui tabel dan hanya menampilkan data riwayat yang sesuai dengan parameter pencarian/filter. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PTL-003 | Detail data riwayat produksi | 1. Navigasi ke menu "Riwayat Produksi Telur"<br>2. Klik tombol "Detail" pada salah satu baris data | - | Sistem memunculkan halaman atau modal yang menampilkan informasi rincian dari data pencatatan produksi telur yang dipilih. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 4. Riwayat Pencatatan Konsumsi Pakan
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-KPK-001 | Menampilkan data riwayat pakan | 1. Navigasi ke menu "Riwayat Konsumsi Pakan" | - | Sistem memuat dan menampilkan tabel berisi seluruh data riwayat konsumsi pakan. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KPK-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Konsumsi Pakan"<br>2. Masukkan parameter pencarian/filter<br>3. Klik tombol "Cari" | - | Sistem memfilter tabel untuk menampilkan data riwayat konsumsi pakan yang relevan. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KPK-003 | Detail data riwayat pakan | 1. Navigasi ke menu "Riwayat Konsumsi Pakan"<br>2. Klik tombol "Detail" pada sebuah data | - | Sistem menampilkan informasi rincian jenis pakan, jumlah, dan kandang yang mengkonsumsi pakan tersebut. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 5. Riwayat Pencatatan Konsumsi Vitamin
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-KVT-001 | Menampilkan data riwayat vitamin | 1. Navigasi ke menu "Riwayat Konsumsi Vitamin" | - | Sistem memuat dan menampilkan tabel berisi seluruh data riwayat konsumsi vitamin. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KVT-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Konsumsi Vitamin"<br>2. Masukkan parameter pencarian/filter<br>3. Klik tombol "Cari" | - | Sistem memfilter tabel untuk menampilkan data riwayat konsumsi vitamin yang sesuai. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KVT-003 | Detail data riwayat vitamin | 1. Navigasi ke menu "Riwayat Konsumsi Vitamin"<br>2. Klik tombol "Detail" pada sebuah data | - | Sistem menampilkan rincian pemberian vitamin, jenis vitamin, dosis, dan target kandang. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 6. Riwayat Pencatatan Deplesi (Kematian/Afkir)
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-DPL-001 | Menampilkan data riwayat deplesi | 1. Navigasi ke menu "Riwayat Deplesi" | - | Sistem memuat dan menampilkan tabel daftar ayam mati atau afkir. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-DPL-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Deplesi"<br>2. Masukkan parameter pencarian/filter<br>3. Klik tombol "Cari" | - | Sistem menampilkan hasil filter data deplesi berdasarkan tanggal atau kriteria lain. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-DPL-003 | Detail data riwayat deplesi | 1. Navigasi ke menu "Riwayat Deplesi"<br>2. Klik tombol "Detail" pada sebuah data | - | Sistem menampilkan informasi rincian penyebab kematian/afkir, jumlah, dan asal kandang. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 7. Riwayat Pencatatan Suhu Kandang
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-SHK-001 | Menampilkan data riwayat suhu | 1. Navigasi ke menu "Riwayat Suhu Kandang" | - | Sistem menampilkan daftar pencatatan suhu kandang dalam bentuk tabel/grafik. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-SHK-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Suhu Kandang"<br>2. Pilih rentang waktu dan klik "Cari" | - | Sistem memfilter data pencatatan suhu berdasarkan rentang waktu spesifik. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-SHK-003 | Detail data riwayat suhu | 1. Navigasi ke menu "Riwayat Suhu Kandang"<br>2. Klik tombol "Detail" pada sebuah data | - | Sistem menampilkan waktu pencatatan, suhu yang terukur, tingkat kelembapan, dan lokasi kandang. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 8. Riwayat Pencatatan Produksi Pupuk
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PPK-001 | Menampilkan data riwayat pupuk | 1. Navigasi ke menu "Riwayat Produksi Pupuk" | - | Sistem memuat dan menampilkan tabel berisi daftar produksi pupuk dari kotoran ayam. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PPK-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Produksi Pupuk"<br>2. Masukkan parameter pencarian dan klik "Cari" | - | Sistem memfilter tabel untuk menampilkan riwayat pupuk yang sesuai. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PPK-003 | Detail data riwayat pupuk | 1. Navigasi ke menu "Riwayat Produksi Pupuk"<br>2. Klik tombol "Detail" pada data | - | Sistem menampilkan volume pupuk yang dihasilkan, tanggal pengambilan, dan penanggung jawab. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 9. Riwayat Penjualan
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PJL-001 | Menampilkan data riwayat penjualan | 1. Navigasi ke menu "Riwayat Penjualan" | - | Sistem menampilkan seluruh data transaksi penjualan (telur, pupuk, atau ayam afkir). | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PJL-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Penjualan"<br>2. Ketik nama pelanggan/nomor invoice dan klik "Cari" | Invoice: "INV-001" | Sistem memfilter data penjualan sesuai dengan kata kunci pencarian. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PJL-003 | Detail data riwayat penjualan | 1. Navigasi ke menu "Riwayat Penjualan"<br>2. Klik tombol "Detail" pada salah satu transaksi | - | Sistem memunculkan detail invoice, barang yang dijual, harga, pelanggan, dan total transaksi. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 10. Riwayat Pembelian
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PBL-001 | Menampilkan data riwayat pembelian | 1. Navigasi ke menu "Riwayat Pembelian" | - | Sistem menampilkan tabel transaksi pembelian (pakan, vitamin, aset). | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PBL-002 | Pencarian/filter data | 1. Navigasi ke menu "Riwayat Pembelian"<br>2. Masukkan nama supplier/tanggal dan klik "Cari" | Supplier: "PT. Pakan Sejahtera" | Sistem memfilter daftar pembelian berdasarkan kata kunci. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PBL-003 | Detail data riwayat pembelian | 1. Navigasi ke menu "Riwayat Pembelian"<br>2. Klik tombol "Detail" | - | Sistem menampilkan rincian barang yang dibeli, harga satuan, supplier, dan total tagihan. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 11. CRUD Data Barang/Item
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-BRG-001 | Test Tambah Data | 1. Navigasi ke "Data Barang/Item"<br>2. Klik "Tambah Data"<br>3. Isi form data barang<br>4. Klik "Simpan" | Nama Barang: "Pakan Ayam Petelur", Kategori: "Pakan", Stok: "100" | Sistem menyimpan data ke database, menampilkan pesan "Data berhasil ditambahkan", dan data baru muncul di tabel. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-BRG-002 | Test Ubah Data | 1. Klik tombol "Ubah" pada data barang<br>2. Ubah data di dalam form<br>3. Klik "Simpan" | Stok: "150" | Sistem memperbarui data, menampilkan pesan "Data berhasil diubah", dan data yang diperbarui tampil di tabel. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-BRG-003 | Test Hapus Data | 1. Klik tombol "Hapus" pada data barang<br>2. Konfirmasi penghapusan (Yes/Ok) | - | Sistem menghapus data barang, menampilkan pesan "Data berhasil dihapus", dan data hilang dari tabel. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-BRG-004 | Test Cari Data | 1. Pada halaman "Data Barang/Item", masukkan kata kunci di kolom pencarian<br>2. Klik "Cari" | Kata kunci: "Pakan Ayam" | Sistem menampilkan tabel yang hanya berisi barang dengan nama sesuai kata kunci. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 12. CRUD Data Supplier
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-SPL-001 | Test Tambah Data | 1. Navigasi ke "Data Supplier"<br>2. Klik "Tambah Data"<br>3. Isi form<br>4. Klik "Simpan" | Nama: "PT. Sumber Pakan", Kontak: "0812345678" | Sistem menyimpan data supplier baru dan memunculkannya pada tabel daftar supplier. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-SPL-002 | Test Ubah Data | 1. Klik tombol "Ubah" pada data supplier<br>2. Ubah data kontak<br>3. Klik "Simpan" | Kontak: "0898765432" | Sistem memperbarui detail kontak supplier dan menyimpannya. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-SPL-003 | Test Hapus Data | 1. Klik tombol "Hapus" pada data supplier<br>2. Konfirmasi penghapusan | - | Sistem menghapus data supplier dan menghilangkannya dari tampilan tabel. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-SPL-004 | Test Cari Data | 1. Masukkan nama supplier di kolom pencarian<br>2. Klik "Cari" | Kata kunci: "Sumber" | Sistem menampilkan hasil pencarian berupa data supplier yang dicari. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 13. CRUD Data Pegawai
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PGW-001 | Test Tambah Data | 1. Navigasi ke "Data Pegawai"<br>2. Klik "Tambah Data"<br>3. Isi biodata pegawai<br>4. Klik "Simpan" | Nama: "Budi", Jabatan: "Anak Kandang" | Sistem menyimpan dan menampilkan data pegawai yang baru didaftarkan. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PGW-002 | Test Ubah Data | 1. Klik tombol "Ubah"<br>2. Ubah jabatan pegawai<br>3. Klik "Simpan" | Jabatan: "Supervisor" | Sistem memperbarui jabatan pegawai dan menampilkannya di tabel pegawai. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PGW-003 | Test Hapus Data | 1. Klik tombol "Hapus"<br>2. Lakukan konfirmasi hapus | - | Sistem berhasil menghapus pegawai dan data tersebut tidak lagi tersedia di sistem. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PGW-004 | Test Cari Data | 1. Masukkan nama pegawai di kolom pencarian<br>2. Klik "Cari" | Kata kunci: "Budi" | Sistem memfilter daftar pegawai dan menampilkan data yang sesuai dengan kata kunci "Budi". | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 14. CRUD Data Pelanggan
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PLG-001 | Test Tambah Data | 1. Navigasi ke "Data Pelanggan"<br>2. Klik "Tambah Data"<br>3. Isi form pelanggan<br>4. Klik "Simpan" | Nama: "Toko Makmur", Alamat: "Jl. Merdeka" | Sistem berhasil menyimpan data pelanggan dan menampilkannya di daftar pelanggan. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PLG-002 | Test Ubah Data | 1. Klik "Ubah"<br>2. Update alamat pelanggan<br>3. Klik "Simpan" | Alamat: "Jl. Sudirman" | Sistem memperbarui alamat pelanggan dan menyimpannya. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PLG-003 | Test Hapus Data | 1. Klik "Hapus"<br>2. Konfirmasi penghapusan | - | Sistem menghapus data pelanggan dari database. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PLG-004 | Test Cari Data | 1. Masukkan nama pelanggan di kolom pencarian<br>2. Tekan Enter | Kata kunci: "Makmur" | Sistem menampilkan data pelanggan "Toko Makmur". | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 15. CRUD Data Rekening Kas/Bank
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-RKB-001 | Test Tambah Data | 1. Navigasi ke "Data Rekening Kas/Bank"<br>2. Klik "Tambah Data"<br>3. Isi form<br>4. Klik "Simpan" | Nama Bank: "BCA", No Rek: "1234567" | Sistem menyimpan data rekening/bank baru dan menampilkannya pada tabel. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-RKB-002 | Test Ubah Data | 1. Klik tombol "Ubah"<br>2. Ubah nomor rekening<br>3. Klik "Simpan" | No Rek: "7654321" | Sistem berhasil memperbarui informasi nomor rekening. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-RKB-003 | Test Hapus Data | 1. Klik tombol "Hapus"<br>2. Konfirmasi hapus | - | Sistem menghapus data rekening kas/bank dari daftar. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-RKB-004 | Test Cari Data | 1. Ketik nama bank di kolom pencarian<br>2. Klik "Cari" | Kata kunci: "BCA" | Sistem menampilkan tabel berisi data rekening kas/bank BCA yang dicari. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 16. CRUD Data Kategori Biaya Operasional
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-KBO-001 | Test Tambah Data | 1. Navigasi ke "Kategori Biaya"<br>2. Klik "Tambah Data"<br>3. Isi form kategori<br>4. Klik "Simpan" | Kategori: "Biaya Listrik" | Sistem menyimpan kategori biaya baru di database. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KBO-002 | Test Ubah Data | 1. Klik tombol "Ubah"<br>2. Ubah nama kategori<br>3. Klik "Simpan" | Kategori: "Biaya Listrik dan Air" | Sistem berhasil memperbarui nama kategori operasional. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KBO-003 | Test Hapus Data | 1. Klik tombol "Hapus"<br>2. Lakukan konfirmasi hapus | - | Sistem menghapus data kategori biaya operasional secara permanen. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-KBO-004 | Test Cari Data | 1. Masukkan kata kunci pada pencarian<br>2. Tekan Enter | Kata kunci: "Listrik" | Sistem menampilkan hasil pencarian untuk kategori biaya tersebut. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 17. Pengaturan Profil dan Sistem
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-PPS-001 | Menampilkan pengaturan | 1. Navigasi ke menu "Pengaturan Profil dan Sistem" | - | Sistem menampilkan form pengaturan profil pengguna saat ini dan konfigurasi sistem (seperti nama peternakan, logo). | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-PPS-002 | Ubah profil / pengaturan | 1. Ubah detail profil atau nama sistem<br>2. Klik "Simpan Perubahan" | Nama Peternakan: "Trifacore Farm" | Sistem berhasil menyimpan perubahan dan tampilan antarmuka memperbarui informasi sesuai dengan data yang baru. | [Lampiran Screenshot Hasil Pengujian] | Pass |

### 18. Riwayat Aktivitas Sistem
| ID | Test Scenario | Test Steps | Test Data | Expected Result | Actual Result | Pass/Fail |
|----|---------------|------------|-----------|-----------------|---------------|-----------|
| TC-RAS-001 | Menampilkan log aktivitas | 1. Navigasi ke menu "Riwayat Aktivitas Sistem" | - | Sistem menampilkan tabel log aktivitas (audit trail) dari seluruh pengguna, mencakup waktu, user, dan aksi. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-RAS-002 | Pencarian/filter log | 1. Masukkan rentang tanggal atau username<br>2. Klik "Cari" | User: "admin" | Sistem memfilter log aktivitas berdasarkan user atau waktu yang dipilih. | [Lampiran Screenshot Hasil Pengujian] | Pass |
| TC-RAS-003 | Detail aktivitas | 1. Klik "Detail" pada baris aktivitas tertentu | - | Sistem memunculkan detail perubahan data (sebelum dan sesudah) jika ada aktivitas perubahan data. | [Lampiran Screenshot Hasil Pengujian] | Pass |
