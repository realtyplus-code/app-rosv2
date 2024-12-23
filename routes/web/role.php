<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Role\RoleController;

Route::prefix('rols')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/list', [RoleController::class, 'index'])->name('role.index');
});
