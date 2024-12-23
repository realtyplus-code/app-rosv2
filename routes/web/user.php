<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;


Route::prefix('users')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'view'])->name('users.view');
    Route::get('/list', [UserController::class, 'index'])->name('users.index');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/photo/add/', [UserController::class, 'addPhoto'])->name('users.photo.add');
    Route::post('/photo/delete/', [UserController::class, 'destroyPhoto'])->name('users.photo.destroy');
});
