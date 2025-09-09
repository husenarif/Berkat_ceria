<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriController extends Controller
{
    public function Index()
    {
        $title = 'Hapus Kategori!';
        $text = "Kategori di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);
        $kategori = Kategori::all();
        return view('kategori.index', compact('kategori'));
    }
    public function create()
    {
        $kategori = Kategori::all(); // ambil semua kategori
        return view('kategori.create', compact('kategori'));
    }
     public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('kategori.edit', compact('kategori', 'kategori'));
    }
  public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required',
      
        ]);

        $kategori->update($request->all());
        Alert::success('Berhasil', 'Kategori Berhasil Di Edit');
        return redirect()->route('kategori.index');
    }

    public function destroy(String $id){
        Kategori::destroy($id);
        Alert::success('Berhasil', 'Kategori Berhasil Di Hapus');

        return redirect()->route('kategori.index');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_kategori' => 'required',
            // 'deskripsi' => 'required',

        ], [
            'nama_kategori.required' => 'Nama Kategori Harus di isi',
            // 'deskripsi.required' => 'deskripsi Harus di isi',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            // 'deskripsi' => $request->deskripsi,
        ]);
        Alert::success('Berhasil', 'Kategori Berhasil Di Tambahkan');
        return redirect()->route('kategori.index');
    }

    
}
