<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OperationsController;
use Illuminate\Support\Facades\Route;

// Ruta principal directa al CRM (sin contraseña)
Route::get('/', [ClientController::class, 'index'])->name('home');

Route::resource('clients', ClientController::class);
Route::post('clients/{client}/policies', [ClientController::class, 'storePolicy'])->name('clients.policies.store');
Route::put('clients/{client}/policies/{policy}', [ClientController::class, 'updatePolicy'])->name('clients.policies.update');
Route::post('clients/{client}/policies/{policy}/cancel', [ClientController::class, 'cancelPolicy'])->name('clients.policies.cancel');
Route::post('clients/{client}/policies/{policy}/reactivate', [ClientController::class, 'reactivatePolicy'])->name('clients.policies.reactivate');
Route::delete('clients/{client}/policies/{policy}', [ClientController::class, 'destroyPolicy'])->name('clients.policies.destroy');
Route::get('clients/{client}/cesion/{policy}', [ClientController::class, 'cesionPoliza'])->name('clients.cesion');
Route::get('clients/{client}/geico/{policy}', [ClientController::class, 'geicoForm'])->name('clients.geico');
Route::get('clients/{client}/mandato/{policy}', [ClientController::class, 'mandatoGestoria'])->name('clients.mandato');
Route::get('clients/{client}/carta-verde/{policy}', [ClientController::class, 'cartaVerde'])->name('clients.carta-verde');
Route::match(['get', 'post'], 'gestoria-demo', [ClientController::class, 'gestoriaDemo'])->name('gestoria.demo');
Route::get('api/decode-vin', [ClientController::class, 'decodeVin'])->name('api.decode-vin');
Route::match(['get', 'post'], 'api/calculate-itp', [ClientController::class, 'calculateItp'])->name('api.calculate-itp');
Route::match(['get', 'post'], 'api/translate', [ClientController::class, 'translateText'])->name('api.translate');
Route::match(['get', 'post'], 'api/calculate-pcs-refund', [ClientController::class, 'calculatePcsRefund'])->name('api.calculate-pcs-refund');
Route::get('api/check-duplicate-client', [ClientController::class, 'checkDuplicateClient'])->name('api.check-duplicate-client');
Route::get('api/lookup-policy', [OperationsController::class, 'lookupPolicy'])->name('api.lookup-policy');
Route::get('api/lookup-plate', [OperationsController::class, 'lookupPlate'])->name('api.lookup-plate');
Route::get('operations/extornos/lookup-policy', [OperationsController::class, 'lookupPolicy']);

// =========================================================================
// MÓDULOS OPERATIVOS KFM (DATABASE25 EXCEL MIGRATION)
// =========================================================================
Route::prefix('operations')->name('operations.')->group(function () {
    
    // 1. GESTORÍA DGT & TRANSFERENCIAS
    Route::get('gestoria', [OperationsController::class, 'indexGestoria'])->name('gestoria.index');
    Route::post('gestoria', [OperationsController::class, 'storeGestoria'])->name('gestoria.store');
    Route::put('gestoria/{tramite}', [OperationsController::class, 'updateGestoria'])->name('gestoria.update');
    Route::patch('gestoria/{tramite}/status', [OperationsController::class, 'updateGestoriaStatus'])->name('gestoria.status');
    // Documentos Oficiales Gestoría
    Route::get('gestoria/{tramite}/portada', [OperationsController::class, 'documentoPortada'])->name('gestoria.portada');
    Route::get('gestoria/{tramite}/mandato-comprador', [OperationsController::class, 'documentoMandatoComprador'])->name('gestoria.mandato-comprador');
    Route::get('gestoria/{tramite}/mandato-vendedor', [OperationsController::class, 'documentoMandatoVendedor'])->name('gestoria.mandato-vendedor');
    Route::get('gestoria/{tramite}/contrato-compraventa', [OperationsController::class, 'documentoContratoCompraventa'])->name('gestoria.contrato-compraventa');
    Route::get('gestoria/{tramite}/extravio-vendedor', [OperationsController::class, 'documentoExtravioVendedor'])->name('gestoria.extravio-vendedor');
    Route::get('gestoria/{tramite}/cesion', [OperationsController::class, 'documentoCesion'])->name('gestoria.cesion');
    Route::get('gestoria/{tramite}/embargo', [OperationsController::class, 'documentoEmbargo'])->name('gestoria.embargo');

    // 2. EXTORNOS & TRASLADOS PCS
    Route::get('extornos', [OperationsController::class, 'indexExtornos'])->name('extornos.index');
    Route::post('extornos', [OperationsController::class, 'storeExtorno'])->name('extornos.store');
    Route::patch('extornos/{extorno}', [OperationsController::class, 'updateExtorno'])->name('extornos.update');

    // 3. SIN MATRÍCULA / PLACAS PROVISIONALES
    Route::get('sin-matricula', [OperationsController::class, 'indexSinMatricula'])->name('sin-matricula.index');
    Route::post('sin-matricula', [OperationsController::class, 'storeSinMatricula'])->name('sin-matricula.store');
    Route::patch('sin-matricula/{item}', [OperationsController::class, 'updateSinMatricula'])->name('sin-matricula.update');

    // 4. PÓLIZAS DE EMBARQUE / SHIPPING POV
    Route::get('embarque', [OperationsController::class, 'indexEmbarque'])->name('embarque.index');
    Route::post('embarque', [OperationsController::class, 'storeEmbarque'])->name('embarque.store');
    Route::patch('embarque/{embarque}', [OperationsController::class, 'updateEmbarque'])->name('embarque.update');

    // 5. SINIESTROS & MULTAS ARAG
    Route::get('siniestros', [OperationsController::class, 'indexSiniestros'])->name('siniestros.index');
    Route::get('multas', [OperationsController::class, 'indexMultas'])->name('multas.index');
    Route::post('siniestros', [OperationsController::class, 'storeSiniestro'])->name('siniestros.store');
    Route::patch('siniestros/{siniestro}', [OperationsController::class, 'updateSiniestro'])->name('siniestros.update');

    // 6. PAGOS FRACCIONADOS (FINANCIACIÓN)
    Route::get('pagos', [OperationsController::class, 'indexPagos'])->name('pagos.index');
    Route::post('pagos', [OperationsController::class, 'storePago'])->name('pagos.store');
    Route::patch('pagos/{pago}', [OperationsController::class, 'updatePago'])->name('pagos.update');

    // 7. CESIONES DE PÓLIZA SIN TRANSFERENCIA
    Route::get('cesiones', [OperationsController::class, 'indexCesiones'])->name('cesiones.index');
    Route::post('cesiones', [OperationsController::class, 'storeCesion'])->name('cesiones.store');
    Route::patch('cesiones/{cesion}', [OperationsController::class, 'updateCesion'])->name('cesiones.update');
    Route::get('cesiones/{cesion}/documento', [OperationsController::class, 'documentoCesionPoliza'])->name('cesiones.documento');
    Route::post('cesiones/{cesion}/crear-cliente', [OperationsController::class, 'crearClienteDesdeCesion'])->name('cesiones.crear-cliente');
});

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
