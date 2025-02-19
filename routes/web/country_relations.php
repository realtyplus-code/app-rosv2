<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryRelation\CountryRelationController;

Route::prefix('country-relations')->middleware(['auth:providers,web'])->group(function () {
    Route::get('/getRelation/{id}/{code}', [CountryRelationController::class, 'getRelationByIdAndCode'])->name('country.relation.code');
});
