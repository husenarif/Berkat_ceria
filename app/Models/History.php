<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- INILAH PERBAIKANNYA

class History extends Model
{
    protected $table = 'history';

    protected $fillable = [
        'aktifitas',
        'nama',
        'detail'
    ];

 public function role(){

        return $this->belongsTo(Role::class,);
    }

    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class, 'detail', 'kode_stok_masuk');
    }

    /**
     * Mencari Transaksi yang cocok berdasarkan kode di kolom 'detail'.
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'detail', 'kode_transaksi');
    }

    /**
     * Mencari Produk yang cocok berdasarkan kode di kolom 'detail'.
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'detail', 'kode_produk');
    }
}


