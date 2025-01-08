<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Incident\IncidentController;

Route::prefix('occurrences')->middleware(['auth'])->group(function () {
    Route::get('/', [IncidentController::class, 'view'])->name('incidents.view');
    Route::get('/kanban', [IncidentController::class, 'viewKanban'])->name('incidents.view_kanban');
    Route::get('/list', [IncidentController::class, 'index'])->name('incidents.index');
    Route::post('/store', [IncidentController::class, 'store'])->name('incidents.store');
    Route::post('/update/{id}', [IncidentController::class, 'update'])->name('incidents.update');
    Route::delete('/{id}', [IncidentController::class, 'destroy'])->name('incidents.destroy');
    Route::get('/byTypeCount', [IncidentController::class, 'byTypeCount'])->name('incidents.type.count');
    Route::post('/photo/add/', [IncidentController::class, 'addPhoto'])->name('incidents.destroy.add');
    Route::post('/photo/delete/', [IncidentController::class, 'destroyPhoto'])->name('incidents.destroy.photo');
    Route::post('/document/add', [IncidentController::class, 'addPdf'])->name('incidents.add.pdf');
    Route::post('/document/delete', [IncidentController::class, 'destroyPdf'])->name('incidents.destroy.pdf');
});
