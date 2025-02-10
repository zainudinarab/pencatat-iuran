<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat permission
    Permission::create(['name' => 'manage_activities']);
    Permission::create(['name' => 'manage_dues']);
    Permission::create(['name' => 'collect_dues']);
    Permission::create(['name' => 'view_dues']);
    Permission::create(['name' => 'approve_dues']);
    Permission::create(['name' => 'report_dues']);

    // Buat role dan assign permission
    $ketua = Role::create(['name' => 'Ketua']);
    $ketua->givePermissionTo(['manage_activities', 'manage_dues', 'view_dues', 'approve_dues', 'report_dues']);

    $bendahara = Role::create(['name' => 'Bendahara']);
    $bendahara->givePermissionTo(['manage_dues', 'view_dues', 'approve_dues', 'report_dues']);

    $petugas = Role::create(['name' => 'Petugas']);
    $petugas->givePermissionTo(['collect_dues', 'view_dues']);

    $warga = Role::create(['name' => 'Warga']);
    $warga->givePermissionTo('view_dues');
    $adminUser = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
    ]);
    $adminUser->assignRole('admin');

    $normalUser = User::create([
        'name' => 'Normal User',
        'email' => 'user@example.com',
        'password' => bcrypt('password123'),
    ]);
    $normalUser->assignRole('user');
    }
}
