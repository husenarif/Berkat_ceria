<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;

    protected $table = 'stok_masuk';

   protected $fillable = [
    'kode_stok_masuk',
    'tanggal_masuk',
    'supplier_id',
    'user_id', // Pastikan ini ada
    'deskripsi',
];


    // Relasi ke User (admin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class); // Sesuaikan jika Anda tidak punya model Supplier
    }

    // Relasi ke DetailStokMasuk
    public function detailStokMasuk()
    {
        return $this->hasMany(DetailStokMasuk::class);
    }
}
