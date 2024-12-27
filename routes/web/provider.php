<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Provider\ProviderController;

Route::prefix('providers')->group(function () {
    Route::get('/', [ProviderController::class, 'view'])->name('providers.view');
    Route::get('/list', [ProviderController::class, 'index'])->name('providers.index');
    Route::post('/store', [ProviderController::class, 'store'])->name('providers.store');
    Route::post('/update/{id}', [ProviderController::class, 'update'])->name('providers.update');
    Route::delete('/{id}', [ProviderController::class, 'destroy'])->name('providers.destroy');
});