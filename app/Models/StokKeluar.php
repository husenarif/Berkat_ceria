<?php
// app/Models/Produk.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    protected $table = 'stok_keluar';

    protected $fillable = [
        'produk_id', 'kategori_id', 'supplier_id',
        'deskripsi', 'tanggal_masuk', 'tanggal_kadaluarsa',
        'satuan', 'jumlah'
    ];

    public function produk() {
        return $this->belongsTo(Produk::class);
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}

