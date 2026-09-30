<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'kode_produk' => 'PRD-001',
            'nama_produk' => 'Laptop ASUS ROG',
            'satuan' => 'unit',
            'stok' => 10,
            'harga_satuan' => 15000000,
        ]);

        Product::create([
            'kode_produk' => 'PRD-002',
            'nama_produk' => 'Mouse Wireless Logitech',
            'satuan' => 'pcs',
            'stok' => 25,
            'harga_satuan' => 250000,
        ]);

        Product::create([
            'kode_produk' => 'PRD-003',
            'nama_produk' => 'Keyboard Mechanical',
            'satuan' => 'unit',
            'stok' => 15,
            'harga_satuan' => 750000,
        ]);
    }
}
