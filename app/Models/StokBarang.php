<?php
// app/Models/Produk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    protected $table = 'stok_barang';

    protected $fillable = [
    'nama_produk',
    'kategori_id',
    'supplier_id',
    'satuan',
    'deskripsi',
    'jumlah_produk',
    'tanggal_masuk',
    'tanggal_kadaluarsa',
];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
