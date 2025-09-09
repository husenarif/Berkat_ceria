<?php

namespace Database\Seeders;

use App\Models\produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run():void
    {
        Produk::create([
            'kategori_id' => '1',
            'supplier_id' => '1',
            'nama_produk' => 'shake',
            'code_produk' => '2',
            'keterangan' => 'pengganti makanan',
            'kadaluarsa' => '23-10-2026',
            'harga_beli' => '30000',
            'harga_jual' => '60000',
            'tanggal_transaksi '=> '10-1-2025',
            'foto' => '',
            'jumlah_produk' => '30',
        ]);
    }
}
