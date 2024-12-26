<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incident\IncidentController;

Route::prefix('incidents')->group(function () {
    Route::get('/', [IncidentController::class, 'view'])->name('incidents.view');
    Route::get('/list', [IncidentController::class, 'index'])->name('incidents.index');
    Route::post('/store', [IncidentController::class, 'store'])->name('incidents.store');
    Route::post('/update/{id}', [IncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
});
