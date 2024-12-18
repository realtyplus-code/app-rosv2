<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnumOption\EnumOptionController;

Route::prefix('enums')->middleware(['auth'])->group(function () {
    Route::get('/', [EnumOptionController::class, 'view'])->name('enum');
    Route::get('/list', [EnumOptionController::class, 'index'])->name('enum.index');
    Route::post('/store', [EnumOptionController::class, 'store'])->name('enum.store');
    Route::post('/update/{id}', [EnumOptionController::class, 'update'])->name('enum.update');
    Route::get('/fathers', [EnumOptionController::class, 'listFathers'])->name('enum.fathers');
    Route::delete('/{id}', [EnumOptionController::class, 'destroy'])->name('enum.destroy');
    Route::get('/option/{name}', [EnumOptionController::class, 'listChildrens'])->name('enum.option');
});