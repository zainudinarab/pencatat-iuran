<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionsSeeder extends Seeder
{
    public function run()
    {
        // Admin role
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // Ketua role
        $ketua = Role::create(['name' => 'Ketua']);
        $ketua->givePermissionTo([
            'view residents',
            'create residents',
            'edit residents',
            'view penarikans',
            'create penarikans',
            'edit penarikans',
            'view setorans',
            'create setorans',
            'edit setorans',
            'view pengeluarans',
            'create pengeluarans',
            'view profils',
            'edit profils',
            'view saldoes',

        ]);
        // Bendahara role
        $bendahara = Role::create(['name' => 'Bendahara']);
        $bendahara->givePermissionTo([
            'view residents',
            'view penarikans',
            'create penarikans',
            'view setorans',
            'create setorans',
            'view saldoes',
            'create saldoes',
            'edit saldoes',
            'view profils',
            'edit profils',
            'view saldoes',
        ]);

        // Petugas role
        $petugas = Role::create(['name' => 'Petugas']);
        $petugas->givePermissionTo([
            'view penarikans',
            'view setorans',
            'view pengeluarans',
            'view saldoes',
            'view profils',
            'edit profils',
            'view saldoes',
        ]);
        // Warga role
        $warga = Role::create(['name' => 'Warga']);
        $warga->givePermissionTo([
            'view residents',
            'view setorans',
        ]);
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
        ]);
        $adminUser->assignRole('Admin');
        $normalUser = User::create([
            'name' => 'Normal User',
            'email' => 'warga@user.com',
            'password' => bcrypt('warga'),
        ]);
        $normalUser->assignRole('Warga');
    }
}
