<?php
// app/Models/Produk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanProduk extends Model
{
    protected $table = 'satuan_produk';

    protected $fillable = [
    'nama_satuan',
];

}
