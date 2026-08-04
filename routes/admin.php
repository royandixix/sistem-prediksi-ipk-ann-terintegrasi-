<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataIpsController;
use App\Http\Controllers\Admin\DatasetController;
use App\Http\Controllers\Admin\GrafikController;
use App\Http\Controllers\Admin\HasilPrediksiController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\ModelAnnController;
use App\Http\Controllers\Admin\PrediksiIpkController;
use App\Http\Controllers\Admin\ProfilController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {


        Route::get(
            '/dashboard',
            [DashboardController::class, 'index']
        )->name('dashboard');

        Route::resource(
            'mahasiswa',
            MahasiswaController::class
        );

        Route::resource(
            'data-ips',
            DataIpsController::class
        )->parameters([
            'data-ips' => 'dataIps',
        ]);

        Route::get('/dataset', [DatasetController::class, 'index'])
            ->name('dataset.index');

        Route::post('/dataset/sync', [DatasetController::class, 'sync'])
            ->name('dataset.sync');

        Route::get('/dataset/download/{type}', [DatasetController::class, 'download'])
            ->whereIn('type', ['raw', 'processed', 'excluded', 'summary'])
            ->name('dataset.download');

        

        Route::get(
            '/model-ann',
            [ModelAnnController::class, 'index']
        )->name('model-ann.index');

        Route::post(
            '/model-ann/training',
            [ModelAnnController::class, 'train']
        )->name('model-ann.train');

        Route::get(
            '/prediksi-ipk',
            [PrediksiIpkController::class, 'index']
        )->name('prediksi-ipk.index');

        Route::post(
            '/prediksi-ipk',
            [PrediksiIpkController::class, 'store']
        )->name('prediksi-ipk.store');

        Route::get(
            '/hasil-prediksi',
            [HasilPrediksiController::class, 'index']
        )->name('hasil-prediksi.index');

        Route::get(
            '/grafik',
            [GrafikController::class, 'index']
        )->name('grafik.index');

        Route::get(
            '/laporan/export-csv',
            [LaporanController::class, 'exportCsv']
        )->name('laporan.export-csv');

        Route::get(
            '/laporan',
            [LaporanController::class, 'index']
        )->name('laporan.index');

        Route::get(
            '/profil',
            [ProfilController::class, 'edit']
        )->name('profil.edit');

        Route::put(
            '/profil',
            [ProfilController::class, 'update']
        )->name('profil.update');
    });