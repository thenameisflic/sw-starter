<?php

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('api')->group(function () {
    Route::get('query', [APIController::class, 'query'])->name('api.query');
});

Route::get('/film/{id}', [APIController::class, 'filmDetails'])->name('film.details');

Route::get('/people/{id}', [APIController::class, 'peopleDetails'])->name('people.details');

Route::get('/statistics', [APIController::class, 'statistics']);
