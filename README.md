# SPK Rekomendasi Laptop – Laravel 8

Sistem Penunjang Keputusan pemilihan laptop untuk mahasiswa menggunakan metode **MFEP (Multi Factor Evaluation Process)**.

> **Kelompok 5** – Ahmad Bayu Nurdiansyah (241110047) & Barta Galih Rizki Dinata (241110049)  
> Program Studi Informatika – FTI Universitas Mercu Buana Yogyakarta – T.A. 2025/2026

---

## Prasyarat

| Tools | Versi |
|-------|-------|
| PHP   | ≥ 8.0 |
| Composer | latest |
| MySQL / MariaDB | ≥ 5.7 |
| Node.js (optional) | ≥ 14 |

---

## Langkah Instalasi

### 1. Clone / copy project

```bash
git clone https://github.com/yourrepo/spk-laptop.git
cd spk-laptop
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Buat file .env

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuaikan koneksi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spk_laptop
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat database

```sql
CREATE DATABASE spk_laptop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan migrasi + seeder

```bash
php artisan migrate --seed
```

Perintah ini akan membuat tabel `criteria`, `laptops`, `mfep_results` sekaligus mengisi data awal 10 laptop dan 6 kriteria.

### 6. Jalankan server lokal

```bash
php artisan serve
```

Buka browser: **http://127.0.0.1:8000**

---

## Struktur Aplikasi

```
app/
├── Http/Controllers/
│   ├── LaptopController.php     ← CRUD data laptop
│   ├── CriteriaController.php   ← Edit bobot kriteria
│   └── MfepController.php       ← Hitung & tampil hasil MFEP
├── Models/
│   ├── Laptop.php
│   ├── Criteria.php
│   └── MfepResult.php
└── Services/
    └── MfepEngine.php           ← Inti algoritma MFEP

database/
├── migrations/
│   ├── ..._create_criteria_table.php
│   ├── ..._create_laptops_table.php
│   └── ..._create_mfep_results_table.php
└── seeders/
    ├── CriteriaSeeder.php
    ├── LaptopSeeder.php
    └── DatabaseSeeder.php

resources/views/
├── layouts/app.blade.php        ← Master layout + sidebar
├── laptops/                     ← index, create, edit, show, _form
├── criteria/                    ← index, edit
└── results/                     ← index (ranking + matriks NEF/NBE)

routes/web.php
```

---

## Kriteria MFEP

| Kode | Kriteria    | Tipe    | Bobot | Keterangan                     |
|------|------------|---------|-------|--------------------------------|
| C1   | Harga      | Cost    | 0.30  | Lebih murah = lebih baik       |
| C2   | RAM        | Benefit | 0.20  | RAM lebih besar = lebih baik   |
| C3   | CPU Score  | Benefit | 0.25  | Skor PassMark lebih tinggi = lebih baik |
| C4   | Bobot      | Cost    | 0.10  | Lebih ringan = lebih baik      |
| C5   | Storage    | Benefit | 0.05  | Kapasitas lebih besar = lebih baik |
| C6   | Baterai    | Benefit | 0.10  | Lebih lama = lebih baik        |

---

## Algoritma MFEP (`MfepEngine`)

1. **Normalisasi → NEF (skala 1–5)**  
   Setiap kriteria diurutkan berdasarkan jenisnya (benefit: DESC, cost: ASC).  
   Rank 1 (terbaik) mendapat NEF = 5, rank terburuk mendapat NEF = 1.  
   Formula: `NEF = 5 - ((rank - 1) / (maxRank - 1)) * 4`

2. **NBE = NBF × NEF**

3. **TBE = Σ NBE** (sum semua kriteria)

4. Laptop dengan **TBE tertinggi = Rekomendasi terbaik**.

---

## Fitur

- ✅ CRUD data laptop (tambah, lihat, edit, hapus) + pagination
- ✅ Manajemen bobot kriteria (edit NBF per kriteria, validasi total = 1.00)
- ✅ Mesin kalkulasi MFEP otomatis
- ✅ Tampilan ranking dengan kartu Top 3 + matriks lengkap NEF/NBE/TBE
- ✅ Sidebar navigasi, Bootstrap 5, responsive
