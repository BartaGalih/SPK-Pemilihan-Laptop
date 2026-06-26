<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\MfepController;
use App\Http\Controllers\SnapshotController;

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index']);

// Manajemen Laptop (CRUD)
Route::resource('laptops', LaptopController::class);

// Manajemen Kriteria (CRUD penuh + soft delete)
Route::resource('criteria', CriteriaController::class)
    ->parameters(['criteria' => 'criterium'])
    ->except(['show']);

// Hasil & Kalkulasi MFEP
Route::get('results',            [MfepController::class, 'index'])->name('results.index');
Route::post('results/calculate', [MfepController::class, 'calculate'])->name('results.calculate');
Route::post('results/snapshot',  [MfepController::class, 'saveSnapshot'])->name('results.snapshot');

// Arsip / Snapshot hasil
Route::get('snapshots',            [SnapshotController::class, 'index'])->name('snapshots.index');
Route::get('snapshots/{snapshot}', [SnapshotController::class, 'show'])->name('snapshots.show');
Route::delete('snapshots/{snapshot}', [SnapshotController::class, 'destroy'])->name('snapshots.destroy');
