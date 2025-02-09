<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\ResidentController;



Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Route untuk logout

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    // Penarikan Iuran
    Route::resource('penarikan', PenarikanController::class);
    // Setoran
    Route::resource('setoran', SetoranController::class);
    Route::resource('residents', ResidentController::class);
    Route::resource('penarikan', PenarikanController::class);
    Route::resource('setoran', SetoranController::class);
    Route::get('/penarikan-by-residents', [PenarikanController::class, 'getresidents'])->name('penarikan.getresidents');
    Route::post('/setoran/{id}/confirm', [SetoranController::class, 'confirmSetoran'])->name('setoran.confirm');
});
