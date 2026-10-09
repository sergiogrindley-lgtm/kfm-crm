<?php

use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Ruta principal directa al CRM (sin contraseña)
Route::get('/', [ClientController::class, 'index'])->name('home');

Route::resource('clients', ClientController::class);
Route::post('clients/{client}/policies', [ClientController::class, 'storePolicy'])->name('clients.policies.store');
Route::get('clients/{client}/cesion/{policy}', [ClientController::class, 'cesionPoliza'])->name('clients.cesion');
Route::get('clients/{client}/geico/{policy}', [ClientController::class, 'geicoForm'])->name('clients.geico');
Route::get('clients/{client}/mandato/{policy}', [ClientController::class, 'mandatoGestoria'])->name('clients.mandato');
Route::get('clients/{client}/carta-verde/{policy}', [ClientController::class, 'cartaVerde'])->name('clients.carta-verde');
Route::match(['get', 'post'], 'gestoria-demo', [ClientController::class, 'gestoriaDemo'])->name('gestoria.demo');
Route::get('api/decode-vin', [ClientController::class, 'decodeVin'])->name('api.decode-vin');
Route::match(['get', 'post'], 'api/calculate-itp', [ClientController::class, 'calculateItp'])->name('api.calculate-itp');
Route::match(['get', 'post'], 'api/translate', [ClientController::class, 'translateText'])->name('api.translate');
Route::match(['get', 'post'], 'api/calculate-pcs-refund', [ClientController::class, 'calculatePcsRefund'])->name('api.calculate-pcs-refund');

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

