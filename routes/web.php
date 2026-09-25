<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\ObatController as AdminObat;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksi;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;
use App\Http\Controllers\Kasir\ObatController as KasirObat;
use App\Http\Controllers\Kasir\PosController;
use App\Http\Controllers\Kasir\TransaksiController as KasirTransaksi;
use Illuminate\Support\Facades\Route;

// =========================================================
// Guest routes
// =========================================================
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// =========================================================
// Authenticated routes
// =========================================================
Route::middleware('auth:sanctum')->group(function (): void {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // -------------------------------------------------------
    // Admin routes
    // -------------------------------------------------------
    Route::middleware('admin')->prefix('admin')->group(function (): void {

        // Dashboard
        Route::get('/', [AdminDashboard::class, 'index'])->name('admin.dashboard');

        // Obat
        Route::get('/obat', [AdminObat::class, 'index'])->name('admin.obat');
        Route::get('/obat/expired', [AdminObat::class, 'expired'])->name('admin.obat.expired');
        Route::get('/obat/expired/export', [AdminObat::class, 'exportExpired'])->name('admin.obat.expired.export');
        Route::get('/obat/create', [AdminObat::class, 'create'])->name('admin.obat.create');
        Route::post('/obat', [AdminObat::class, 'store'])->name('admin.obat.store');
        Route::get('/obat/{id}/edit', [AdminObat::class, 'edit'])->name('admin.obat.edit');
        Route::put('/obat/{id}', [AdminObat::class, 'update'])->name('admin.obat.update');
        Route::delete('/obat/{id}', [AdminObat::class, 'destroy'])->name('admin.obat.delete');

        // User
        Route::get('/user', [UserController::class, 'index'])->name('admin.user');
        Route::get('/user/create', [UserController::class, 'create'])->name('admin.user.create');
        Route::post('/user', [UserController::class, 'store'])->name('admin.user.store');
        Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');

        // Transaksi
        Route::get('/transaksi', [AdminTransaksi::class, 'index'])->name('admin.transaksi');
        Route::get('/transaksi/profit', [AdminTransaksi::class, 'profit'])->name('admin.transaksi.profit');
        Route::get('/transaksi/export', [AdminTransaksi::class, 'exportExcel'])->name('admin.transaksi.export');
        Route::get('/transaksi/struk/{kode}', [AdminTransaksi::class, 'cetakStruk'])->name('admin.cetak.struk');
        Route::post('/transaksi/{id}/void', [AdminTransaksi::class, 'void'])->name('transaksi.void');
        Route::post('/transaksi/{transaction}/return', [AdminTransaksi::class, 'processReturn'])->name('admin.transaksi.return');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'laporan'])->name('admin.laporan');
    });

    // -------------------------------------------------------
    // Kasir routes
    // -------------------------------------------------------
    Route::middleware('kasir')->prefix('kasir')->group(function (): void {

        // Dashboard (kasir root)
        Route::get('/', [KasirDashboard::class, 'index'])->name('kasir.dashboard');

        // POS
        Route::get('/pos', [PosController::class, 'index'])->name('kasir.pos');
        Route::post('/pos', [PosController::class, 'bayar'])->name('kasir.pos.store');
        Route::get('/struk/{kode}', [PosController::class, 'cetakStruk'])->name('kasir.cetak.struk');

        // Obat (read-only for kasir)
        Route::get('/obat', [KasirObat::class, 'index'])->name('kasir.obat');
        Route::get('/obat/search', [KasirObat::class, 'search'])->name('kasir.obat.search');

        // Transaksi
        Route::get('/transaksi', [KasirTransaksi::class, 'index'])->name('kasir.transaksi');
        Route::get('/transaksi/profit', [KasirTransaksi::class, 'profit'])->name('kasir.transaksi.profit');
        Route::get('/transaksi/export', [KasirTransaksi::class, 'exportExcel'])->name('kasir.transaksi.export');
        Route::post('/transaksi/{id}/void', [KasirTransaksi::class, 'void'])->name('kasir.transaksi.void');
        Route::post('/transaksi/{transaction}/return', [KasirTransaksi::class, 'processReturn'])->name('kasir.transaksi.return');
    });
});
