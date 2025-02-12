<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\KonfirmasiSetoranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [ResidentController::class, 'totaAamount']);
Route::get('/detail/{resident}', [ResidentController::class, 'detail'])->name('residents.detail');
// show




// Auth::routes();
Auth::routes([
    'register' => true
]);


// Route::middleware(['auth', 'role:petugas,bendahara'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

// Route untuk logout

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/home', function () {
        return view('home');
    })->name('home');


    // Menampilkan halaman profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/update-name', [ProfileController::class, 'updateName'])->name('profile.updateName');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::resource('setoran', SetoranController::class);
    Route::resource('residents', ResidentController::class);
    Route::resource('penarikan', PenarikanController::class);
    Route::resource('setoran', SetoranController::class);
    Route::resource('pengeluaran', PengeluaranController::class);
    Route::get('/penarikan-by-residents', [PenarikanController::class, 'getresidents'])->name('penarikan.getresidents');
    Route::get('confirm-setoran', [KonfirmasiSetoranController::class, 'confirmSetoran'])->name('confirm.setoran');
    Route::put('/setoran/{setoran}/konfirmasi', [KonfirmasiSetoranController::class, 'konfirmasi'])->name('setoran.konfirmasi');
    Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/tarikan-by-petugas', [LaporanController::class, 'tarikabypetugas'])->name('laporan.tarikabypetugas');
    Route::resource('penarikan', PenarikanController::class);
    Route::get('download-penarikan-excel', [PenarikanController::class, 'downloadExcel']);
    Route::get('download-penarikan-pdf', [PenarikanController::class, 'downloadPDF']);
});
// middleware('auth')
Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('managemen-users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    // Semua route berikut hanya dapat diakses oleh Admin
    Route::get('roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RolePermissionController::class, 'create'])->name('roles.create');
    Route::post('roles', [RolePermissionController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}/edit', [RolePermissionController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RolePermissionController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RolePermissionController::class, 'destroy'])->name('roles.destroy');
    Route::get('permissions', [RolePermissionController::class, 'permissionsIndex'])->name('permissions.index');
    Route::get('permissions/create', [RolePermissionController::class, 'permissionsCreate'])->name('permissions.create');
    Route::post('permissions', [RolePermissionController::class, 'permissionsStore'])->name('permissions.store');
    // permissions.destroy
    Route::delete('permissions/{permission}', [RolePermissionController::class, 'permissionsDestroy'])->name('permissions.destroy');
});
