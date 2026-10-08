<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Ruta principal directa al CRM (sin contraseña)
Route::get('/', [ClientController::class, 'index'])->name('home');

Route::resource('clients', ClientController::class);
Route::post('clients/{client}/policies', [ClientController::class, 'storePolicy'])->name('clients.policies.store');
Route::get('clients/{client}/cesion/{policy}', [ClientController::class, 'cesionPoliza'])->name('clients.cesion');
Route::match(['get', 'post'], 'gestoria-demo', [ClientController::class, 'gestoriaDemo'])->name('gestoria.demo');

// Endpoint de diagnóstico rápido de IP pública y cabeceras
Route::get('ip-check', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'status' => 'ok',
        'ip_detectada' => $request->ip(),
        'x_forwarded_for' => $request->header('x-forwarded-for'),
        'whitelist_activa' => config('kfm.whitelist_enabled'),
        'ips_autorizadas' => config('kfm.allowed_ips'),
    ]);
});

