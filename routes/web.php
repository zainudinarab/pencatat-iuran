<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\SetoranController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\KonfirmasiSetoranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LaporanController;

// RT Controllers
// RT LaporanController
use App\Http\Controllers\Rt\LaporanController as RtLaporanController;
use App\Http\Controllers\Rt\DashboardController;
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
use App\Http\Controllers\Rt\TransferPosRtController;

// Authentication Routes
Auth::routes(['register' => true]);

// Public Routes
Route::get('/', [ResidentController::class, 'totalAmount'])->name('home.total');
Route::get('/detail/{resident}', [ResidentController::class, 'detail'])->name('residents.detail');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update-name', [ProfileController::class, 'updateName'])->name('profile.updateName');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // =============================
    // 🔐 WARGA (Resident) Routes
    // =============================
    Route::middleware('role:warga')->prefix('warga')->name('warga.')->group(function () {
        // Contoh: dashboard warga, pembayaran, dll
        // Route::get('/dashboard', [WargaController::class, 'index'])->name('dashboard');
    });

    // =============================
    // 🔐 SHARED: Admin + RT Roles
    // =============================
    Route::middleware('role:admin|ketua_rt|bendahara_rt|petugas_rt')
        ->prefix('manage-rt')
        ->name('manage-rt.')
        ->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
            // CRUD Data Dasar
            Route::resource('rts', RtController::class);
            Route::resource('gangs', GangController::class);
            Route::resource('houses', HouseController::class);
            Route::resource('jenis-iuran', JenisIuranController::class);
            Route::resource('iuran-wajib', IuranWajibController::class);

            // Pembayaran
            Route::resource('pembayaran', PembayaranController::class);
            Route::get('pembayaran-global', [PembayaranController::class, 'pembayaranGlobal'])->name('pembayaran.global');
            Route::get('belum-dibayar/{house_id}', [PembayaranController::class, 'getIuranBelumDibayar'])
                ->name('pembayaran.belum-dibayar');
            Route::get('iuran-all/{house_id}', [PembayaranController::class, 'getAllIuran'])
                ->name('pembayaran.iuran-all');


            // Setoran Petugas
            Route::resource('setoran-petugas', SetoranPetugasController::class);
            Route::get('confirm-setoran-petugas', [KonfirmasiSetoranPetugasController::class, 'confirmSetoran'])
                ->name('confirm.setoran.petugas');
            Route::put('setoran-petugas/{setoran}/konfirmasi', [KonfirmasiSetoranPetugasController::class, 'konfirmasi'])
                ->name('setoran-petugas.konfirmasi');

            // Pengeluaran RT
            Route::resource('pengeluaran-rt', PengeluaranRtController::class);

            // Link User to House
            Route::get('house-user', [HouseUserController::class, 'index'])->name('house-user.index');
            Route::get('house-user/link', [HouseUserController::class, 'linkUserToHouse'])->name('house-user.link');
            Route::post('house-user/link', [HouseUserController::class, 'storeLink'])->name('house-user.storeLink');
        });

    // ===================================
    // 🔐 Khusus: Ketua RT & Bendahara RT
    // ===================================
    Route::middleware('role:ketua_rt|bendahara_rt')
        ->prefix('manage-rt')
        ->name('manage-rt.shared.')
        ->group(function () {
            Route::get('laporan-keuangan', [LaporanController::class, 'laporanKeuangan'])->name('laporan.keuangan');
            // ✅ Laporan Petugas
            Route::get('laporan-petugas', [RtLaporanController::class, 'laporanPetugas'])->name('laporan.petugas');
            Route::get('laporan-petugas/{id}/detail', [RtLaporanController::class, 'detailPetugas'])->name('petugas.detail');
            Route::get('laporan-petugas/detail-pembayaran/{id}', [RtLaporanController::class, 'showDetail'])->name('detail-pembayaran.show');
            Route::get('laporan-tahunan-gang', [RtLaporanController::class, 'laporanTahunanPerGang'])->name('laporan.tahunan-gang');
            Route::get('laporan-tahunan-gang/{tahun}/{gang_id}', [RtLaporanController::class, 'detailTahunanPerGang'])
                ->name('laporan.tahunan-gang.detail');


            Route::get('pengaturan-iuran', [IuranWajibController::class, 'atur'])->name('iuran.atur');
            Route::get('/transfer-pos', [TransferPosRtController::class, 'index'])->name('transfer-pos.index');
            Route::post('/transfer-pos', [TransferPosRtController::class, 'store'])->name('transfer-pos.store');
            Route::post('/transfer-pos/{id}/konfirmasi', [TransferPosRtController::class, 'konfirmasi'])->name('transfer-pos.konfirmasi');
            Route::post('/transfer-pos/{id}/tolak', [TransferPosRtController::class, 'tolak'])->name('transfer-pos.tolak');
            // Pengeluaran RT
            Route::get('/pengeluaran', [PengeluaranRtController::class, 'index'])->name('pengeluaran.index');
            Route::get('/pengeluaran/create', [PengeluaranRtController::class, 'create'])->name('pengeluaran.create');
            Route::post('/pengeluaran', [PengeluaranRtController::class, 'store'])->name('pengeluaran.store');
            Route::get('/pengeluaran/{pengeluaranRt}/edit', [PengeluaranRtController::class, 'edit'])->name('pengeluaran.edit');
            Route::put('/pengeluaran/{pengeluaranRt}', [PengeluaranRtController::class, 'update'])->name('pengeluaran.update');
            Route::delete('/pengeluaran/{pengeluaranRt}', [PengeluaranRtController::class, 'destroy'])->name('pengeluaran.destroy');
            Route::get('pengeluaran/{id}/nota', [PengeluaranRtController::class, 'nota'])->name('pengeluaran.nota');

            // Konfirmasi pengeluaran
            Route::post('/pengeluaran/{id}/approve', [PengeluaranRtController::class, 'approvePengeluaran'])->name('pengeluaran.approve');

            // Lihat nota pengeluaran
            // Route::get('/pengeluaran/{id}/nota', [PengeluaranRtController::class, 'showNota'])->name('pengeluaran.nota');
        });

    // ===================================
    // 🔐 Khusus: Bendahara RT
    // ===================================
    Route::middleware('role:bendahara_rt')
        ->prefix('manage-rt/bendahara')
        ->name('manage-rt.bendahara.')
        ->group(function () {
            Route::get('konfirmasi-setoran', [KonfirmasiSetoranPetugasController::class, 'index'])->name('konfirmasi-setoran.index');
            Route::post('konfirmasi-setoran/{id}', [KonfirmasiSetoranPetugasController::class, 'konfirmasi'])->name('konfirmasi-setoran.konfirmasi');
            Route::post('batalkan-konfirmasi/{id}', [KonfirmasiSetoranPetugasController::class, 'batalkanKonfirmasi'])->name('konfirmasi-setoran.batal');
        });

    // ===================================
    // 🔐 Khusus: Ketua RT
    // ===================================
    Route::middleware('role:ketua_rt')
        ->prefix('manage-rt/ketua-rt')
        ->name('manage-rt.ketua-rt.')
        ->group(function () {
            // Tambahkan route khusus ketua RT di sini
            // Contoh: approve laporan, cetak rekap, dll
        });

    // ===================================
    // 🔐 Khusus: Petugas RT
    // ===================================
    Route::middleware('role:petugas_rt')
        ->prefix('manage-rt/petugas-rt')
        ->name('manage-rt.petugas-rt.')
        ->group(function () {
            // Tambahkan route khusus petugas RT
            // Contoh: input setoran harian, lihat target, dll
        });

    // ===================================
    // 🏦 Umum: Keuangan & Laporan
    // ===================================
    Route::get('/saldo', [SaldoController::class, 'index'])->name('saldo.index');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/tarikan-by-petugas', [LaporanController::class, 'tarikabypetugas'])->name('laporan.tarikabypetugas');

    // Penarikan
    Route::resource('penarikan', PenarikanController::class);
    Route::get('penarikan-by-residents', [PenarikanController::class, 'getresidents'])->name('penarikan.getresidents');
    Route::get('detail-penarikan', [PenarikanController::class, 'tarikan'])->name('penarikan.tarikan');
    Route::get('download-penarikan-excel', [PenarikanController::class, 'downloadExcel'])->name('penarikan.download.excel');
    Route::get('download-penarikan-pdf', [PenarikanController::class, 'downloadPDF'])->name('penarikan.download.pdf');

    // Setoran
    Route::resource('setoran', SetoranController::class);
    Route::get('confirm-setoran', [KonfirmasiSetoranController::class, 'confirmSetoran'])->name('setoran.confirm');
    Route::put('/setoran/{setoran}/konfirmasi', [KonfirmasiSetoranController::class, 'konfirmasi'])->name('setoran.konfirmasi');

    // Pengeluaran
    Route::resource('pengeluaran', PengeluaranController::class);
});

// ===================================
// 🔐 Admin Only Routes
// ===================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});
