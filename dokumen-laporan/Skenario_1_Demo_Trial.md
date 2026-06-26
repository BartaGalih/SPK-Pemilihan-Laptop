# Skenario 1 — Demo / Trial Aplikasi SPK Rekomendasi Laptop

> Skenario ini digunakan untuk **uji coba/demonstrasi** alur kerja aplikasi dari awal sampai muncul hasil rekomendasi. Data laptop yang dipakai adalah **5 unit data dummy** (model nyata, spesifikasi disederhanakan untuk trial) dan **berbeda dari data bawaan (seeder)** aplikasi, sehingga cocok dipakai saat demo dengan database kosong.

> **Catatan rumus:** Nilai NEF dihitung dengan rumus `RANK.EQ` (sesuai spreadsheet):
> `NEF = ROUND(1 + 4 × (rank − 1) / (N − 1), 0)` → bilangan bulat 1–5, dengan
> `rank` = 1 + jumlah nilai yang lebih baik (benefit: lebih besar; cost: lebih kecil).
> Nilai yang seri mendapat NEF yang sama.

---

## 1. Tujuan Skenario

Membuktikan bahwa aplikasi dapat:
1. Menyimpan data alternatif laptop.
2. Menggunakan bobot kriteria (total = 1,00).
3. Menjalankan perhitungan **MFEP** dan menampilkan **peringkat rekomendasi** yang benar.

---

## 2. Prasyarat

- Aplikasi sudah berjalan (`php artisan serve`) dan dapat dibuka di **http://127.0.0.1:8000**.
- Database dalam keadaan **kosong dari data laptop** (hapus dahulu data lama/seeder agar hasil demo bersih).
- Bobot kriteria memakai nilai bawaan (lihat Tabel di bagian 4).

---

## 3. Data Uji — 5 Laptop (Dummy, di luar seeder)

| No | Nama Laptop | Harga (Rp) | RAM | CPU Score | Bobot | Storage | Baterai |
|----|-------------|-----------:|----:|----------:|------:|--------:|--------:|
| 1 | Acer Aspire 5 A514-56 | 9.499.000 | 16 GB | 17.000 | 1,45 kg | 512 GB | 8 jam |
| 2 | HP Pavilion 14-dv2 | 10.799.000 | 8 GB | 13.500 | 1,41 kg | 512 GB | 8 jam |
| 3 | Dell Inspiron 14 5440 | 11.999.000 | 16 GB | 16.800 | 1,59 kg | 512 GB | 9 jam |
| 4 | Lenovo LOQ 15IRX9 | 16.999.000 | 16 GB | 26.000 | 2,40 kg | 512 GB | 6 jam |
| 5 | ASUS Zenbook 14 OLED UX3405MA | 18.499.000 | 16 GB | 23.000 | 1,20 kg | 1024 GB | 13 jam |

---

## 4. Bobot Kriteria yang Digunakan

| Kode | Kriteria | Tipe | Bobot (NBF) |
|------|----------|------|------------:|
| C1 | Harga | Cost | 0,30 |
| C2 | RAM | Benefit | 0,20 |
| C3 | CPU Score | Benefit | 0,25 |
| C4 | Bobot | Cost | 0,10 |
| C5 | Storage | Benefit | 0,05 |
| C6 | Baterai | Benefit | 0,10 |
| | **Total** | | **1,00** ✓ |

---

## 5. Langkah-Langkah Demo (Skenario Uji)

| Langkah | Aksi yang Dilakukan | Input | Hasil yang Diharapkan |
|--------:|---------------------|-------|------------------------|
| 1 | Buka aplikasi di browser | http://127.0.0.1:8000 | Tampil halaman **Data Laptop** |
| 2 | (Opsional) Atur kriteria di **Kriteria & Bobot** | — | Total bobot = 1,00 (indikator hijau) |
| 3 | Klik **Tambah Laptop**, isi data laptop No.1, klik **Simpan** | Data laptop No.1 | Notifikasi "Data laptop berhasil ditambahkan" |
| 4 | Ulangi untuk laptop No.2 s/d No.5 | Data laptop No.2–5 | Total 5 laptop tampil di daftar Data Laptop |
| 5 | Buka menu **Hasil Rekomendasi** | – | Tombol **Hitung** aktif (karena total bobot = 1,00) |
| 6 | Klik tombol **Hitung / Perbarui MFEP** | – | Notifikasi "Perhitungan MFEP berhasil dijalankan!" dan muncul hasil peringkat |
| 7 | Amati kartu **Top 3** dan **Matriks Evaluasi** | – | Peringkat sesuai Tabel Hasil pada bagian 6 |
| 8 | (Opsional) Klik **Simpan ke Arsip**, isi judul | judul arsip | Hasil tersimpan & tampil di menu **Arsip Hasil** |
| 9 | (Opsional) Uji validasi: tambah laptop dengan Harga `500000` | price = 500000 | Ditolak — nilai harus angka yang valid |

---

## 6. Hasil yang Diharapkan (Expected Output)

Setelah menekan **Hitung / Perbarui MFEP**, aplikasi menampilkan peringkat berikut (diurutkan dari TBE tertinggi):

| Rank | Nama Laptop | TBE | Keterangan |
|:----:|-------------|----:|------------|
| 🥇 1 | **Acer Aspire 5 A514-56** | **3,2000** | Rekomendasi terbaik |
| 🥈 2 | ASUS Zenbook 14 OLED UX3405MA | 2,9500 | Runner-up |
| 🥉 3 | Lenovo LOQ 15IRX9 | 2,5000 | Pilihan ke-3 |
| 4 | Dell Inspiron 14 5440 | 2,4500 | – |
| 5 | HP Pavilion 14-dv2 | 2,3000 | – |

### Matriks Evaluasi Lengkap (NEF / NBE per kriteria)

**Peringkat 1 — Acer Aspire 5 A514-56 (TBE = 3,2000)**

| | C1 Harga | C2 RAM | C3 CPU | C4 Bobot | C5 Storage | C6 Baterai |
|---|---:|---:|---:|---:|---:|---:|
| NEF | 5 | 2 | 3 | 3 | 1 | 2 |
| NBE | 1,5000 | 0,4000 | 0,7500 | 0,3000 | 0,0500 | 0,2000 |

**Peringkat 2 — ASUS Zenbook 14 OLED UX3405MA (TBE = 2,9500)**

| | C1 Harga | C2 RAM | C3 CPU | C4 Bobot | C5 Storage | C6 Baterai |
|---|---:|---:|---:|---:|---:|---:|
| NEF | 1 | 2 | 4 | 5 | 5 | 5 |
| NBE | 0,3000 | 0,4000 | 1,0000 | 0,5000 | 0,2500 | 0,5000 |

**Peringkat 3 — Lenovo LOQ 15IRX9 (TBE = 2,5000)**

| | C1 Harga | C2 RAM | C3 CPU | C4 Bobot | C5 Storage | C6 Baterai |
|---|---:|---:|---:|---:|---:|---:|
| NEF | 2 | 2 | 5 | 1 | 1 | 1 |
| NBE | 0,6000 | 0,4000 | 1,2500 | 0,1000 | 0,0500 | 0,1000 |

**Peringkat 4 — Dell Inspiron 14 5440 (TBE = 2,4500)**

| | C1 Harga | C2 RAM | C3 CPU | C4 Bobot | C5 Storage | C6 Baterai |
|---|---:|---:|---:|---:|---:|---:|
| NEF | 3 | 2 | 2 | 2 | 1 | 4 |
| NBE | 0,9000 | 0,4000 | 0,5000 | 0,2000 | 0,0500 | 0,4000 |

**Peringkat 5 — HP Pavilion 14-dv2 (TBE = 2,3000)**

| | C1 Harga | C2 RAM | C3 CPU | C4 Bobot | C5 Storage | C6 Baterai |
|---|---:|---:|---:|---:|---:|---:|
| NEF | 4 | 1 | 1 | 4 | 1 | 2 |
| NBE | 1,2000 | 0,2000 | 0,2500 | 0,4000 | 0,0500 | 0,2000 |

> Catatan: Empat laptop ber-RAM 16 GB seri pada kriteria RAM, sehingga NEF C2-nya sama (= 2); HP Pavilion (8 GB) mendapat NEF 1. Begitu pula Storage: hanya Zenbook (1024 GB) yang unggul (NEF 5), empat laptop 512 GB seri di NEF 1.

---

## 7. Kesimpulan Demo

Hasil perhitungan menunjukkan **Acer Aspire 5 A514-56** sebagai **rekomendasi terbaik** dengan TBE tertinggi (3,2000). Meskipun performa CPU dan kelengkapan fiturnya tidak paling tinggi, laptop ini unggul pada kriteria berbobot besar yaitu **harga termurah (C1, bobot 0,30 → NEF 5)** sehingga skor totalnya mengungguli laptop lain. Aplikasi dinyatakan **berjalan sesuai harapan** apabila urutan peringkat yang muncul sama dengan Tabel Hasil pada bagian 6.

---

## 8. Status Pengujian

| Aspek yang Diuji | Hasil |
|------------------|:-----:|
| Input & simpan data laptop | ☐ Berhasil / ☐ Gagal |
| Validasi form input laptop | ☐ Berhasil / ☐ Gagal |
| Total bobot kriteria = 1,00 (tombol Hitung aktif) | ☐ Berhasil / ☐ Gagal |
| Perhitungan MFEP & peringkat | ☐ Berhasil / ☐ Gagal |
| Tampilan Top 3 & matriks | ☐ Berhasil / ☐ Gagal |
| Simpan & lihat Arsip Hasil | ☐ Berhasil / ☐ Gagal |

*(Centang kolom hasil saat demo berlangsung.)*
