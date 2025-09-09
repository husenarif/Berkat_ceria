<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStokMasuk extends Model
{
    use HasFactory;

    protected $table = 'detail_stok_masuk';

    protected $guarded = ['id'];

    // Relasi ke StokMasuk
    public function stokMasuk()
    {
        return $this->belongsTo(StokMasuk::class);
    }

    // Relasi ke Produk
    public function produk() // <--- PASTIKAN RELASI INI ADA
    {
        return $this->belongsTo(Produk::class);
    }
}
