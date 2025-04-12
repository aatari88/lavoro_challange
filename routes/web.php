<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::get('tipo-cambio', function () {
        return Inertia::render('TipoCambio');
    })->name('tipo-cambio');
    Route::get('tipo-cambio/find', [App\Http\Controllers\TipoCambioController::class, 'find'])->name('tipo-cambio.find');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
