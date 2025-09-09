<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupplierController extends Controller
{
    public function Index()
    {
        $title = 'Hapus Supplier!';
        $text = "Supplier di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);
        $supplier = Supplier::all();
        return view('supplier.index', compact('supplier'));
    }
    public function create()
    {
        $supplier = Supplier::all(); // ambil semua kategori
        return view('supplier.create', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = supplier::findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, supplier $supplier)
    {
        $request->validate([
            'nama_supplier' => 'required',
            'alamat' => 'nullable',
            'telepon' => 'nullable',
            // 'email' => 'required',
        ]);

        $supplier->update($request->all());
        Alert::success('Berhasil', 'Supplier Berhasil Di Edit');

        return redirect()->route('supplier.index');
    }

    public function destroy(String $id)
    {
        supplier::destroy($id);

        Alert::success('Berhasil', 'Supplier Berhasil Di Hapus');
        return redirect()->route('supplier.index');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_supplier' => 'required',

            // 'email' => 'required',

        ], [
            'nama_supplier.required' => 'Nama Kategori Harus di isi',

            // 'email.required' => 'Email Harus di isi',
        ]);

        Supplier::create([
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            // 'email' => $request->email,
        ]);
        Alert::success('Berhasil', 'Supplier Berhasil Di Tambahkan');

        return redirect()->route('supplier.index');
    }
}
