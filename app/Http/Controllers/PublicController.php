<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data produk dengan relasi kategori
        $query = Produk::with('kategori');

        // Logika untuk filter pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        // Logika untuk filter kategori
        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        // Ambil data produk dan semua kategori untuk filter
        $produk = $query->latest()->get();
        $kategori = Kategori::all();

        // Kirim data ke view
        return view('public.stok_index', compact('produk', 'kategori'));
    }
}
