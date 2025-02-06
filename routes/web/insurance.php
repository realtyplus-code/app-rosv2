<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Insurance\InsuranceController;

Route::prefix('insurances')->middleware(['auth:web'])->group(function () {
    Route::get('/', [InsuranceController::class, 'view'])->name('insurances.view')->middleware('can:list_insurances');
    Route::get('/list', [InsuranceController::class, 'index'])->name('insurances.index')->middleware('can:list_insurances');
    Route::post('/store', [InsuranceController::class, 'store'])->name('insurances.store')->middleware('can:create_insurances');
    Route::post('/update/{id}', [InsuranceController::class, 'update'])->name('insurances.update')->middleware('can:edit_insurances');
    Route::delete('/{id}', [InsuranceController::class, 'destroy'])->name('insurances.destroy')->middleware('can:delete_insurances');
    // otras funciones
    Route::get('/byTypeCount', [InsuranceController::class, 'byTypeCount'])->name('insurances.type.count');
    Route::post('/document/add', [InsuranceController::class, 'addPdf'])->name('insurances.add.pdf');
    Route::post('/document/delete', [InsuranceController::class, 'destroyPdf'])->name('insurances.destroy.pdf');
});

Route::prefix('insurances/only')->middleware(['auth:web,providers'])->group(function () {
    Route::get('/list', [InsuranceController::class, 'index'])->name('only.insurances.index')->middleware('can:read_insurances');
});