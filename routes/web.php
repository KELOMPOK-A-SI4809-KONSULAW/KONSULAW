<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProBonoApplicationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('beranda');

Route::get('/pro-bono', [
    ProBonoApplicationController::class,
    'create',
])->name('pro-bono.create');

Route::post('/pro-bono', [
    ProBonoApplicationController::class,
    'store',
])->name('pro-bono.store');