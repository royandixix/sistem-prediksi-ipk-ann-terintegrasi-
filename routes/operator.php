<?php

use App\Http\Controllers\Operator\DashboardController;
use App\Http\Controllers\Operator\DataIpsController;
use App\Http\Controllers\Operator\HasilPrediksiController;
use App\Http\Controllers\Operator\PrediksiIpkController;
use App\Http\Controllers\Operator\ProfilController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:operator'])
    ->prefix('operator')
    ->name('operator.')
    ->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/data-ips', [DataIpsController::class, 'create'])
            ->name('data-ips.create');

        Route::post('/data-ips', [DataIpsController::class, 'store'])
            ->name('data-ips.store');

        Route::get('/prediksi-ipk', [PrediksiIpkController::class, 'create'])
            ->name('prediksi-ipk.create');

        Route::post('/prediksi-ipk', [PrediksiIpkController::class, 'store'])
            ->name('prediksi-ipk.store');

        Route::get('/hasil-prediksi', [HasilPrediksiController::class, 'index'])
            ->name('hasil-prediksi.index');

        Route::get('/hasil-prediksi/{prediksiIpk}', [HasilPrediksiController::class, 'show'])
            ->name('hasil-prediksi.show');

        Route::get('/profil', [ProfilController::class, 'edit'])
            ->name('profil.edit');

        Route::put('/profil', [ProfilController::class, 'update'])
            ->name('profil.update');
    });