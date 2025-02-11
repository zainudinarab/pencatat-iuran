<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin']);  // Hanya admin yang bisa mengakses
    }

    // Menampilkan Daftar Pengguna
    public function index()
    {

        $users = User::all();  // Ambil semua pengguna

        return view('users.index', compact('users'));
    }

    // Menampilkan Form Edit Role Pengguna
    public function edit($id)
    {
        $user = User::findOrFail($id);  // Cari pengguna berdasarkan ID
        $roles = Role::all();  // Ambil semua role yang ada
        // dd($roles);
        return view('users.edit', compact('user', 'roles'));
    }

    // Mengupdate Role Pengguna
    public function update(Request $request, $id)
    {

        $request->validate([
            'roles' => 'required|array',  // Pastikan role dipilih
            'roles.*' => 'exists:roles,name',  // Pastikan ID role yang dipilih valid
        ]);

        $user = User::findOrFail($id);  // Cari pengguna berdasarkan ID

        $user->syncRoles($request->roles);  // Update role pengguna

        return redirect()->route('users.index')->with('success', 'Role pengguna berhasil diubah!');
    }
}
