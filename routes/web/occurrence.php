<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incident\IncidentController;

Route::prefix('occurrences')->middleware(['auth:providers,web'])->group(function () {
    Route::get('/', [IncidentController::class, 'view'])->name('incidents.view')->middleware('can:list_incidents');
    Route::get('/kanban', [IncidentController::class, 'viewKanban'])->name('incidents.view_kanban')->middleware('can:list_incidents');
    Route::get('/list', [IncidentController::class, 'index'])->name('incidents.index')->middleware('can:list_incidents');
    Route::post('/store', [IncidentController::class, 'store'])->name('incidents.store')->middleware('can:create_incidents');
    Route::post('/update/{id}', [IncidentController::class, 'update'])->name('incidents.update')->middleware('can:edit_incidents');
    Route::delete('/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy')->middleware('can:delete_incidents');
    // otras funciones
    Route::get('/byTypeCount', [IncidentController::class, 'byTypeCount'])->name('incidents.type.count');
    Route::post('/photo/add/', [IncidentController::class, 'addPhoto'])->name('incidents.destroy.add');
    Route::post('/photo/delete/', [IncidentController::class, 'destroyPhoto'])->name('incidents.destroy.photo');
    Route::post('/document/add', [IncidentController::class, 'addPdf'])->name('incidents.add.pdf');
    Route::post('/document/delete', [IncidentController::class, 'destroyPdf'])->name('incidents.destroy.pdf');
});
