# Simple Inventory Management System (Laravel 10)

Aplikasi inventori sederhana untuk mengelola produk dan transaksi stok masuk/keluar, dilengkapi dengan validasi stok anti-minus, REST API, serta fitur histori transaksi per produk.

## Requirement Teknis

- PHP >= 8.2
- Composer
- MySQL / MariaDB

## Step-by-Step Instalasi & Menjalankan Project

1. Clone atau salin repository ini ke komputer lokal Anda.
2. Buka terminal pada folder project, lalu install dependencies:
   composer install
    1. Salin file environment: cp .env.example .env
    2. Generate application key: php artisan key:generate
    3. Sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, DB_PASSWORD) di dalam file .env.
    4. Jalankan migrasi database beserta seeder (data dummy produk): php artisan migrate --seed
    5. Jalankan server lokal: php artisan serve
    6. Akses aplikasi di browser melalui: http://127.0.0.1:8000

## Endpoint REST API

GET /api/produk — Menampilkan list seluruh produk (Mendukung parameter pencarian ?search=keyword)
POST /api/produk — Menambah produk baru secara JSON
POST /api/transaksi — Melakukan input transaksi stok masuk/keluar (Dilengkapi validasi ketat agar stok tidak menjadi minus)

## Fitur Utama

CRUD Produk: Manajemen data produk (kode unik, nama, satuan, stok, harga satuan).
Transaksi Stok: Pencatatan barang masuk dan keluar gudang secara real-time.
Validasi Anti-Minus: Sistem secara otomatis menolak transaksi "Keluar" jika jumlah melebihi stok yang tersedia.
Pencarian Produk: Fitur pencarian menggunakan where like berdasarkan kode atau nama produk.
Histori Transaksi: Memantau riwayat keluar-masuk barang secara spesifik untuk setiap produk.
