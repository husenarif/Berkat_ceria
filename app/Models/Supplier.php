<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    // Masukkan semua kolom yang bisa diisi secara massal
    protected $fillable = ['nama_supplier', 'alamat','telepon'];

     public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id');
    }
}

