<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['kode_produk', 'nama_produk', 'satuan', 'stok', 'harga_satuan'];

    public function transactions()
    {
        return $this->hasMany(StockTransaction::class);
    }
}
