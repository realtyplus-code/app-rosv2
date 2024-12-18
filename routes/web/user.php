<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserController;

Route::prefix('users')->middleware(['auth'])->group(function () {
    Route::get('/list', [UserController::class, 'index'])->name('users.index');
});
