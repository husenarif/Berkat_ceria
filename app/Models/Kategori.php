<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';

    // Masukkan semua kolom yang bisa diisi secara massal
    protected $fillable = ['nama_kategori'];

     public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}

