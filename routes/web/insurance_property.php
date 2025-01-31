<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InsuranceProperty\InsurancePropertyController;

Route::prefix('insurance-property')->middleware(['auth'])->group(function () {
    Route::post('/store', [InsurancePropertyController::class, 'store'])->name('insurance_property.store');
    Route::delete('/{id}', [InsurancePropertyController::class, 'destroy'])->name('insurance_property.destroy');
});
