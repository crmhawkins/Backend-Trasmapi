<?php

use App\Http\Controllers\AnuncioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/anuncios', [AnuncioController::class, 'index'])->name('anuncios.index');
Route::get('/anuncios/create', [AnuncioController::class, 'create'])->name('anuncios.create');
Route::post('/anuncios', [AnuncioController::class, 'store'])->name('anuncios.store');
Route::get('/anuncios/{id}/edit', [AnuncioController::class, 'edit'])->name('anuncios.edit');
Route::put('/anuncios/{id}', [AnuncioController::class, 'update'])->name('anuncios.update');


