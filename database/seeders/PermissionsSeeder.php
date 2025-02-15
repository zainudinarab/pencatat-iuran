<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // role
        Permission::create(['name' => 'role-view']);
        Permission::create(['name' => 'role-create']);
        Permission::create(['name' => 'role-edit']);
        Permission::create(['name' => 'role-delete']);
        // Permissions untuk tabel residents
        Permission::create(['name' => 'residents-view']);
        Permission::create(['name' => 'residents-create']);
        Permission::create(['name' => 'residents-edit']);
        Permission::create(['name' => 'residents-delete']);

        // Permissions untuk tabel penarikans
        Permission::create(['name' => 'penarikans-view']);
        Permission::create(['name' => 'penarikans-create']);
        Permission::create(['name' => 'penarikans-edit']);
        Permission::create(['name' => 'penarikans-delete']);

        // Permissions untuk tabel setorans
        Permission::create(['name' => 'setorans-view']);
        Permission::create(['name' => 'setorans-create']);
        Permission::create(['name' => 'setorans-edit']);
        Permission::create(['name' => 'setorans-delete']);

        // Permissions untuk tabel konfirmasi_setorans
        Permission::create(['name' => 'konfirmasi_setorans-view']);
        Permission::create(['name' => 'konfirmasi_setorans-create']);
        Permission::create(['name' => 'konfirmasi_setorans-edit']);
        Permission::create(['name' => 'konfirmasi_setorans-delete']);

        // Permissions untuk tabel pengeluarans
        Permission::create(['name' => 'pengeluarans-view']);
        Permission::create(['name' => 'pengeluarans-create']);
        Permission::create(['name' => 'pengeluarans-edit']);
        Permission::create(['name' => 'pengeluarans-delete']);

        // Permissions untuk tabel saldoes
        Permission::create(['name' => 'saldones-view']);


        // Permissions untuk tabel log_saldos
        Permission::create(['name' => 'log_saldos-view']);


        // Permissions untuk tabel profils
        Permission::create(['name' => 'profils-view']);
        Permission::create(['name' => 'profils-create']);
        Permission::create(['name' => 'profils-edit']);
        Permission::create(['name' => 'profils-delete']);
        // Permissions untuk tabel laporan
        Permission::create(['name' => 'laporan-view']);
    }
}
