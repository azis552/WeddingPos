<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Models\Barang;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::put('akun/update/{id}', [AuthController::class, 'updateRole'])->name('user.updateRole');
    Route::Delete('akun/delete/{id}', [AuthController::class, 'destroy'])->name('user.destroy');
    Route::Post('/akun/add', [AuthController::class, 'AddUser'])->name('register.addUser');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/akun', [AuthController::class, 'akun'])->name('akun');
    Route::get('/akun/show/{id}', [AuthController::class, 'show'])->name('user.show');
    Route::post('/akun/{id}', [AuthController::class, 'update'])->name('user.update');
    Route::delete('/akun/{id}', [AuthController::class, 'destroy'])->name('user.destroy');

    // 

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang',[BarangController::class, 'store'])->name('barang.store');
    Route::PUT('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    // 

    Route::get('/listBarang', [BarangController::class, 'ListBarang'])->name('listBarang');
    Route::get('/listBarang/{id}', [BarangController::class, 'show'])->name('listBarang.show');
    Route::get('keranjang', [BarangController::class, 'keranjang'])->name('keranjang');
});
