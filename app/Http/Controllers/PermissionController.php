<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionController extends Controller
{
    // Menampilkan daftar role
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    // Menampilkan form untuk membuat role baru
    public function create()
    {
        return view('permissions.create');
    }

    // Menyimpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit role
    public function edit(Role $role) {}

    // Memperbarui role
    public function update(Request $request, Role $role) {}

    // Menghapus role
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus!');
    }
}
