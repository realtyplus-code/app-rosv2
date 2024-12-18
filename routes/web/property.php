<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Property\PropertyController;

Route::prefix('properties')->middleware(['auth'])->group(function () {
    Route::get('/list', [PropertyController::class, 'index'])->name('properties.index');
    Route::post('/store', [PropertyController::class, 'store'])->name('properties.store');
    Route::post('/update/{id}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/{id}', [PropertyController::class, 'destroy'])->name('properties.destroy');
    Route::post('/photo/add/', [PropertyController::class, 'addPhoto'])->name('properties.destroy.add');
    Route::post('/photo/delete/', [PropertyController::class, 'destroyPhoto'])->name('properties.destroy.photo');
});