<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Permissions untuk tabel residents
        Permission::create(['name' => 'view residents']);
        Permission::create(['name' => 'create residents']);
        Permission::create(['name' => 'edit residents']);
        Permission::create(['name' => 'delete residents']);

        // Permissions untuk tabel penarikans
        Permission::create(['name' => 'view penarikans']);
        Permission::create(['name' => 'create penarikans']);
        Permission::create(['name' => 'edit penarikans']);
        Permission::create(['name' => 'delete penarikans']);

        // Permissions untuk tabel setorans
        Permission::create(['name' => 'view setorans']);
        Permission::create(['name' => 'create setorans']);
        Permission::create(['name' => 'edit setorans']);
        Permission::create(['name' => 'delete setorans']);

        // Permissions untuk tabel konfirmasi_setorans
        Permission::create(['name' => 'view konfirmasi_setorans']);
        Permission::create(['name' => 'create konfirmasi_setorans']);
        Permission::create(['name' => 'edit konfirmasi_setorans']);
        Permission::create(['name' => 'delete konfirmasi_setorans']);

        // Permissions untuk tabel pengeluarans
        Permission::create(['name' => 'view pengeluarans']);
        Permission::create(['name' => 'create pengeluarans']);
        Permission::create(['name' => 'edit pengeluarans']);
        Permission::create(['name' => 'delete pengeluarans']);

        // Permissions untuk tabel saldoes
        Permission::create(['name' => 'view saldoes']);
        Permission::create(['name' => 'create saldoes']);
        Permission::create(['name' => 'edit saldoes']);
        Permission::create(['name' => 'delete saldoes']);

        // Permissions untuk tabel log_saldos
        Permission::create(['name' => 'view log_saldos']);
        Permission::create(['name' => 'create log_saldos']);
        Permission::create(['name' => 'edit log_saldos']);
        Permission::create(['name' => 'delete log_saldos']);
        // Permissions untuk tabel profils
        Permission::create(['name' => 'view profils']);
        Permission::create(['name' => 'create profils']);
        Permission::create(['name' => 'edit profils']);
        Permission::create(['name' => 'delete profils']);
    }
}
