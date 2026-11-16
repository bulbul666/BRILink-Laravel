<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('transaksi', TransaksiController::class)
        ->except('show')
        ->parameters(['transaksi' => 'transaksi']);

    Route::resource('pengeluaran', PengeluaranController::class)
        ->except('show')
        ->parameters(['pengeluaran' => 'pengeluaran']);

    Route::get('saldo', [SaldoController::class, 'index'])->name('saldo.index');
    Route::post('saldo', [SaldoController::class, 'store'])->name('saldo.store');
    Route::delete('saldo/{saldo}', [SaldoController::class, 'destroy'])->name('saldo.destroy');

    Route::get('laporan/harian', [LaporanController::class, 'harian'])->name('laporan.harian');
    Route::get('laporan/harian/pdf', [LaporanController::class, 'harianPdf'])->name('laporan.harian.pdf');
    Route::get('laporan/bulanan', [LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('laporan/bulanan/pdf', [LaporanController::class, 'bulananPdf'])->name('laporan.bulanan.pdf');
});

require __DIR__ . '/auth.php';