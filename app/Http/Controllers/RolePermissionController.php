<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    // Menampilkan daftar role
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Menampilkan form untuk membuat role baru
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Menyimpan role baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array', // Validasi untuk permissions
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions); // Menetapkan permission ke role

        return redirect()->route('roles.index')->with('success', 'Role berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit role
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    // Memperbarui role
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui!');
    }

    // Menghapus role
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus!');
    }

    // Menampilkan daftar permission
    public function permissionsIndex()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    // Menampilkan form untuk membuat permission baru
    public function permissionsCreate()
    {
        return view('permissions.create');
    }

    // Menyimpan permission baru
    public function permissionsStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dibuat!');
    }
    // permissionsDestroy
    public function permissionsDestroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus!');
    }
    // permissions.create

}
