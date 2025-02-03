<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Provider\ProviderController;

Route::prefix('providers')->middleware(['auth:proveedores,web'])->group(function () {
    Route::get('/', [ProviderController::class, 'view'])->name('providers.view');
    Route::get('/list', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/listByProperty', [ProviderController::class, 'byProperty'])->name('providers.by.property');
    Route::post('/store', [ProviderController::class, 'store'])->name('providers.store');
    Route::get('/byTypeCount', [ProviderController::class, 'byTypeCount'])->name('providers.count');
    Route::post('/update/{id}', [ProviderController::class, 'update'])->name('providers.update');
    Route::delete('/{id}', [ProviderController::class, 'destroy'])->name('providers.destroy');
});
