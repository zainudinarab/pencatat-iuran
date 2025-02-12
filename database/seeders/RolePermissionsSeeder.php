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
        // Membuat user untuk Ketua
        $ketuaUser = User::create([
            'name' => 'Ketua',
            'email' => 'ketua@gmail.com',
            'password' => bcrypt('ketua123'),
        ]);
        $ketuaUser->assignRole('Ketua');

        // Membuat user untuk Bendahara
        $bendaharaUser = User::create([
            'name' => 'Khoirul Wahyudi',
            'email' => 'khoirul@gmail.com',
            'password' => bcrypt('12345'),
        ]);
        $bendaharaUser->assignRole('Bendahara');

        // Membuat user untuk Petugas
        $petugasUser = User::create([
            'name' => 'zainudin',
            'email' => 'zainudinarab@gmail.com',
            'password' => bcrypt('arab'),
        ]);
        $petugasUser->assignRole('Petugas');
    }
}
