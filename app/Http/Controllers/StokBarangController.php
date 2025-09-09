<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\StokBarang;
use App\Models\Supplier;
use Doctrine\DBAL\Schema\Index;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public function Index()
    {
        $stok_barang = StokBarang::all();
        return view('stok_barang.index', compact('stok_barang'));
    }

    public function create()
    {
        $kategori = Kategori::all(); // ambil semua kategori
        $supplier = Supplier::all(); // ambil semua supplier
        return view('stok_barang.create', compact('kategori','supplier'));// kirim ke view
    }


    public function store(Request $request)
{
    $request->validate([
        'nama_produk' => 'required|max:100',
        'kategori_id' => 'required|exists:kategori,id',
        'supplier_id' => 'required|exists:supplier,id',
        'satuan' => 'required|max:50',
        'deskripsi' => 'nullable',
        'jumlah_produk' => 'required|integer|min:1',
        'tanggal_masuk' => 'required|date',
        'tanggal_kadaluarsa' => 'required|date|after:tanggal_masuk',
    ], [
        'nama_produk.required' => 'Nama Produk Harus di isi',
        'kategori_id.required' => 'Kategori harus dipilih',
        'supplier_id.required' => 'Supplier harus dipilih',
        'satuan.required' => 'Satuan Harus di isi',
        'jumlah_produk.required' => 'Jumlah Harus di isi',
        'tanggal_masuk.required' => 'Tanggal Masuk Harus di isi',
        'tanggal_kadaluarsa.required' => 'Tanggal Kadaluarsa Harus di isi',
        'tanggal_kadaluarsa.after' => 'Tanggal Kadaluarsa harus setelah Tanggal Masuk',
    ]);

    StokBarang::create([
        'nama_produk' => $request->nama_produk,
        'kategori_id' => $request->kategori_id,
        'supplier_id' => $request->supplier_id,
        'satuan' => $request->satuan,
        'deskripsi' => $request->deskripsi, // TAMBAHKAN INI
        'jumlah_produk' => $request->jumlah_produk,
        'tanggal_masuk' => $request->tanggal_masuk,
        'tanggal_kadaluarsa' => $request->tanggal_kadaluarsa,
    ]);

    return redirect()->route('stok_barang.index');
}
}
