<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Ruta principal directa al CRM (sin contraseña)
Route::get('/', [ClientController::class, 'index'])->name('home');

Route::resource('clients', ClientController::class);
Route::post('clients/{client}/policies', [ClientController::class, 'storePolicy'])->name('clients.policies.store');
