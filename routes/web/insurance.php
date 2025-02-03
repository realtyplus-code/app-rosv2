<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Insurance\InsuranceController;

Route::prefix('insurances')->middleware(['auth'])->group(function () {
    Route::get('/', [InsuranceController::class, 'view'])->name('insurances.view');
    Route::get('/list', [InsuranceController::class, 'index'])->name('insurances.index');
    Route::post('/store', [InsuranceController::class, 'store'])->name('insurances.store');
    Route::post('/update/{id}', [InsuranceController::class, 'update'])->name('insurances.update');
    Route::delete('/{id}', [InsuranceController::class, 'destroy'])->name('insurances.destroy');
    Route::get('/byTypeCount', [InsuranceController::class, 'byTypeCount'])->name('insurances.type.count');
    Route::post('/document/add', [InsuranceController::class, 'addPdf'])->name('insurances.add.pdf');
    Route::post('/document/delete', [InsuranceController::class, 'destroyPdf'])->name('insurances.destroy.pdf');
});