<?php

namespace App\Http\Controllers;

use App\Models\DetailStokMasuk;
use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\History;
use App\Models\SatuanProduk;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage; // <-- PENTING: Tambahkan ini

class ProdukController extends Controller
{
    public function index()
    {
        $title = 'Hapus Produk!';
        $text = "Produk di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);

        $produk = Produk::with(['kategori', 'satuan'])->latest()->get(); // <-- Ditambahkan latest() agar data terbaru di atas
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        $satuan = SatuanProduk::all();
        $kategori = Kategori::all();
        $kode_produk = Produk::generateKodeProduk(); // <-- UBAH BARIS INI
        return view('produk.create', compact('kategori', 'satuan', 'kode_produk'));
    }

    // ==================================================================
    // >> METHOD STORE YANG SUDAH DISESUAIKAN <<
    // ==================================================================
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_produk' => 'required|string|unique:produk,kode_produk',
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan_produk,id',
            'harga' => 'required|numeric|min:0',
            'modal_harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
             // Validasi untuk gambar
        ]);
        $jeneng=auth()->user()->name; //log
        $log_history = ([ 
            'aktifitas'=>'Tambah produk',
            'nama'=>$jeneng,
            'detail'=> $request->input('kode_produk')
             // Validasi untuk gambar
        ]); //fungsi untuk menambahkan hitory 


        // Tambahkan 'stok' default
        $validatedData['stok'] = 0;

        // Proses upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('produk', 'public');
            $validatedData['gambar'] = $path;
        }

        Produk::create($validatedData);
        History::create($log_history); //fungsi untuk menambahkan hitory 


        Alert::success('Berhasil', 'Produk berhasil ditambahkan.');
        return redirect()->route('produk.index');
    }

    public function edit($id)
    {
        $produk = Produk::with(['kategori', 'satuan'])->findOrFail($id);
        $satuan = SatuanProduk::all();
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori', 'satuan'));
    }

    // ==================================================================
    // >> METHOD UPDATE YANG SUDAH DISESUAIKAN <<
    // ==================================================================
    public function update(Request $request, Produk $produk)
    {
        $validatedData = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'satuan_id' => 'required|exists:satuan_produk,id',
            'harga' => 'required|numeric|min:0',
            'modal_harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk gambar
        ]);
          $jeneng=auth()->user()->name; //fungsi untuk menambahkan hitory 

        $log_history = ([ 
            'aktifitas'=>'Edit produk',
            'nama'=>$jeneng,
            'detail'=> $produk['kode_produk']
             // Validasi untuk gambar
        ]); //fungsi untuk menambahkan hitory 


        // Proses upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama jika ada
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            // 2. Upload gambar baru dan simpan path-nya
            $path = $request->file('gambar')->store('produk', 'public');
            $validatedData['gambar'] = $path;
        }

        $produk->update($validatedData);
        History::create($log_history); //log

        Alert::success('Berhasil', 'Produk berhasil diedit.');
        return redirect()->route('produk.index');
    }

    // ==================================================================
    // >> METHOD DESTROY YANG SUDAH DISESUAIKAN <<
    // ==================================================================
    public function destroy(String $id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar dari storage sebelum menghapus data dari database
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

         $jeneng=auth()->user()->name; //fungsi untuk menambahkan hitory 

        $log_history = ([ 
            'aktifitas'=>'Hapus produk',
            'nama'=>$jeneng,
            'detail'=> $produk->kode_produk
             // Validasi untuk gambar
        ]); //fungsi untuk menambahkan hitory 

        $produk->delete();
        History::create($log_history); //fungsi untuk menambahkan hitory 



        Alert::success('Berhasil', 'Produk berhasil dihapus.');
        return redirect()->route('produk.index');
    }
    public function show($id)
{
    // 1. Ambil data produk utama, beserta relasi kategori dan satuan.
    // 'findOrFail($id)' akan otomatis menampilkan error 404 jika produk tidak ditemukan.
    $produk = Produk::with(['kategori', 'satuan'])->findOrFail($id);

    // 2. Ambil riwayat stok masuk untuk produk ini.
    // Kita mengambil DetailStokMasuk karena di sana ada informasi jumlah dan tanggal kadaluarsa.
    // Kita juga memuat relasi 'stokMasuk' agar bisa menampilkan kode stok masuk (SM...) dan supplier.
    // Diurutkan dari yang terbaru.
    $riwayatStokMasuk = DetailStokMasuk::where('produk_id', $id)
        ->with('stokMasuk.supplier') // Eager load relasi stokMasuk dan supplier-nya
        ->latest('created_at') // Urutkan berdasarkan tanggal pembuatan detail
        ->get();

    // 3. Ambil riwayat transaksi (penjualan) untuk produk ini.
    // Sama seperti stok masuk, kita ambil dari detailnya.
    // Kita memuat relasi 'transaksi' agar bisa menampilkan kode transaksi (TR...).
    $riwayatTransaksi = DetailTransaksi::where('produk_id', $id)
        ->with('transaksi') // Eager load relasi transaksi
        ->latest('created_at')
        ->get();

    // 4. Kirim semua data yang sudah kita kumpulkan ke view.
    return view('produk.show', compact(
        'produk',
        'riwayatStokMasuk',
        'riwayatTransaksi'
    ));
}
}
