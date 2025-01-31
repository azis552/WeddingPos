<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', [AuthController::class, 'index'])->name('login');

Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');

Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
