<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Insurance\InsuranceController;

Route::prefix('insurances')->middleware(['auth'])->group(function () {
    Route::get('/list', [InsuranceController::class, 'index'])->name('insurances.index');
    Route::post('/store', [InsuranceController::class, 'store'])->name('insurances.store');
    Route::post('/update/{id}', [InsuranceController::class, 'update'])->name('insurances.update');
    Route::delete('/{id}', [InsuranceController::class, 'destroy'])->name('insurances.destroy');
});