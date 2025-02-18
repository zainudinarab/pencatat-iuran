<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\KonfirmasiSetoranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
// RoleController
use App\Http\Controllers\RoleController;
// manage RT
use App\Http\Controllers\Rt\RtController;
use App\Http\Controllers\Rt\GangController;
use App\Http\Controllers\Rt\HouseController;
use App\Http\Controllers\Rt\IuranWajibController;
use App\Http\Controllers\Rt\PembayaranController;
use App\Http\Controllers\Rt\SetoranPetugasController;
use App\Http\Controllers\Rt\PengeluaranRtController;
use App\Http\Controllers\Rt\HouseUserController;
use App\Http\Controllers\Rt\JenisIuranController;
use App\Http\Controllers\Rt\KonfirmasiSetoranPetugasController;

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
});
// role Admin dan Bendahara
Route::middleware(['auth'])->group(function () {
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
    Route::get('/detail-penarikan', [PenarikanController::class, 'tarikan'])->name('penarikan.tarikan');
    // manage RT
    Route::prefix('manage-rt')->name('manage-rt.')->group(function () {
        // Route untuk RT
        Route::resource('rts', RtController::class);
        Route::resource('gangs', GangController::class);
        Route::resource('houses', HouseController::class);
        Route::resource('iuran-wajib', IuranWajibController::class);
        Route::resource('pembayaran', PembayaranController::class);
        Route::get('belum-dibayar/{house_id}', [PembayaranController::class, 'getIuranBelumDibayar'])->name('pembayaran.getIuranBelumDibayar');
        Route::resource('setoran', SetoranPetugasController::class);
        Route::get('confirm-setoran-petugas', [KonfirmasiSetoranPetugasController::class, 'confirmSetoran']);
        // put
        Route::put('/confirm-setoran-petugas/{setoran}/konfirmasi', [KonfirmasiSetoranPetugasController::class, 'konfirmasi'])->name('confirm.setoran.petugas');
        // Route::resource('setoran-petugas', SetoranPetugasController::class);
        Route::resource('pengeluaran-rt', PengeluaranRtController::class);
        Route::resource('jenis-iuran', JenisIuranController::class);
        // Route::resource('house-user', HouseUserController::class);
        // index
        Route::get('house-user', [HouseUserController::class, 'index'])->name('house-user.index');
        Route::get('house-user/link', [HouseUserController::class, 'linkUserToHouse'])->name('house-user.link');
        Route::post('house-user/link', [HouseUserController::class, 'storeLink'])->name('house-user.storeLink');
    });
});
// middleware('auth')
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
