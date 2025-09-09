<?php

namespace App\Http\Controllers;

use App\Models\SatuanProduk;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SatuanProdukController extends Controller
{
    public function Index()
    {
        $title = 'Hapus Satuan!';
        $text = "Satuan di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);
        $satuan_produk = SatuanProduk::all();
        return view('satuan_produk.index', compact('satuan_produk'));
    }
    public function create()
    {
        $satuan_produk = SatuanProduk::all(); // ambil semua satuan_produk
        return view('satuan_produk.create', compact('satuan_produk'));
    }

      public function edit($id)
    {
        $satuan_produk = SatuanProduk::findOrFail($id);
        return view('satuan_produk.edit', compact('satuan_produk'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_satuan' => 'required',


        ], [
            'nama_satuan.required' => 'Nama Satuan Harus di isi',
        ]);

        SatuanProduk::create([
            'nama_satuan' => $request->nama_satuan,
        ]);
        Alert::success('Berhasil', 'Satuan Berhasil Di Tambahkan');
        return redirect()->route('satuan_produk.index');
    }

     public function update(Request $request, SatuanProduk $satuan_produk)
    {
        $request->validate([
            'nama_satuan' => 'required',
      
        ]);

        $satuan_produk->update($request->all());
        Alert::success('Berhasil', 'Satuan Berhasil Di Edit');
        return redirect()->route('satuan_produk.index');
    }

    
    public function destroy(String $id){
        SatuanProduk::destroy($id);
        Alert::success('Berhasil', 'Satuan Berhasil Di Hapus');
        return redirect()->route('satuan_produk.index');
    }

}
