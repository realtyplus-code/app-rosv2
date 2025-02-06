<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;


Route::prefix('users')->middleware(['auth:web'])->group(function () {
    Route::get('/', [UserController::class, 'view'])->name('users.view')->middleware('can:list_users');
    Route::get('/list', [UserController::class, 'index'])->name('users.index')->middleware('can:list_users');
    Route::post('/store', [UserController::class, 'store'])->name('users.store')->middleware('can:create_users');
    Route::post('/update/{id}', [UserController::class, 'update'])->name('users.update')->middleware('can:edit_users');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('can:delete_users');

    Route::post('/photo/add/', [UserController::class, 'addPhoto'])->name('users.photo.add');
    Route::post('/photo/delete/', [UserController::class, 'destroyPhoto'])->name('users.photo.destroy');
    Route::get('/role/{roleName}', [UserController::class, 'getUsersByRole'])->name('users.role');
});
