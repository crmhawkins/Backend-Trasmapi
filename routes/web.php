<?php

use App\Http\Controllers\AnuncioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\LimpiezaController;
use App\Http\Controllers\PosidoniaController;
use App\Http\Controllers\TortugaController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('anuncios.index');
    }

    return view('auth.login');
});

Auth::routes();

Route::middleware('auth:admin')->group(function () {
    Route::get('/anuncios', [AnuncioController::class, 'index'])->name('anuncios.index');
    Route::get('/anuncios/create', [AnuncioController::class, 'create'])->name('anuncios.create');
    Route::post('/anuncios', [AnuncioController::class, 'store'])->name('anuncios.store');
    Route::get('/anuncios/{id}/edit', [AnuncioController::class, 'edit'])->name('anuncios.edit');
    Route::put('/anuncios/{id}', [AnuncioController::class, 'update'])->name('anuncios.update');
    Route::delete('/anuncios/{id}', [AnuncioController::class, 'destroy'])->name('anuncios.destroy');
    Route::get('/posidonia', [PosidoniaController::class, 'index'])->name('posidonia.index');
    Route::get('/limpieza', [LimpiezaController::class, 'index'])->name('limpieza.index');
    Route::get('/tortuga', [TortugaController::class, 'index'])->name('tortuga.index');

});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
