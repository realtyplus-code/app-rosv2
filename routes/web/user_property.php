<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserProperty\UserPropertyController;

Route::prefix('user-properties')->middleware(['auth'])->group(function () {
    Route::get('/byProperties/{id}', [UserPropertyController::class, 'showByUser'])->name('user.properties.index');
});