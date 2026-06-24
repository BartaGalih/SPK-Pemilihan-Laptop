# Buku Panduan Pengguna (User Manual)
## SPK Rekomendasi Laptop – Metode MFEP

> **Kelompok 5** – Ahmad Bayu Nurdiansyah (241110047) & Barta Galih Rizki Dinata (241110049)
> Program Studi Informatika – FTI Universitas Mercu Buana Yogyakarta – T.A. 2025/2026

---

## 1. Pengenalan

**SPK Rekomendasi Laptop** adalah aplikasi web Sistem Penunjang Keputusan untuk membantu mahasiswa memilih laptop terbaik. Aplikasi menilai setiap laptop berdasarkan beberapa **kriteria** yang masing-masing punya **bobot kepentingan**, lalu menghitung peringkat menggunakan metode **MFEP (Multi Factor Evaluation Process)**.

Kriteria pada aplikasi ini bersifat **dinamis** — dapat ditambah, diubah, maupun dihapus sesuai kebutuhan. Form data laptop akan **otomatis menyesuaikan** dengan kriteria yang aktif.

Hasil akhirnya adalah daftar laptop yang **diurutkan dari yang paling direkomendasikan** ke yang paling kurang direkomendasikan, dan dapat **disimpan sebagai arsip**.

### Siapa yang menggunakan?
Aplikasi ini ditujukan untuk satu jenis pengguna (admin/operator) — tidak ada login. Siapa pun yang membuka alamat aplikasi dapat mengelola data dan menjalankan perhitungan.

---

## 2. Memulai Aplikasi

1. Pastikan aplikasi sudah terpasang dan berjalan (lihat **README.md** untuk instalasi).
2. Jalankan server lokal:
   ```bash
   php artisan serve
   ```
3. Buka browser dan kunjungi: **http://127.0.0.1:8000**
4. Halaman akan langsung mengarah ke menu **Data Laptop**.

### Navigasi
Di sisi kiri terdapat **sidebar** berisi 4 menu utama:

| Menu | Fungsi |
|------|--------|
| **Data Laptop** | Mengelola daftar laptop (tambah/lihat/ubah/hapus) |
| **Kriteria & Bobot** | Menambah/mengubah/menghapus kriteria & mengatur bobotnya |
| **Hasil Rekomendasi** | Menjalankan perhitungan & melihat peringkat |
| **Arsip Hasil** | Menyimpan & melihat kembali hasil perhitungan yang sudah disimpan |

Pesan notifikasi (berhasil/peringatan) akan muncul di bagian atas layar setiap kali Anda menyimpan data.

> 💡 **Urutan disarankan untuk pengguna baru:** atur dulu **Kriteria & Bobot** (Bab 4) → isi **Data Laptop** (Bab 3) → jalankan **Hasil Rekomendasi** (Bab 5).

---

## 3. Menu Data Laptop

Menu ini adalah tempat menyimpan semua laptop yang ingin dibandingkan. Minimal harus ada **2 laptop** agar perhitungan bermakna.

### 3.1 Melihat Daftar Laptop
Klik **Data Laptop** pada sidebar. Daftar ditampilkan per 10 baris (dengan navigasi halaman / *pagination* bila datanya banyak). **Kolom pada tabel mengikuti kriteria yang aktif** — jika Anda menambah/menghapus kriteria, kolomnya ikut berubah.

### 3.2 Menambah Laptop Baru
1. Klik tombol **Tambah Laptop**.
2. Isi **Nama Laptop**, lalu isi **satu kotak nilai untuk setiap kriteria** yang ditampilkan (form ini dinamis — jumlah kotak = jumlah kriteria aktif).

   | Field | Keterangan | Aturan Pengisian |
   |-------|-----------|------------------|
   | **Nama Laptop** | Nama/model laptop | Teks, wajib, maks. 200 karakter. Contoh: *ASUS Vivobook S14* |
   | **Nilai per Kriteria** | Satu input untuk tiap kriteria aktif (mis. Harga, RAM, CPU Score, dst.) | Wajib, berupa angka ≥ 0. Setiap input diberi label nama kriteria, satuan, dan tanda **Benefit/Cost** |

3. Klik **Simpan**.
4. Jika ada isian yang salah/kosong, kolom akan ditandai dengan pesan perbaikan. Perbaiki lalu simpan ulang.
5. Setelah berhasil, muncul notifikasi *"Data laptop berhasil ditambahkan."*

> Catatan: Nilai laptop disimpan per kriteria. Bila kemudian Anda menambah kriteria baru, laptop lama belum memiliki nilai untuk kriteria itu (dianggap 0) sampai Anda mengeditnya.

### 3.3 Melihat Detail Laptop
Klik tombol **Detail** (ikon mata) pada baris laptop untuk melihat seluruh spesifikasi (sesuai kriteria aktif) beserta hasil MFEP laptop tersebut bila sudah dihitung.

### 3.4 Mengubah Data Laptop
1. Klik tombol **Edit** pada baris laptop.
2. Ubah nama dan/atau nilai tiap kriteria.
3. Klik **Simpan**. Muncul notifikasi *"Data laptop berhasil diperbarui."*

### 3.5 Menghapus Laptop
1. Klik tombol **Hapus** pada baris laptop.
2. Konfirmasi penghapusan.
3. Data terhapus dan muncul notifikasi *"Data laptop berhasil dihapus."*

> ⚠️ **Penting:** Setiap kali Anda menambah, mengubah, atau menghapus laptop, hasil rekomendasi menjadi **tidak lagi terbaru**. Jalankan ulang perhitungan (lihat Bab 5) agar peringkat sesuai data terakhir.

---

## 4. Menu Kriteria & Bobot

Kriteria adalah faktor penilaian. Pada aplikasi ini kriteria **dapat ditambah, diubah, dan dihapus**. Aplikasi menyediakan 6 kriteria awal (Harga, RAM, CPU Score, Bobot, Storage, Baterai) yang dapat Anda sesuaikan.

### 4.1 Memahami Tipe Kriteria
- **Benefit** = nilai lebih besar lebih baik (mis. RAM, CPU Score, Storage, Baterai).
- **Cost** = nilai lebih kecil lebih baik (mis. Harga, Bobot perangkat).

### 4.2 Indikator Total Bobot
Di bagian atas halaman terdapat indikator total bobot:
- **Hijau** bila total = 1,00 (siap dihitung).
- **Kuning** bila total ≠ 1,00, disertai info **sisa** bobot yang masih perlu dialokasikan.

**Aturan utama: jumlah seluruh bobot kriteria harus = 1,00 (100%).**

### 4.3 Menambah Kriteria Baru
1. Klik tombol **Tambah Kriteria**.
2. Isi **Nama**, **Satuan** (opsional), **Tipe** (Benefit/Cost), dan **Bobot (NBF)**.
3. Kotak bobot dibatasi **maksimal sisa bobot yang tersedia**. Persentase dan peringatan tampil langsung saat mengetik.
   - Bila total bobot sudah 1,00, akan muncul keterangan untuk **mengurangi bobot kriteria lain** terlebih dahulu.
4. Klik **Simpan**.

### 4.4 Mengubah Kriteria
1. Klik tombol **Edit** (ikon pensil) pada kriteria.
2. Ubah nama, satuan, tipe, atau bobot. Bobot tetap dibatasi sisa yang tersedia.
3. Klik **Simpan Perubahan**.

### 4.5 Menghapus Kriteria
1. Klik tombol **Hapus** (ikon tempat sampah) pada kriteria.
2. Konfirmasi penghapusan. Kriteria tidak lagi dipakai dalam perhitungan.
3. Sesuaikan kembali bobot kriteria lain agar total = 1,00.

> Penghapusan kriteria bersifat aman terhadap arsip: arsip hasil yang sudah disimpan **tidak terpengaruh** oleh perubahan/penghapusan kriteria.

---

## 5. Menu Hasil Rekomendasi

Di sinilah perhitungan MFEP dijalankan dan peringkat laptop ditampilkan.

### 5.1 Menjalankan Perhitungan
1. Klik menu **Hasil Rekomendasi**.
2. Klik tombol **Hitung / Perbarui MFEP**.
3. Aplikasi memproses seluruh laptop terhadap seluruh kriteria aktif, lalu menampilkan hasil dengan notifikasi *"Perhitungan MFEP berhasil dijalankan!"*

> ⚠️ Tombol **Hitung dinonaktifkan** bila total bobot kriteria ≠ 1,00. Akan muncul peringatan beserta tautan ke menu Kriteria & Bobot untuk memperbaikinya. Jalankan ulang perhitungan setiap kali ada perubahan data laptop atau bobot.

### 5.2 Membaca Hasil
Halaman hasil menampilkan:

- **Kartu Top 3** — tiga laptop terbaik dengan badge peringkat (🥇 emas, 🥈 perak, 🥉 perunggu).
- **Matriks Evaluasi Lengkap** — seluruh laptop diurutkan dari skor tertinggi (Rank 1), dengan kolom **NEF & NBE per kriteria** (mengikuti kriteria aktif) dan total **TBE**.
- **Tabel Bobot Kriteria (NBF)** yang digunakan saat perhitungan.

**Laptop dengan TBE (skor akhir) tertinggi = peringkat 1 = rekomendasi terbaik.**

### 5.3 Menyimpan Hasil ke Arsip
1. Klik tombol **Simpan ke Arsip** (muncul setelah ada hasil).
2. Isi **Judul Arsip** (wajib) dan **Catatan** (opsional) pada dialog yang muncul.
3. Klik **Simpan Arsip**. Anda akan diarahkan ke halaman detail arsip yang baru dibuat.

> Yang disimpan adalah **salinan** (laptop, kriteria, bobot, dan hasil hitung saat itu). Perubahan data setelahnya **tidak** mengubah isi arsip.

### 5.4 Memahami Istilah Perhitungan

| Istilah | Arti |
|---------|------|
| **NBF** | *Bobot* kriteria (yang Anda atur di menu Kriteria), total = 1,00 |
| **NEF** | Nilai evaluasi tiap laptop per kriteria, dinormalisasi ke skala **1–5** (5 = terbaik) |
| **NBE** | NBF × NEF — kontribusi skor satu kriteria untuk satu laptop |
| **TBE** | Jumlah seluruh NBE — **skor akhir** yang menentukan peringkat |

Singkatnya: **TBE = Σ (bobot kriteria × nilai laptop pada kriteria itu)**. Semakin tinggi TBE, semakin direkomendasikan.

---

## 6. Menu Arsip Hasil

Menu **Arsip Hasil** menyimpan kumpulan hasil perhitungan yang telah Anda simpan. Setiap arsip adalah salinan independen, sehingga tetap utuh meski data master berubah.

### 6.1 Melihat Daftar Arsip
Klik **Arsip Hasil** pada sidebar. Daftar menampilkan judul, jumlah laptop, jumlah kriteria, dan waktu penyimpanan.

### 6.2 Melihat Detail Arsip
Klik tombol **Lihat** (ikon mata). Halaman detail menampilkan **salinan** kriteria + bobot dan matriks hasil lengkap (Nilai, NEF, NBE, dan TBE per laptop) pada saat arsip dibuat.

### 6.3 Menghapus Arsip
Klik tombol **Hapus** (ikon tempat sampah) lalu konfirmasi. Arsip terhapus permanen.

---

## 7. Alur Kerja Lengkap (Ringkasan)

Untuk mendapatkan rekomendasi, ikuti urutan ini:

```
1. Kriteria & Bobot → Atur kriteria (tambah/ubah/hapus) & pastikan total bobot = 1,00
2. Data Laptop      → Masukkan minimal 2 laptop beserta nilai tiap kriteria
3. Hasil Rekomendasi → Klik "Hitung / Perbarui MFEP", baca peringkat & Top 3
4. (opsional) Simpan ke Arsip → simpan hasil untuk dokumentasi/perbandingan
```

Setiap kali data laptop, kriteria, atau bobot berubah → **ulangi langkah 3**.

---

## 8. Pemecahan Masalah (Troubleshooting)

| Masalah | Penyebab & Solusi |
|---------|-------------------|
| Tombol "Hitung / Perbarui MFEP" tidak bisa diklik (nonaktif) | Total bobot kriteria ≠ 1,00. Buka **Kriteria & Bobot** dan sesuaikan hingga total = 1,00. |
| Saat menambah/mengubah kriteria, bobot ditolak | Bobot melebihi sisa yang tersedia. Kurangi bobot kriteria lain terlebih dahulu. |
| Kolom isian laptop bertanda merah saat menyimpan | Ada nilai kosong atau bukan angka. Baca pesan di bawah kolom dan perbaiki. |
| Hasil rekomendasi terasa tidak sesuai data terbaru | Anda belum menjalankan ulang perhitungan. Buka **Hasil Rekomendasi** → klik **Hitung / Perbarui MFEP**. |
| Halaman hasil kosong | Belum pernah dihitung atau belum ada data laptop. Tambahkan laptop lalu jalankan perhitungan. |
| Menambah kriteria baru tapi laptop lama nilainya 0 | Wajar — laptop lama belum punya nilai untuk kriteria baru. Edit laptop untuk mengisinya. |
| Tidak bisa membuka aplikasi | Pastikan `php artisan serve` masih berjalan dan alamat http://127.0.0.1:8000 benar. |

---

## 9. Catatan

- Aplikasi tidak memiliki sistem login; semua orang yang mengakses memiliki hak yang sama.
- Data tersimpan di database (MySQL/MariaDB). Backup database secara berkala bila datanya penting.
- Untuk kriteria berbasis skor (mis. CPU Score), gunakan sumber yang konsisten (mis. PassMark / cpubenchmark.net) agar perbandingan adil.
- Arsip hasil bersifat permanen sampai Anda menghapusnya, dan tidak terpengaruh perubahan data master.

---

*Dokumen ini adalah panduan penggunaan. Untuk instalasi dan detail teknis algoritma, lihat **README.md**.*
