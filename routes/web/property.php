<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Property\PropertyController;

Route::prefix('properties')->middleware(['auth:web'])->group(function () {
    Route::get('/', [PropertyController::class, 'view'])->name('properties.view')->middleware('can:list_properties');
    Route::get('/list', [PropertyController::class, 'index'])->name('properties.index')->middleware('can:list_properties');
    Route::post('/store', [PropertyController::class, 'store'])->name('properties.store')->middleware('can:create_properties');
    Route::post('/update/{id}', [PropertyController::class, 'update'])->name('properties.update')->middleware('can:edit_properties');
    Route::delete('/{id}', [PropertyController::class, 'destroy'])->name('properties.destroy')->middleware('can:delete_properties');
    Route::get('/export', [PropertyController::class, 'exportExcel'])->name('properties.export')->middleware('can:export_properties');
    Route::get('/export-pdf', [PropertyController::class, 'exportPdf'])->name('properties.export.pdf')->middleware('can:export_properties');
    // otras funciones
    Route::get('/byTypeCount', [PropertyController::class, 'byTypeCount'])->name('properties.type.count');
    Route::post('/photo/add/', [PropertyController::class, 'addPhoto'])->name('properties.destroy.add');
    Route::post('/photo/delete/', [PropertyController::class, 'destroyPhoto'])->name('properties.destroy.photo');
    Route::post('/document/add', [PropertyController::class, 'addPdf'])->name('properties.add.pdf');
    Route::post('/document/delete', [PropertyController::class, 'destroyPdf'])->name('properties.destroy.pdf');
});


Route::prefix('properties/providers')->middleware(['auth:providers'])->group(function () {
    Route::get('/list', [PropertyController::class, 'index'])->name('providers.properties.index')->middleware('can:read_properties');
});
