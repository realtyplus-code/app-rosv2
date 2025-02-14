<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnumOption\EnumOptionController;

Route::prefix('enums')->middleware(['auth:providers,web'])->group(function () {
    Route::get('/', [EnumOptionController::class, 'view'])->name('enum')->middleware('can:list_enums');
    Route::get('/list', [EnumOptionController::class, 'index'])->name('enum.index')->middleware('can:list_enums');
    Route::post('/store', [EnumOptionController::class, 'store'])->name('enum.store')->middleware('can:create_enums');
    Route::post('/update/{id}', [EnumOptionController::class, 'update'])->name('enum.update')->middleware('can:edit_enums');
    Route::get('/fathers', [EnumOptionController::class, 'listFathers'])->name('enum.fathers')->middleware('can:list_enums');
    Route::delete('/{id}', [EnumOptionController::class, 'destroy'])->name('enum.destroy')->middleware('can:delete_enums');

    Route::get('/option/{name}', [EnumOptionController::class, 'listChildrens'])->name('enum.option');
    Route::get('/get-id/{id}', [EnumOptionController::class, 'getOptionById'])->name('enum.get');
    Route::get('/get-brother/{id}', [EnumOptionController::class, 'getBrotherById'])->name('enum.get.childrens');
    Route::get('/get-brother-code/{id}/{code}', [EnumOptionController::class, 'getBrotherByIdAndCode'])->name('enum.get.code.childrens');
});