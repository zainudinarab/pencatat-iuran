<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Daftar tabel
        $tables = [
            'rts',
            'gangs',
            'houses',
            'house_user',
            'jenis_iurans',
            'iuran_wajibs',
            'pembayarans',
            'detail_pembayarans',
            'setoran_petugas',
            'saldo_rts',
            'saldo_rt_logs',
            'pengeluaran_rts',
            'konfirmasi_setoran_petugas',
            'activity_logs',
            'profils',
            'roles',
            'permissions',

        ];

        // Permissions per tabel: view, create, edit, delete
        $permissions = [];

        foreach ($tables as $table) {
            $permissions[] = "$table-view";
            $permissions[] = "$table-create";
            $permissions[] = "$table-edit";
            $permissions[] = "$table-delete";
        }
        // Tambahan khusus

        $permissions[] = 'pembayaran-global';
        $permissions[] = 'setoran_petugas-reject';
        $permissions[] = 'setoran_petugas-confirm';
        // Buat semua permissions
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $roles = [
            'admin',
            'ketua_rt',
            'bendahara_rt',
            'petugas_rt',
            'warga'
        ];


        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // Assign semua permissions ke Admin
        $adminRole = Role::findByName('admin');
        $adminRole->syncPermissions(Permission::all());

        // Contoh: assign sebagian permission ke role lainnya (bisa diatur sesuai kebutuhan)
        Role::findByName('ketua_rt')->syncPermissions([
            'rts-view',
            'rts-edit',
            'setoran_petugas-create',
            'setoran_petugas-view',
            'setoran_petugas-delete',
            'setoran_petugas-edit',
            'pembayarans-view',
            'pembayarans-create',
            'pembayarans-edit',
            'pembayarans-delete',
            'pembayaran-global',
            'activity_logs-view',
            'saldo_rts-view',
            'pengeluaran_rts-view',
            'pengeluaran_rts-view',
            'pengeluaran_rts-create',
            'pengeluaran_rts-edit',
            'pengeluaran_rts-delete'

        ]);

        Role::findByName('bendahara_rt')->syncPermissions([
            'iuran_wajibs-view',
            'iuran_wajibs-edit',
            'pembayarans-view',
            'pembayarans-create',
            'pembayarans-edit',
            'pembayarans-delete',
            'pembayaran-global',
            'setoran_petugas-create',
            'setoran_petugas-view',
            'setoran_petugas-delete',
            'setoran_petugas-edit',
            'saldo_rts-view',
            'pengeluaran_rts-view',
            'pengeluaran_rts-create',
            'setoran_petugas-reject',
            'setoran_petugas-confirm',
            'pengeluaran_rts-view',
            'pengeluaran_rts-create',
            'pengeluaran_rts-edit',
            'pengeluaran_rts-delete'
        ]);

        Role::findByName('petugas_rt')->syncPermissions([
            'setoran_petugas-create',
            'setoran_petugas-view',
            'setoran_petugas-delete',
            'setoran_petugas-edit',
            'konfirmasi_setoran_petugas-view',
            'pembayarans-view',
            'pembayarans-create',
            'pembayarans-edit',
            'pembayarans-delete'
        ]);
        Role::findByName('warga')->syncPermissions([
            'profils-view',
            'profils-create',
            'profils-edit',
            'profils-delete',
        ]);
    }
}
