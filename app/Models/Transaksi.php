<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $guarded = ['id'];
    // App\Models\Transaksi.php
    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'tanggal_transaksi',
        'deskripsi',
        'total_harga',
        'bayar',
        'kembalian',
        'profit', // <--- TAMBAHKAN INI

    ];

    // Relasi ke user (kasir/admin)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail transaksi (produk per item)
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}
