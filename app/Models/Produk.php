<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- INILAH PERBAIKANNYA

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'kategori_id',
        'satuan_id',
        'harga',
        'modal_harga',
        'stok',
        'gambar',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(SatuanProduk::class, 'satuan_id');
    }

    // Fungsi ini akan menjadi satu-satunya cara kita membuat kode produk
    public static function generateKodeProduk(): string
    {
        // Sekarang PHP tahu di mana harus mencari 'Str'
        return 'PRD' . now()->format('YmdHis') . Str::random(4);
    }
}
