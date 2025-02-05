<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncidentAction\IncidentActionController;

Route::prefix('occurrences-action')->middleware(['auth:providers,web'])->group(function () {
    Route::get('/', [IncidentActionController::class, 'view'])->name('incidents_action.view')->middleware('can:list_incidents_actions');
    Route::get('/list', [IncidentActionController::class, 'index'])->name('incidents_action.index')->middleware('can:list_incidents_actions');
    Route::post('/store', [IncidentActionController::class, 'store'])->name('incidents_action.store')->middleware('can:create_incidents_actions');
    Route::post('/update/{id}', [IncidentActionController::class, 'update'])->name('incidents_action.update')->middleware('can:edit_incidents_actions');
    Route::delete('/{id}', [IncidentActionController::class, 'destroy'])->name('incidents_action.destroy')->middleware('can:delete_incidents_actions');

    Route::get('/byTypeCount', [IncidentActionController::class, 'byTypeCount'])->name('incidents_action.type.count');
    Route::post('/photo/add/', [IncidentActionController::class, 'addPhoto'])->name('incidents_action.destroy.add');
    Route::post('/photo/delete/', [IncidentActionController::class, 'destroyPhoto'])->name('incidents_action.destroy.photo');
    Route::post('/document/add', [IncidentActionController::class, 'addPdf'])->name('incidents_action.add.pdf');
    Route::post('/document/delete', [IncidentActionController::class, 'destroyPdf'])->name('incidents_action.destroy.pdf');
});
