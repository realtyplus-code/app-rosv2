<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentAction\IncidentActionController;

Route::prefix('occurrences-action')->middleware(['auth'])->group(function () {
    Route::get('/', [IncidentActionController::class, 'view'])->name('incidents_action.view');
    Route::get('/list', [IncidentActionController::class, 'index'])->name('incidents_action.index');
    Route::post('/store', [IncidentActionController::class, 'store'])->name('incidents_action.store');
    Route::post('/update/{id}', [IncidentActionController::class, 'update'])->name('incidents_action.update');
    Route::delete('/{id}', [IncidentActionController::class, 'destroy'])->name('incidents_action.destroy');
    Route::get('/byTypeCount', [IncidentActionController::class, 'byTypeCount'])->name('incidents_action.type.count');
    Route::post('/photo/add/', [IncidentActionController::class, 'addPhoto'])->name('incidents_action.destroy.add');
    Route::post('/photo/delete/', [IncidentActionController::class, 'destroyPhoto'])->name('incidents_action.destroy.photo');
    Route::post('/document/add', [IncidentActionController::class, 'addPdf'])->name('incidents_action.add.pdf');
    Route::post('/document/delete', [IncidentActionController::class, 'destroyPdf'])->name('incidents_action.destroy.pdf');
});
