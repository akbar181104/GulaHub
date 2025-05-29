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
    Route::get('/petani/rencanagiling/{id}', [PageController::class, 'rencanaGilingByPetani'])->name('petani.rencanagiling');
    Route::post('/ajukan-setoran/{id}', [PageController::class, 'ajukanSetoran'])->name('petani.ajukan');
    Route::get('/permintaan', [PageController::class, 'permintaanSetor'])->name('petani.permintaan');
    Route::post('/petani/konfirmasi/{rencanaPanenId}/{pabrikId}', [PageController::class, 'konfirmasiSetor'])->name('petani.konfirmasi');
    Route::get('/rencanapanen', [PageController::class, 'rencanaPanen'])->name('petani.rencanapanen');
    Route::post('/rencanapanen', [PageController::class, 'storeRencanaPanen'])->name('rencanapanen.store');
    Route::put('/rencanapanen/{id}', [PageController::class, 'updatePanen'])->name('rencanapanen.update');
    Route::delete('/rencanapanen/{id}', [PageController::class, 'destroyPanen'])->name('rencanapanen.destroy');
    Route::get('/riwayatsetor', [PageController::class, 'riwayatSetor'])->name('petani.riwayatsetor');

});

// =======================
// PABRIK ROUTES
// =======================
Route::middleware(['auth', 'role:pabrik'])->prefix('pabrik')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboardPabrik'])->name('pabrik.dashboard');
    Route::get('/profil', [PageController::class, 'profilPabrik'])->name('pabrik.profil');
    Route::get('/jadwalpanen', [PageController::class, 'jadwalPanen'])->name('pabrik.jadwalpanen');
    Route::get('/pabrik/rencanapanen/{id}', [PageController::class, 'rencanaPanenByPabrik'])->name('pabrik.rencanapanen');
    Route::post('/ajukan-terima/{id}', [PageController::class, 'ajukanTerima'])->name('pabrik.ajukan');
    Route::get('/permintaan', [PageController::class, 'permintaanTerima'])->name('pabrik.permintaan');
    Route::post('/pabrik/konfirmasi/{rencanaGilingId}/{petaniId}', [PageController::class, 'konfirmasiAjuan'])->name('pabrik.konfirmasi');
    Route::get('/rencanagiling', [PageController::class, 'rencanaGiling'])->name('pabrik.rencanagiling');
    Route::post('/rencanagiling', [PageController::class, 'storeRencanaGiling'])->name('rencanagiling.store');
    Route::put('/rencanagiling/{id}', [PageController::class, 'updateGiling'])->name('rencanagiling.update');
    Route::delete('/rencanagiling/{id}', [PageController::class, 'destroyGiling'])->name('rencanagiling.destroy');
    Route::get('/riwayatterima', [PageController::class, 'riwayatTerima'])->name('pabrik.riwayatterima');
});
