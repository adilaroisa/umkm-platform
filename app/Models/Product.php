<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Mengizinkan kolom-kolom ini diisi secara massal (Mass Assignment)
    protected $fillable = [
        'nama_produk', 
        'kategori', 
        'harga', 
        'stok', 
        'deskripsi'
    ];
}