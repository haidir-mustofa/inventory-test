<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'jenis', 'jumlah', 'tanggal', 'keterangan'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
