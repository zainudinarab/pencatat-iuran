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
        // Assign permissions to $ketua
        $ketua->givePermissionTo([
            'residents-view',
            'residents-create',
            'residents-edit',
            'residents-delete',
            'penarikans-view',
            'penarikans-create',
            'penarikans-edit',
            'penarikans-delete',
            'setorans-view',
            'setorans-create',
            'setorans-edit',
            'setorans-delete',
            'konfirmasi_setorans-view',
            'konfirmasi_setorans-create',
            'konfirmasi_setorans-edit',
            'konfirmasi_setorans-delete',
            'pengeluarans-view',
            'pengeluarans-create',
            'pengeluarans-edit',
            'pengeluarans-delete',
            'saldones-view',
            'log_saldos-view',
            'profils-view',
            'profils-create',
            'profils-edit',
            'profils-delete',
            'laporan-view',
        ]);

        // Bendahara role
        $bendahara = Role::create(['name' => 'Bendahara']);
        $bendahara->givePermissionTo([
            'konfirmasi_setorans-view',
            'konfirmasi_setorans-create',
            'konfirmasi_setorans-edit',
            'konfirmasi_setorans-delete',
            'pengeluarans-view',
            'pengeluarans-create',
            'pengeluarans-edit',
            'pengeluarans-delete',
            'saldones-view',
            'laporan-view',
            'log_saldos-view',

            'profils-view',
            'profils-create',
            'profils-edit',
            'profils-delete',
        ]);

        // Petugas role
        $petugas = Role::create(['name' => 'Petugas']);
        $petugas->givePermissionTo([
            'residents-view',
            'residents-create',
            'residents-edit',
            'residents-delete',
            'penarikans-view',
            'penarikans-create',
            'penarikans-edit',
            'penarikans-delete',
            'setorans-view',
            'setorans-create',
            'setorans-edit',
            'setorans-delete',
            'profils-view',
            'profils-create',
            'profils-edit',
            'profils-delete',
        ]);
        // Warga role
        $warga = Role::create(['name' => 'Warga']);
        $warga->givePermissionTo([
            'profils-view',
            'profils-create',
            'profils-edit',
            'profils-delete',
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
