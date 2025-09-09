<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // Menampilkan semua pengguna
    public function index()
    {
        $title = 'User!';
        $text = "User di hapus secara permanen, apakah anda yakin?";
        confirmDelete($title, $text);
        $roles = Role::all();
        $users = User::with('role')->get();
        return view('user.index', compact('users', 'roles'));
    }

    // Menampilkan form tambah user
    public function create()
    {  $roles = Role::all(); // ambil semua data role dari tabel roles
    return view('user.create', compact('roles'));
    }

    // Menyimpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required',
        ]);

        // Simpan data user ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // penting: jangan simpan password mentah!
            'role_id' => $request->role_id,
        ]);
        Alert::success('Berhasil', 'User Berhasil Di Tambahkan');
        return redirect()->route('user.index');
    }


    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('user.edit', compact('users'));
        
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user->update($request->all());

        Alert::success('Berhasil', 'User Berhasil Di Edit');
        return redirect()->route('user.index');
    }

    public function destroy(String $id)
    {
        User::destroy($id);

        Alert::success('Berhasil', 'User Berhasil Di Hapus');
        return redirect()->route('user.index');
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);
        $user->role_id = $request->role_id;
        $user->save();

        Alert::success('Berhasil', 'Role Berhasil Di Edit');

        return redirect()->route('user.index');
    }
}
