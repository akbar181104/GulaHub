<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;

// =======================
// AUTH
// =======================
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registrasi
Route::get('/register/petani', [AuthController::class, 'showRegisterPetani'])->name('register.petani');
Route::post('/register/petani', [AuthController::class, 'registerPetani']);

Route::get('/register/pabrik', [AuthController::class, 'showRegisterPabrik'])->name('register.pabrik');
Route::post('/register/pabrik', [AuthController::class, 'registerPabrik']);


// =======================
// PETANI ROUTES
// =======================
Route::middleware(['auth', 'role:petani'])->prefix('petani')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboardPetani'])->name('petani.dashboard');
    Route::get('/profil', [PageController::class, 'profilPetani'])->name('petani.profil');
    Route::get('/jadwalgiling', [PageController::class, 'jadwalGiling'])->name('petani.jadwalgiling');
    Route::get('/permintaan', [PageController::class, 'permintaanSetor'])->name('petani.permintaan');
    Route::get('/rencanapanen', [PageController::class, 'rencanaPanen'])->name('petani.rencanapanen');
    Route::get('/riwayatsetor', [PageController::class, 'riwayatSetor'])->name('petani.riwayatsetor');
});

// =======================
// PABRIK ROUTES
// =======================
Route::middleware(['auth', 'role:pabrik'])->prefix('pabrik')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboardPabrik'])->name('pabrik.dashboard');
    Route::get('/profil', [PageController::class, 'profilPabrik'])->name('pabrik.profil');
    Route::get('/jadwalpanen', [PageController::class, 'jadwalPanen'])->name('pabrik.jadwalpanen');
    Route::get('/permintaan', [PageController::class, 'permintaanTerima'])->name('pabrik.permintaan');
    Route::get('/rencanagiling', [PageController::class, 'rencanaGiling'])->name('pabrik.rencanagiling');
    Route::get('/riwayatterima', [PageController::class, 'riwayatTerima'])->name('pabrik.riwayatterima');
});
