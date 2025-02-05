<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Provider\ProviderController;

Route::prefix('providers')->middleware(['auth:providers,web'])->group(function () {
    Route::get('/', [ProviderController::class, 'view'])->name('providers.view')->middleware('can:list_providers');
    Route::get('/list', [ProviderController::class, 'index'])->name('providers.index')->middleware('can:list_providers');
    Route::post('/store', [ProviderController::class, 'store'])->name('providers.store')->middleware('can:create_providers');
    Route::post('/update/{id}', [ProviderController::class, 'update'])->name('providers.update')->middleware('can:edit_providers');
    Route::delete('/{id}', [ProviderController::class, 'destroy'])->name('providers.destroy')->middleware('can:delete_providers');
    // otras funciones
    Route::get('/byTypeCount', [ProviderController::class, 'byTypeCount'])->name('providers.count');
    Route::get('/listByProperty', [ProviderController::class, 'byProperty'])->name('providers.by.property');
});
