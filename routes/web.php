<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\KonfirmasiSetoranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\LaporanController;


Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
Auth::routes([
    'register' => true
]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::middleware(['auth', 'role:petugas,bendahara'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

// Route untuk logout

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('home');
    })->name('dashboard');
    // Penarikan Iuran

    // Setoran
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
    Route::resource('penarikan', PenarikanController::class);
    // Route::middleware('role:petugas,bendahara')->group(function () {

    // });
});
// middleware('auth')
