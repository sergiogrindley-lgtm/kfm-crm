<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use App\Models\GestoriaTramite;
use App\Models\Extorno;
use App\Models\SinMatricula;
use App\Models\PolizaEmbarque;
use App\Models\Siniestro;
use App\Models\PagoFraccionado;
use Illuminate\Http\Request;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OperationsController;

$totalTests = 0;
$passedTests = 0;
$failedTests = [];

function auditAssert(bool $condition, string $testName, string $details = '')
{
    global $totalTests, $passedTests, $failedTests;
    $totalTests++;
    if ($condition) {
        $passedTests++;
        echo "  [PASS] {$testName}\n";
    } else {
        $failedTests[] = "{$testName}: {$details}";
        echo "  [FAIL] {$testName} - {$details}\n";
    }
}

echo "========================================================================\n";
echo "🔎 AUDITORÍA INTEGRAL DE FUNCIONES - KFM CRM & OPERACIONES\n";
echo "========================================================================\n\n";

$clientCtrl = new ClientController();
$opsCtrl = new OperationsController();

// -------------------------------------------------------------------------
// 1. INTEGRIDAD DE BASE DE DATOS Y MODELOS
// -------------------------------------------------------------------------
echo "1. AUDITORÍA DE MODELOS Y RELACIONES...\n";
$sedes = Sede::all();
auditAssert($sedes->count() >= 2, "Sedes KFM configuradas (Central y NEX)", "Total: " . $sedes->count());

$clientsCount = Client::count();
auditAssert($clientsCount > 0, "Existen clientes en base de datos", "Total: {$clientsCount}");

$policiesCount = Policy::count();
auditAssert($policiesCount > 0, "Existen pólizas en base de datos", "Total: {$policiesCount}");

// Verificar que ninguna póliza sea huérfana
$orphanPolicies = Policy::whereNotIn('client_id', Client::pluck('id'))->count();
auditAssert($orphanPolicies === 0, "Cero pólizas huérfanas en el sistema", "Huérfanas: {$orphanPolicies}");

// -------------------------------------------------------------------------
// 2. BUSCADOR Y LISTADO PRINCIPAL (HOME)
// -------------------------------------------------------------------------
echo "\n2. AUDITORÍA DEL BUSCADOR PRINCIPAL (HOME / INDEX)...\n";
$reqHome = Request::create('/', 'GET');
$respHome = $clientCtrl->index($reqHome);
auditAssert($respHome->name() === 'clients.index', "Ruta Home renderiza vista clients.index");
$clientsData = $respHome->getData()['clients'];
auditAssert($clientsData->count() <= 10, "Paginación principal limitada a 10 clientes para navegación ágil", "Elementos por página: " . $clientsData->count());

// Búsqueda por matrícula
$reqSearchPlate = Request::create('/', 'GET', ['q' => '0680 JCL']);
$respSearchPlate = $clientCtrl->index($reqSearchPlate);
$searchCount = $respSearchPlate->getData()['clients']->total();
auditAssert($searchCount >= 1, "Buscador localiza correctamente por matrícula (0680 JCL)", "Encontrados: {$searchCount}");

// Búsqueda por Agente
$reqSearchAgente = Request::create('/', 'GET', ['agente' => 'Kerry']);
$respSearchAgente = $clientCtrl->index($reqSearchAgente);
auditAssert($respSearchAgente->getData()['clients']->total() >= 1, "Filtro por Agente operativo (Kerry)");

// -------------------------------------------------------------------------
// 3. CICLO DE ALTA DE CLIENTE (CREATE / STORE / DUPLICADOS / FANTASMAS)
// -------------------------------------------------------------------------
echo "\n3. AUDITORÍA DEL PROCESO DE ALTA DE CLIENTE...\n";

// 3.1 Vista create
$respCreateView = $clientCtrl->create();
auditAssert($respCreateView->name() === 'clients.create', "Ruta clients.create renderiza formulario");

// 3.2 Alta sin póliza (Debe crear exactamente 0 pólizas)
$uniqueDoc = 'AUDIT-' . time();
$reqStoreNoPol = Request::create('/clients', 'POST', [
    'sede_id' => 2,
    'agente' => 'Kerry',
    'nombre' => 'JOHN AUDIT',
    'apellido' => 'DOE AUDIT',
    'doc_identidad' => $uniqueDoc,
    'crear_poliza' => '0',
    'tipo_vivienda' => '',
]);
$clientCtrl->store($reqStoreNoPol);
$newClient = Client::where('doc_identidad', $uniqueDoc)->first();
auditAssert($newClient !== null, "Cliente creado correctamente en BD");
$pCount = $newClient ? $newClient->policies()->count() : -1;
auditAssert($pCount === 0, "Prevención de pólizas fantasma: Creadas 0 pólizas al dejar contrato sin marcar", "Pólizas: {$pCount}");

// 3.3 Detección de duplicado por DNI/NIE
$reqStoreDup = Request::create('/clients', 'POST', [
    'sede_id' => 2,
    'agente' => 'Kerry',
    'nombre' => 'JOHN AUDIT',
    'apellido' => 'DOE AUDIT',
    'doc_identidad' => $uniqueDoc,
]);
$session = app('session.store');
$reqStoreDup->setLaravelSession($session);
$clientCtrl->store($reqStoreDup);
$dupData = $session->get('error_duplicado');
auditAssert(is_array($dupData), "Detección de duplicado por DNI/NIE bloquea creación accidental y activa alerta");
auditAssert(($dupData['cliente_id'] ?? null) === $newClient->id, "Alerta de duplicado enlaza exactamente con el cliente existente");

// 3.4 Forzar creación de duplicado si es intencional
$reqStoreDupForced = Request::create('/clients', 'POST', [
    'sede_id' => 2,
    'agente' => 'Kerry',
    'nombre' => 'JOHN AUDIT 2',
    'apellido' => 'DOE AUDIT',
    'doc_identidad' => $uniqueDoc . '-FORCED',
    'forzar_duplicado' => '1',
]);
$clientCtrl->store($reqStoreDupForced);
$forcedClient = Client::where('doc_identidad', $uniqueDoc . '-FORCED')->first();
auditAssert($forcedClient !== null, "Permite forzar creación de duplicado con flag forzar_duplicado=1");
if ($forcedClient) $forcedClient->delete();

// -------------------------------------------------------------------------
// 4. EDICIÓN DE CLIENTE (SHOW / EDIT / UPDATE)
// -------------------------------------------------------------------------
echo "\n4. AUDITORÍA DE EDICIÓN DE CLIENTE...\n";
$respShow = $clientCtrl->show($newClient);
auditAssert($respShow->name() === 'clients.show', "Ficha de cliente clients.show renderiza correctamente");

$respEdit = $clientCtrl->edit($newClient);
auditAssert($respEdit->name() === 'clients.edit', "Vista dedicada clients.edit operativa");

// Actualizar cliente
$reqUpdate = Request::create("/clients/{$newClient->id}", 'PUT', [
    'sede_id' => 1,
    'agente' => 'Conchi',
    'nombre' => 'JOHN AUDIT UPDATED',
    'apellido' => 'DOE AUDIT',
    'telefono_movil' => '654321098',
    'direccion_local' => 'C/ Triunfo 7, Rota',
    'observaciones' => 'Actualizado durante auditoria integral',
]);
$clientCtrl->update($reqUpdate, $newClient);
$newClient->refresh();
auditAssert($newClient->agente === 'Conchi' && $newClient->sede_id === 1 && $newClient->telefono_movil === '654321098', "Actualización de cliente persiste todos los datos modificados (agente, sede, teléfono)");

// -------------------------------------------------------------------------
// 5. GESTIÓN COMPLETA DE PÓLIZAS (STORE / UPDATE / CANCEL / REACTIVATE)
// -------------------------------------------------------------------------
echo "\n5. AUDITORÍA DEL CICLO DE PÓLIZAS (ALTA, EDICIÓN, BAJA, REACTIVACIÓN)...\n";

// 5.1 Alta de Póliza Vehículo
$reqStorePol = Request::create("/clients/{$newClient->id}/policies", 'POST', [
    'ramo' => 'Vehiculo',
    'codigo_aseguradora' => '81',
    'marca' => 'FORD',
    'modelo' => 'MUSTANG GT',
    'matricula' => '9988XYZ',
    'vin' => '1FA6P8CF5H5123456',
    'prima' => '540,00 €',
    'tipo_facturacion' => 'Anual',
    'balance' => 'Paid Full',
    'liquidacion' => 'Pendiente',
    'estado' => 'Activo',
]);
$clientCtrl->storePolicy($reqStorePol, $newClient);
$policy = $newClient->policies()->first();
auditAssert($policy !== null, "Alta de nueva póliza asociada al cliente OK");
auditAssert($policy->tipo_vivienda === null, "Aislamiento de ramo: Póliza de vehículo NO hereda tipo de vivienda");
auditAssert($policy->numero_poliza === 'Pendiente', "Número de póliza por defecto es 'Pendiente' (sin números timestamp feos)");

// 5.2 Edición de Póliza (Asignar número definitivo y liquidar con fecha)
$reqUpdatePol = Request::create("/clients/{$newClient->id}/policies/{$policy->id}", 'PUT', [
    'ramo' => 'Vehiculo',
    'codigo_aseguradora' => '81',
    'numero_poliza' => 'POL-2026-999',
    'recibo_numero' => 'REC-001',
    'marca' => 'FORD',
    'modelo' => 'MUSTANG GT 5.0',
    'matricula' => '9988XYZ',
    'vin' => '1FA6P8CF5H5123456',
    'prima' => '550,00 €',
    'tipo_facturacion' => 'Anual',
    'balance' => 'Paid Full',
    'liquidacion' => 'Liquidado',
    'fecha_liquidacion' => '28/10/2026',
    'fecha_pago' => '28/10/2026',
    'fecha_vencimiento' => '28/10/2027',
    'estado' => 'Activo',
]);
$clientCtrl->updatePolicy($reqUpdatePol, $newClient, $policy);
$policy->refresh();
auditAssert($policy->numero_poliza === 'POL-2026-999', "Edición de póliza: Número definitivo actualizado correctamente");
auditAssert($policy->liquidacion === 'Liquidado' && $policy->fecha_liquidacion === '28/10/2026', "Edición de póliza: Estado de liquidación y fecha de liquidación actualizados");

// 5.3 Cancelación / Baja de Póliza
$reqCancelPol = Request::create("/clients/{$newClient->id}/policies/{$policy->id}/cancel", 'POST', [
    'motivo' => 'Órdenes de Traslado PCS',
    'fecha_baja' => '28/10/2026',
    'notas' => 'Militar entrega vehículo en VPC Rota',
]);
$clientCtrl->cancelPolicy($reqCancelPol, $newClient, $policy);
$policy->refresh();
auditAssert($policy->estado === 'Cancelado', "Baja de póliza: Pasa a estado Cancelado preservando registro");

// 5.4 Reactivación de Póliza
$clientCtrl->reactivatePolicy($newClient, $policy);
$policy->refresh();
auditAssert($policy->estado === 'Activo', "Reactivación de póliza: Vuelve a estado Activo limpiamente");

// -------------------------------------------------------------------------
// 6. GENERADORES DE DOCUMENTOS OFICIALES Y FORMULARIOS
// -------------------------------------------------------------------------
echo "\n6. AUDITORÍA DE GENERACIÓN DE DOCUMENTOS OFICIALES...\n";

// 6.1 Formulario GEICO Military
$respGeico = $clientCtrl->geicoForm($newClient, $policy);
auditAssert($respGeico->name() === 'clients.geico', "Formulario GEICO Military renderiza sin errores");

// 6.2 Mandato DGT Colegiado
$respMandato = $clientCtrl->mandatoGestoria($newClient, $policy);
auditAssert($respMandato->name() === 'clients.mandato', "Mandato Oficial Colegiado DGT renderiza sin errores");

// 6.3 Carta Verde (CIS Green Card)
$respCartaVerde = $clientCtrl->cartaVerde($newClient, $policy);
auditAssert($respCartaVerde->name() === 'clients.carta-verde', "Carta Verde Internacional (CIS) renderiza sin errores");

// 6.4 Hoja de Cesión Oficial
$respCesion = $clientCtrl->cesionPoliza($newClient, $policy);
auditAssert($respCesion->name() === 'clients.cesion', "Hoja de Cesión Oficial de Vehículo renderiza sin errores");

// 6.5 Gestoría Demo
$respGestoriaDemo = $clientCtrl->gestoriaDemo(Request::create('/gestoria-demo', 'GET'));
auditAssert($respGestoriaDemo->name() === 'gestoria.demo', "Panel interactivo de Gestoría Demo operativo");

// -------------------------------------------------------------------------
// 7. HERRAMIENTAS Y APIS (VIN, ITP, PCS REFUND, TRADUCCIÓN)
// -------------------------------------------------------------------------
echo "\n7. AUDITORÍA DE APIS AUXILIARES Y HERRAMIENTAS...\n";

// 7.1 VIN Decoder
$reqVin = Request::create('/api/decode-vin', 'GET', ['vin' => '1FA6P8CF5H5123456']);
$respVin = $clientCtrl->decodeVin($reqVin);
$vinData = json_decode($respVin->getContent(), true);
auditAssert(isset($vinData['success']) && $vinData['success'] === true, "API Decodificador VIN operativo", "Status: " . ($vinData['success'] ? 'OK' : 'Error'));

// 7.2 Calculadora ITP Junta de Andalucía (Modelo 620)
$reqItp = Request::create('/api/calculate-itp', 'GET', ['ano' => 2018, 'valor_fiscal' => 15000]);
$respItp = $clientCtrl->calculateItp($reqItp);
$itpData = json_decode($respItp->getContent(), true);
auditAssert(isset($itpData['cuota_itp']) && $itpData['cuota_itp'] > 0, "API Calculadora Tasas e ITP Junta de Andalucía operativa", "Cuota calculada: " . ($itpData['cuota_itp'] ?? 0));

// 7.3 Calculadora Reembolso de Prima PCS
$reqPcs = Request::create('/api/calculate-pcs-refund', 'POST', ['prima' => 600, 'dias_consumidos' => 120]);
$respPcs = $clientCtrl->calculatePcsRefund($reqPcs);
$pcsData = json_decode($respPcs->getContent(), true);
auditAssert(isset($pcsData['reembolso_estimado']), "API Cálculo Reembolso Prima PCS operativa", "Reembolso calculado: " . ($pcsData['reembolso_estimado'] ?? 0));

// 7.4 Endpoint de Traducción Instantánea
$reqTrans = Request::create('/api/translate', 'POST', ['text' => 'Military vehicle insurance policy', 'target' => 'es']);
$respTrans = $clientCtrl->translateText($reqTrans);
auditAssert($respTrans->getStatusCode() === 200, "API Traductor Bilingüe operativa");

// -------------------------------------------------------------------------
// 8. MÓDULOS OPERATIVOS DATABASE25 (GESTORÍA, EXTORNOS, SIN MATRÍCULA...)
// -------------------------------------------------------------------------
echo "\n8. AUDITORÍA DE MÓDULOS OPERATIVOS KFM (DATABASE25)...\n";

// 8.1 Gestoría DGT & Transferencias
$respGestIndex = $opsCtrl->indexGestoria(Request::create('/operations/gestoria', 'GET'));
auditAssert($respGestIndex->name() === 'operations.gestoria.index', "Módulo Gestoría DGT: Listado y KPIs operativos");

// 8.2 Documentos de Gestoría
$tramitePrueba = GestoriaTramite::first();
if ($tramitePrueba) {
    $respDocPortada = $opsCtrl->documentoPortada($tramitePrueba);
    auditAssert($respDocPortada->name() === 'operations.gestoria.docs.portada', "Gestoría Doc: Portada de Expediente operativa");

    $respDocMandatoComprador = $opsCtrl->documentoMandatoComprador($tramitePrueba);
    auditAssert($respDocMandatoComprador->name() === 'operations.gestoria.docs.mandato-comprador', "Gestoría Doc: Mandato Colegiado Comprador operativo");

    $respDocMandatoVendedor = $opsCtrl->documentoMandatoVendedor($tramitePrueba);
    auditAssert($respDocMandatoVendedor->name() === 'operations.gestoria.docs.mandato-vendedor', "Gestoría Doc: Mandato Colegiado Vendedor operativo");

    $respDocCompraventa = $opsCtrl->documentoContratoCompraventa($tramitePrueba);
    auditAssert($respDocCompraventa->name() === 'operations.gestoria.docs.contrato-compraventa', "Gestoría Doc: Contrato de Compraventa bilingüe operativo");

    $respDocCesion = $opsCtrl->documentoCesion($tramitePrueba);
    auditAssert($respDocCesion->name() === 'operations.gestoria.docs.cesion', "Gestoría Doc: Hoja de Cesión operativa");
} else {
    echo "  [INFO] Sin trámites previos de gestoría en BD local para probar docs\n";
}

// 8.3 Extornos PCS
$respExtornos = $opsCtrl->indexExtornos(Request::create('/operations/extornos', 'GET'));
auditAssert($respExtornos->name() === 'operations.extornos.index', "Módulo Extornos PCS: Listado operativo");

// 8.4 Sin Matrícula / Provisionales
$respSinMat = $opsCtrl->indexSinMatricula(Request::create('/operations/sin-matricula', 'GET'));
auditAssert($respSinMat->name() === 'operations.sin-matricula.index', "Módulo Vehículos Sin Matrícula (Provisionales) operativo");

// 8.5 Pólizas de Embarque
$respEmbarque = $opsCtrl->indexEmbarque(Request::create('/operations/embarque', 'GET'));
auditAssert($respEmbarque->name() === 'operations.embarque.index', "Módulo Pólizas de Embarque / Shipping POV operativo");

// 8.6 Siniestros & Multas
$respSiniestros = $opsCtrl->indexSiniestros(Request::create('/operations/siniestros', 'GET'));
auditAssert($respSiniestros->name() === 'operations.siniestros.index', "Módulo Siniestros y Multas ARAG operativo");

// 8.7 Pagos Fraccionados
$respPagos = $opsCtrl->indexPagos(Request::create('/operations/pagos', 'GET'));
auditAssert($respPagos->name() === 'operations.pagos.index', "Módulo Pagos Fraccionados operativo");

// -------------------------------------------------------------------------
// 9. LIMPIEZA DE DATOS DE AUDITORÍA
// -------------------------------------------------------------------------
echo "\n9. LIMPIEZA DE DATOS DE AUDITORÍA...\n";
if ($policy) $policy->delete();
if ($newClient) $newClient->delete();
echo "  [OK] Registros temporales eliminados sin dejar rastro.\n";

// -------------------------------------------------------------------------
// RESUMEN FINAL
// -------------------------------------------------------------------------
echo "\n========================================================================\n";
echo "📊 RESUMEN FINAL DE LA AUDITORÍA INTERNA:\n";
echo "   Total de Pruebas Ejecutadas: {$totalTests}\n";
echo "   Pruebas Exitosas: {$passedTests} (" . round(($passedTests/$totalTests)*100, 1) . "%)\n";
echo "   Pruebas Fallidas: " . count($failedTests) . "\n";
echo "========================================================================\n";

if (count($failedTests) > 0) {
    echo "\n⚠️ DETALLE DE FALLOS:\n";
    foreach ($failedTests as $f) {
        echo " - {$f}\n";
    }
    exit(1);
} else {
    echo "\n✨ ¡TODAS LAS FUNCIONES Y MÓDULOS DE LA APLICACIÓN ESTÁN 100% OPERATIVOS! ✨\n";
    exit(0);
}
