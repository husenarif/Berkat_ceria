<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\StokKeluar;
use App\Models\supplier;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;

class StokKeluarController extends Controller
{
    public function Index()
    {
        $stok_keluar = StokKeluar::all();
        return view('stok_keluar.index', compact('stok_keluar'));
    }

    public function create()
    {
        $kategori = Kategori::all(); // ambil semua kategori
        $supplier = supplier::all(); // ambil semua supplier
        return view('stok_keluar.create', compact('kategori','supplier'));// kirim ke view
    }


    
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_produk' => 'required',
            'kategori_id' => 'required|exists:kategori,id',
            'supplier_id' => 'required|exists:supplier,id', // validasi supplier id
            'deskripsi' => 'required',
            'tanggal_masuk' => 'required',
            'tanggal_kadaluarsa' => 'required',
            'satuan' => 'required',
            'jumlah' => 'required',

        ], [
            'nama_produk.required' => 'Nama Produk Harus di isi',
            'kategori_id.required' => 'Kategori tidak valid',
            'supplier_id.required' => 'Supplier Harus di isi',
            'deskripsi' => 'Deskripsi Harus disi',
            'tanggal_masuk' => 'Tanggal Masuk Harus disi',
            'tanggal_kadaluarsa' => 'Tanggal Kadaluarsa Harus disi',
            'satuan.required' => 'Satuan Harus di isi',
            'jumlah.required' => 'Jumlah Harus di isi',

        ]);

        StokKeluar::create([
            'nama_produk' => $request->nama_produk,
            'kategori_id' => $request->kategori_id, // 'kategori_id'
            'supplier_id' => $request->supplier_id,
            'deskripsi' => $request->deskripsi,
            'tanggal_masuk' => $request->tanggal_masuk,
            'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
            'satuan' => $request->satuan,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('stok_keluar.index');
    }
}
