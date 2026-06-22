<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaptopController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\MfepController;

Route::get('/', function () {
    return redirect()->route('laptops.index');
});

// Manajemen Laptop (CRUD)
Route::resource('laptops', LaptopController::class);

// Manajemen Kriteria (index + edit + update saja)
Route::get('criteria',              [CriteriaController::class, 'index'])->name('criteria.index');
Route::get('criteria/{criterium}/edit',   [CriteriaController::class, 'edit'])->name('criteria.edit');
Route::put('criteria/{criterium}',        [CriteriaController::class, 'update'])->name('criteria.update');

// Hasil & Kalkulasi MFEP
Route::get('results',              [MfepController::class, 'index'])->name('results.index');
Route::post('results/calculate',   [MfepController::class, 'calculate'])->name('results.calculate');
