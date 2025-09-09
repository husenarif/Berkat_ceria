<?php

namespace App\Http\Controllers;

use App\Models\History;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HistoryController extends Controller
{
    // Menampilkan semua pengguna
    public function index()
    {
        $title = '!';
        $text = " di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);

        $history = History::with(['stokMasuk', 'transaksi', 'produk'])
            ->latest() // Urutkan berdasarkan yang terbaru
            ->get();

        $users = User::with('role')->get();
        $roles = Role::all();
        return view('history.index', compact('history', 'users', 'roles'));
    }
}
