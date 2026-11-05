<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Policy;
use App\Models\Sede;
use App\Models\GestoriaTramite;
use App\Models\Extorno;
use App\Models\SinMatricula;
use App\Models\PolizaEmbarque;
use App\Models\Siniestro;
use App\Models\PagoFraccionado;
use App\Models\CesionPoliza;
use Illuminate\Http\Request;

class OperationsController extends Controller
{
    /**
     * =========================================================================
     * 1. GESTORÍA DGT & TRANSFERENCIAS DE VEHÍCULOS
     * =========================================================================
     */
    public function indexGestoria(Request $request)
    {
        $query = GestoriaTramite::with(['client', 'policy', 'sede'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('matricula', 'like', "%{$search}%")
                  ->orWhere('vin', 'like', "%{$search}%")
                  ->orWhere('buyer_nombre', 'like', "%{$search}%")
                  ->orWhere('buyer_dni', 'like', "%{$search}%")
                  ->orWhere('seller_nombre', 'like', "%{$search}%")
                  ->orWhere('seller_dni', 'like', "%{$search}%")
                  ->orWhere('marca', 'like', "%{$search}%")
                  ->orWhere('modelo', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            if ($estado === 'CESION_POLIZA') {
                $query->where(function ($q) {
                    $q->where('estado', 'CESION_POLIZA')
                      ->orWhere('tiene_cesion', true)
                      ->orWhere('tipo', 'like', '%Cesión%');
                });
            } elseif ($estado === 'PENDIENTE_DOC') {
                $query->whereIn('estado', ['PENDIENTE_DOC', 'DOCUMENTOS']);
            } elseif ($estado === 'ENVIADO_GESTORIA') {
                $query->whereIn('estado', ['ENVIADO_GESTORIA', 'OUT_GESTORIA']);
            } elseif (in_array($estado, ['PERMISO_RECIBIDO', 'TERMINADAS', 'TERMINADA'])) {
                $query->whereIn('estado', ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'LIQUIDADO', 'RECOGIDO']);
            } else {
                $query->where('estado', $estado);
            }
        }

        if ($tipo = $request->input('tipo')) {
            if ($tipo === 'Cesión de póliza' || $tipo === 'cesion') {
                $query->where(function ($q) {
                    $q->where('tiene_cesion', true)
                      ->orWhere('estado', 'CESION_POLIZA')
                      ->orWhere('tipo', 'like', '%Cesión%');
                });
            } elseif ($tipo === 'Transferencia' || $tipo === 'Transferencias') {
                $query->where(function ($q) {
                    $q->where('tipo', 'like', '%Transferencia%')
                      ->orWhere('tipo', 'like', '%Cesión%')
                      ->orWhere('tiene_cesion', true)
                      ->orWhereNull('tipo');
                });
            } else {
                $query->where('tipo', $tipo);
            }
        }

        if ($sedeId = $request->input('sede_id')) {
            $query->where('sede_id', $sedeId);
        }

        if ($agente = $request->input('agente')) {
            $query->where('agente', $agente);
        }

        $tramites = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => GestoriaTramite::count(),
            'preparado' => GestoriaTramite::where('estado', 'PREPARADO')->count(),
            'pendiente_doc' => GestoriaTramite::whereIn('estado', ['PENDIENTE_DOC', 'DOCUMENTOS'])->count(),
            'enviado_gestoria' => GestoriaTramite::whereIn('estado', ['ENVIADO_GESTORIA', 'OUT_GESTORIA'])->count(),
            'permiso_recibido' => GestoriaTramite::whereIn('estado', ['PERMISO_RECIBIDO', 'BACK_AVISADO', 'LIQUIDADO', 'RECOGIDO'])->count(),
            'cancelado' => GestoriaTramite::where('estado', 'CANCELADO')->count(),
            'cesion_poliza' => GestoriaTramite::where(function ($q) {
                $q->where('estado', 'CESION_POLIZA')
                  ->orWhere('tiene_cesion', true)
                  ->orWhere('tipo', 'like', '%Cesión%');
            })->count(),
            'transferencias' => GestoriaTramite::where(function ($q) {
                $q->where('tipo', 'like', '%Transferencia%')
                  ->orWhere('tipo', 'like', '%Cesión%')
                  ->orWhere('tiene_cesion', true)
                  ->orWhereNull('tipo');
            })->count(),
        ];

        $sedes = Sede::all();

        return view('operations.gestoria.index', compact('tramites', 'stats', 'sedes'));
    }

    public function storeGestoria(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:100',
            'agente' => 'nullable|string|max:50',
            'sede_id' => 'nullable|exists:sedes,id',
            'matricula' => 'required|string|max:50',
            'vin' => 'nullable|string|max:50',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'fecha_tramite' => 'nullable|string|max:50',
            'precio_venta' => 'nullable|string|max:50',
            'buyer_nombre' => 'required|string|max:150',
            'buyer_dni' => 'nullable|string|max:50',
            'buyer_fa' => 'nullable|string|max:50',
            'buyer_dob' => 'nullable|string|max:50',
            'buyer_email' => 'nullable|string|max:100',
            'buyer_telefono' => 'nullable|string|max:50',
            'buyer_direccion' => 'nullable|string|max:255',
            'buyer_cp' => 'nullable|string|max:20',
            'seller_nombre' => 'required|string|max:150',
            'seller_dni' => 'nullable|string|max:50',
            'seller_fa' => 'nullable|string|max:50',
            'seller_direccion' => 'nullable|string|max:255',
            'seller_cp' => 'nullable|string|max:20',
            'seller_contacto' => 'nullable|string|max:100',
            'precio_tramite' => 'nullable|string|max:50',
            'itp_cuota' => 'nullable|string|max:50',
            'recibo_numero' => 'nullable|string|max:50',
            'estado' => 'nullable|string|max:50',
            'tiene_cesion' => 'nullable|boolean',
            'numero_poliza' => 'nullable|string|max:100',
            'ssn' => 'nullable|string|max:50',
            'box_ship' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $validated['matricula'] = strtoupper(trim($validated['matricula']));
        $validated['vin'] = !empty($validated['vin']) ? strtoupper(trim($validated['vin'])) : null;
        $validated['buyer_nombre'] = strtoupper(trim($validated['buyer_nombre']));
        $validated['buyer_dni'] = !empty($validated['buyer_dni']) ? strtoupper(trim($validated['buyer_dni'])) : null;
        $validated['seller_nombre'] = strtoupper(trim($validated['seller_nombre']));
        $validated['seller_dni'] = !empty($validated['seller_dni']) ? strtoupper(trim($validated['seller_dni'])) : null;
        $validated['fecha_tramite'] = !empty($validated['fecha_tramite']) ? trim($validated['fecha_tramite']) : date('d/m/Y');
        $validated['estado'] = $validated['estado'] ?? 'PREPARADO';
        $validated['tiene_cesion'] = $request->has('tiene_cesion') || $request->input('tipo') === 'Cesión de póliza';
        $validated['numero_poliza'] = !empty($validated['numero_poliza']) ? strtoupper(trim($validated['numero_poliza'])) : null;

        // Documentos checklist por defecto
        $validated['documentos_checklist'] = [
            'doc_comprador' => true,
            'doc_vendedor' => true,
            'permiso_circulacion' => true,
            'ficha_itv' => true,
            'contrato_firmado' => true,
            'mandato_comprador' => true,
            'mandato_vendedor' => true,
            'tasas_dgt' => true,
        ];

        $tramite = GestoriaTramite::create($validated);

        return redirect()->route('operations.gestoria.index')
            ->with('success', "Expediente para {$tramite->matricula} ({$tramite->buyer_nombre}) registrado correctamente.");
    }

    public function updateGestoria(Request $request, GestoriaTramite $tramite)
    {
        $validated = $request->validate([
            'tipo' => 'required|string|max:100',
            'agente' => 'nullable|string|max:50',
            'sede_id' => 'nullable|exists:sedes,id',
            'matricula' => 'required|string|max:50',
            'vin' => 'nullable|string|max:50',
            'marca' => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'fecha_tramite' => 'nullable|string|max:50',
            'precio_venta' => 'nullable|string|max:50',
            'buyer_nombre' => 'required|string|max:150',
            'buyer_dni' => 'nullable|string|max:50',
            'buyer_fa' => 'nullable|string|max:50',
            'buyer_dob' => 'nullable|string|max:50',
            'buyer_email' => 'nullable|string|max:100',
            'buyer_telefono' => 'nullable|string|max:50',
            'buyer_direccion' => 'nullable|string|max:255',
            'buyer_cp' => 'nullable|string|max:20',
            'seller_nombre' => 'required|string|max:150',
            'seller_dni' => 'nullable|string|max:50',
            'seller_fa' => 'nullable|string|max:50',
            'seller_direccion' => 'nullable|string|max:255',
            'seller_cp' => 'nullable|string|max:20',
            'seller_contacto' => 'nullable|string|max:100',
            'precio_tramite' => 'nullable|string|max:50',
            'itp_cuota' => 'nullable|string|max:50',
            'recibo_numero' => 'nullable|string|max:50',
            'estado' => 'required|string|max:50',
            'tiene_cesion' => 'nullable|boolean',
            'numero_poliza' => 'nullable|string|max:100',
            'ssn' => 'nullable|string|max:50',
            'box_ship' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $validated['matricula'] = strtoupper(trim($validated['matricula']));
        $validated['vin'] = !empty($validated['vin']) ? strtoupper(trim($validated['vin'])) : null;
        $validated['buyer_nombre'] = strtoupper(trim($validated['buyer_nombre']));
        $validated['buyer_dni'] = !empty($validated['buyer_dni']) ? strtoupper(trim($validated['buyer_dni'])) : null;
        $validated['seller_nombre'] = strtoupper(trim($validated['seller_nombre']));
        $validated['seller_dni'] = !empty($validated['seller_dni']) ? strtoupper(trim($validated['seller_dni'])) : null;
        $validated['tiene_cesion'] = $request->has('tiene_cesion') || $request->input('tipo') === 'Cesión de póliza';
        $validated['numero_poliza'] = !empty($validated['numero_poliza']) ? strtoupper(trim($validated['numero_poliza'])) : null;

        $tramite->update($validated);

        return redirect()->route('operations.gestoria.index')
            ->with('success', "Expediente {$tramite->matricula} actualizado correctamente.");
    }

    public function updateGestoriaStatus(Request $request, GestoriaTramite $tramite)
    {
        $estado = $request->input('estado');
        $hoy = date('d/m/Y');

        $data = ['estado' => $estado];

        if ($estado === 'ENVIADO_GESTORIA' && !$tramite->fecha_out_gestoria) {
            $data['fecha_out_gestoria'] = $hoy;
        } elseif ($estado === 'PERMISO_RECIBIDO' && !$tramite->fecha_recogido) {
            $data['fecha_recogido'] = $hoy;
        }

        $tramite->update($data);

        return redirect()->back()
            ->with('success', "Expediente {$tramite->matricula} actualizado a estado: {$tramite->status_label}.");
    }

    /**
     * Generación de Documentos Oficiales de Gestoría
     */
    public function documentoPortada(GestoriaTramite $tramite)
    {
        return view('operations.gestoria.docs.portada', compact('tramite'));
    }

    public function documentoMandatoComprador(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.mandato-comprador', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    public function documentoMandatoVendedor(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.mandato-vendedor', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    public function documentoContratoCompraventa(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        $precioNum = 1000;
        if (!empty($tramite->precio_venta)) {
            $cleaned = str_replace(['€', ' ', '.'], '', $tramite->precio_venta);
            $cleaned = str_replace(',', '.', $cleaned);
            $parsed = (float)$cleaned;
            if ($parsed > 0) {
                $precioNum = $parsed;
            }
        }
        $precioLetras = $this->numeroALetras($precioNum);
        $precioFormatted = number_format($precioNum, 2, ',', '.') . ' €';

        return view('operations.gestoria.docs.contrato-compraventa', compact('tramite', 'dia', 'mesNombre', 'ano', 'precioLetras', 'precioFormatted'));
    }

    public function documentoExtravioVendedor(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.extravio-vendedor', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    public function documentoCesion(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.cesion', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    public function documentoEmbargo(GestoriaTramite $tramite)
    {
        $fecha = $tramite->fecha_tramite ?: ($tramite->created_at ? $tramite->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.gestoria.docs.embargo', compact('tramite', 'dia', 'mesNombre', 'ano'));
    }

    private function numeroALetras($number): string
    {
        $unidades = ['', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE'];
        $especiales = [11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE', 16 => 'DIECISÉIS', 17 => 'DIECISIETE', 18 => 'DIECIOCHO', 19 => 'DIECINUEVE'];
        $decenas = ['', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA', 'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA'];
        $centenas = ['', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS', 'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS'];

        $number = (int)$number;
        if ($number === 0) return 'CERO EUROS';
        if ($number === 100) return 'CIEN EUROS';

        $convertir3 = function($n) use ($unidades, $especiales, $decenas, $centenas) {
            $res = '';
            $c = (int)($n / 100);
            $restoCentena = $n % 100;
            $d = (int)($restoCentena / 10);
            $u = $restoCentena % 10;

            if ($n === 100) return 'CIEN';
            if ($c > 0) $res .= $centenas[$c] . ' ';

            if ($restoCentena >= 11 && $restoCentena <= 19) {
                $res .= $especiales[$restoCentena];
            } elseif ($restoCentena === 10) {
                $res .= 'DIEZ';
            } elseif ($restoCentena === 20) {
                $res .= 'VEINTE';
            } elseif ($restoCentena > 20 && $restoCentena < 30) {
                $res .= 'VEINTI' . $unidades[$u];
            } else {
                if ($d > 0) {
                    $res .= $decenas[$d];
                    if ($u > 0) $res .= ' Y ' . $unidades[$u];
                } elseif ($u > 0) {
                    $res .= $unidades[$u];
                }
            }
            return trim($res);
        };

        $miles = (int)($number / 1000);
        $resto = $number % 1000;
        $texto = '';

        if ($miles === 1) {
            $texto .= 'MIL ';
        } elseif ($miles > 1) {
            $texto .= $convertir3($miles) . ' MIL ';
        }

        if ($resto > 0) {
            $texto .= $convertir3($resto);
        }

        return trim($texto) . ' EUROS';
    }

    /**
     * =========================================================================
     * 2. EXTORNOS & TRASLADOS MILITARES (PCS)
     * =========================================================================
     */
    public function indexExtornos(Request $request)
    {
        // Auto-sincronizar cualquier póliza cancelada que aún no tenga registro en Extornos
        $polizasCanceladas = Policy::where(function($q) {
                $q->where('estado', 'Cancelado')
                  ->orWhere('balance', 'Cancelled');
            })
            ->whereNotIn('id', Extorno::whereNotNull('policy_id')->pluck('policy_id'))
            ->with('client.sede')
            ->get();

        foreach ($polizasCanceladas as $pol) {
            $cli = $pol->client;
            Extorno::create([
                'client_id' => $cli?->id,
                'policy_id' => $pol->id,
                'office' => ($cli && $cli->sede && $cli->sede->slug === 'central') ? 'Central' : 'NEX',
                'cartera' => ($pol->codigo_aseguradora == '87' || str_contains($pol->aseguradora ?? '', 'GEICO')) ? '87' : '81',
                'numero_poliza' => $pol->numero_poliza ?: 'Pendiente',
                'client_name' => $cli ? strtoupper($cli->full_name) : 'CLIENTE KFM',
                'patria_amount' => $pol->prima,
                'projected_departure' => 'Cancelación registrada en CRM',
                'status' => 'Pendiente',
                'observaciones' => $pol->observaciones ? "Historial: " . trim($pol->observaciones) : 'Cancelación registrada desde ficha de cliente',
            ]);
        }

        $query = Extorno::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('projected_departure', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $extornos = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => Extorno::count(),
            'pendientes' => Extorno::where('status', 'Pendiente')->count(),
            'tramitados' => Extorno::where('status', 'Tramitado')->count(),
            'reembolsados' => Extorno::where('status', 'Reembolsado')->count(),
        ];

        return view('operations.extornos.index', compact('extornos', 'stats'));
    }

    public function lookupPolicy(Request $request)
    {
        $num = trim($request->input('numero_poliza') ?? $request->input('q') ?? '');
        if (empty($num)) {
            return response()->json(['found' => false]);
        }

        // Buscar por numero_poliza exacto o por matrícula exacta primero
        $policy = Policy::with(['client.sede'])
            ->where(function ($q) use ($num) {
                $q->where('numero_poliza', $num)
                  ->orWhere('matricula', $num);
            })
            ->latest()
            ->first();

        if (!$policy) {
            $policy = Policy::with(['client.sede'])
                ->where(function ($q) use ($num) {
                    $q->where('numero_poliza', 'LIKE', "%{$num}%")
                      ->orWhere('matricula', 'LIKE', "%{$num}%");
                })
                ->latest()
                ->first();
        }

        if (!$policy) {
            return response()->json(['found' => false]);
        }

        $client = $policy->client;
        $office = ($client && $client->sede && $client->sede->slug === 'central') ? 'Central' : 'NEX';
        $cartera = ($policy->codigo_aseguradora == '87' || str_contains($policy->aseguradora ?? '', 'GEICO')) ? '87' : '81';

        return response()->json([
            'found' => true,
            'policy_id' => $policy->id,
            'numero_poliza' => $policy->numero_poliza,
            'client_id' => $client?->id,
            'client_name' => $client ? strtoupper($client->full_name) : '',
            'client_dni' => $client?->doc_identidad ?: ($client?->ssn ? "SSN: {$client->ssn}" : ''),
            'client_telefono' => $client?->telefono,
            'sede_id' => $client?->sede_id,
            'agente' => $policy->agente ?: $client?->agente,
            'office' => $office,
            'cartera' => $cartera,
            'cartera_label' => $cartera === '87' ? '87 (GEICO 87)' : '81 (Patria 81)',
            'patria_amount' => $policy->prima ?: '',
            'estado' => $policy->estado,
            'ramo' => $policy->ramo,
            'matricula' => $policy->matricula,
            'marca' => $policy->marca,
            'modelo' => $policy->modelo,
            'vin' => $policy->vin,
            'vehiculo' => trim("{$policy->marca} {$policy->modelo}"),
        ]);
    }

    public function lookupPlate(Request $request)
    {
        $plateInput = trim($request->input('matricula') ?? $request->input('q') ?? '');
        if (empty($plateInput)) {
            return response()->json(['success' => false, 'message' => 'Por favor introduce una matrícula.']);
        }

        $clean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $plateInput));
        $dgt = $this->calcularMatriculaEspanola($clean);
        $formatted = $dgt['matricula_formateada'] ?? $clean;

        // Buscar en la base de datos de KFM (Policies, Gestoria, Cesiones, Sin Matricula)
        $policy = Policy::with(['client.sede'])
            ->where(function ($q) use ($clean, $plateInput, $formatted) {
                $q->where('matricula', $clean)
                  ->orWhere('matricula', $plateInput)
                  ->orWhere('matricula', $formatted)
                  ->orWhereRaw("REPLACE(REPLACE(COALESCE(matricula, ''), ' ', ''), '-', '') = ?", [$clean])
                  ->orWhere('matricula', 'like', "%{$clean}%");
            })
            ->latest()
            ->first();

        $gestoria = null;
        if (!$policy) {
            $gestoria = GestoriaTramite::with('client')
                ->where(function ($q) use ($clean, $plateInput, $formatted) {
                    $q->where('matricula', $clean)
                      ->orWhere('matricula', $plateInput)
                      ->orWhere('matricula', $formatted)
                      ->orWhereRaw("REPLACE(REPLACE(COALESCE(matricula, ''), ' ', ''), '-', '') = ?", [$clean])
                      ->orWhere('matricula', 'like', "%{$clean}%");
                })
                ->latest()
                ->first();
        }

        $client = $policy?->client ?: $gestoria?->client;
        $foundInCrm = ($policy || $gestoria);

        $marca = $policy?->marca ?: $gestoria?->marca ?: '';
        $modelo = $policy?->modelo ?: $gestoria?->modelo ?: '';
        $vin = $policy?->vin ?: $gestoria?->vin ?: '';

        // Si tenemos VIN pero no marca/modelo completos, o queremos ficha técnica completa de EE.UU. / DOT
        $vinData = null;
        if ($vin && strlen($vin) === 17) {
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(3)->get("https://vpic.nhtsa.dot.gov/api/vehicles/decodevinvalues/{$vin}?format=json");
                if ($response->successful()) {
                    $results = $response->json('Results.0');
                    if ($results && !empty($results['Make'])) {
                        $vinData = [
                            'make' => $results['Make'] ?? '',
                            'model' => $results['Model'] ?? '',
                            'year' => $results['ModelYear'] ?? '',
                            'fuel' => $results['FuelTypePrimary'] ?? '',
                            'displacement' => $results['DisplacementL'] ? "{$results['DisplacementL']}L" : '',
                            'cylinders' => $results['EngineCylinders'] ?? '',
                            'body' => $results['BodyClass'] ?? '',
                            'plant_country' => $results['PlantCountry'] ?? '',
                        ];
                        if (empty($marca)) $marca = $results['Make'];
                        if (empty($modelo)) $modelo = $results['Model'];
                    }
                }
            } catch (\Throwable $e) {
                // Silently ignore NHTSA external failure
            }
        }

        return response()->json([
            'success' => true,
            'matricula' => $dgt['matricula_formateada'] ?? $clean,
            'raw_matricula' => $clean,
            'tipo_matricula' => $dgt['tipo'] ?? 'Ordinaria',
            'fecha_matriculacion' => $dgt['fecha_texto'] ?? 'Estimada',
            'ano_matriculacion' => $dgt['ano'] ?? 2018,
            'antiguedad_texto' => $dgt['antiguedad'] ?? '',
            'etiqueta_dgt' => $dgt['etiqueta'] ?? 'C',
            'etiqueta_label' => $dgt['etiqueta_label'] ?? 'Distintivo C (Verde)',
            'etiqueta_color' => $dgt['etiqueta_color'] ?? 'emerald',
            'found_in_crm' => (bool)$foundInCrm,
            'marca' => $marca ?: 'No identificada',
            'modelo' => $modelo ?: '',
            'vehiculo' => trim("{$marca} {$modelo}") ?: 'Vehículo',
            'vin' => $vin ?: 'No registrado',
            'vin_data' => $vinData,
            'client_id' => $client?->id,
            'client_name' => $client ? strtoupper($client->full_name) : null,
            'client_phone' => $client?->telefono,
            'policy_id' => $policy?->id,
            'policy_number' => $policy?->numero_poliza,
            'cartera' => ($policy?->codigo_aseguradora === '87') ? 'GEICO 87' : ($policy ? 'Patria 81' : null),
            'policy_status' => $policy?->estado,
            'external_url' => "https://auto-info.gratis/es/",
        ]);
    }

    public function calcularMatriculaEspanola(string $clean): array
    {
        // 1. Formato ordinario moderno: 4 dígitos + 3 letras (0000 BBB a 9999 ZZZ)
        if (preg_match('/^(\d{4})([BCDFGHJKLMNPRSTVWXYZ]{3})$/i', $clean, $m)) {
            $digits = $m[1];
            $letters = strtoupper($m[2]);
            $formatted = "{$digits} {$letters}";
            
            $chars = 'BCDFGHJKLMNPRSTVWXYZ';
            $idx = 0;
            for ($i = 0; $i < 3; $i++) {
                $pos = strpos($chars, $letters[$i]);
                if ($pos !== false) {
                    $idx = $idx * 20 + $pos;
                }
            }

            $milestones = [
                [0,    2000, 9,  'Septiembre 2000'],
                [67,   2001, 1,  'Enero 2001'],
                [230,  2002, 1,  'Enero 2002'],
                [400,  2002, 10, 'Octubre 2002'],
                [480,  2003, 1,  'Enero 2003'],
                [680,  2004, 1,  'Enero 2004'],
                [800,  2004, 9,  'Septiembre 2004'],
                [870,  2005, 1,  'Enero 2005'],
                [1060, 2006, 1,  'Enero 2006'],
                [1200, 2006, 5,  'Mayo 2006'],
                [1340, 2007, 1,  'Enero 2007'],
                [1550, 2008, 1,  'Enero 2008'],
                [1600, 2008, 2,  'Febrero 2008'],
                [1720, 2009, 1,  'Enero 2009'],
                [1860, 2010, 1,  'Enero 2010'],
                [2000, 2010, 12, 'Diciembre 2010'],
                [2010, 2011, 1,  'Enero 2011'],
                [2120, 2012, 1,  'Enero 2012'],
                [2220, 2013, 1,  'Enero 2013'],
                [2300, 2014, 1,  'Enero 2014'],
                [2400, 2014, 11, 'Noviembre 2014'],
                [2420, 2015, 1,  'Enero 2015'],
                [2428, 2015, 4,  'Abril 2015'],
                [2530, 2016, 1,  'Enero 2016'],
                [2700, 2017, 1,  'Enero 2017'],
                [2800, 2017, 6,  'Junio 2017'],
                [2900, 2018, 1,  'Enero 2018'],
                [3060, 2019, 1,  'Enero 2019'],
                [3200, 2019, 9,  'Septiembre 2019'],
                [3260, 2020, 1,  'Enero 2020'],
                [3351, 2020, 7,  'Julio 2020'],
                [3410, 2021, 1,  'Enero 2021'],
                [3520, 2022, 1,  'Enero 2022'],
                [3600, 2022, 9,  'Septiembre 2022'],
                [3680, 2023, 1,  'Enero 2023'],
                [3802, 2023, 4,  'Abril 2023'],
                [3880, 2024, 1,  'Enero 2024'],
                [4000, 2024, 9,  'Septiembre 2024'],
                [4060, 2025, 1,  'Enero 2025'],
                [4200, 2025, 9,  'Septiembre 2025'],
                [4300, 2026, 1,  'Enero 2026'],
            ];

            $fechaTexto = 'Reciente (2026)';
            $ano = 2026;
            $mes = 1;

            $count = count($milestones);
            for ($k = 0; $k < $count - 1; $k++) {
                $idx1 = $milestones[$k][0];
                $idx2 = $milestones[$k + 1][0];
                if ($idx >= $idx1 && $idx <= $idx2) {
                    $useFirst = ($idx - $idx1) <= ($idx2 - $idx);
                    $mChoose = $useFirst ? $milestones[$k] : $milestones[$k + 1];
                    $ano = $mChoose[1];
                    $mes = $mChoose[2];
                    $fechaTexto = $mChoose[3];
                    break;
                }
            }

            if ($idx > 4300) {
                $ano = 2026;
                $fechaTexto = 'Año 2026';
            }

            $currentYear = (int)date('Y');
            $diffYears = max(0, $currentYear - $ano);
            $antiguedadTexto = "{$diffYears} años aprox.";

            if ($ano >= 2015) {
                $etiqueta = 'C';
                $etiquetaLabel = 'Distintivo C (Verde)';
                $etiquetaColor = 'emerald';
            } elseif ($ano >= 2006) {
                $etiqueta = 'C / B';
                $etiquetaLabel = 'Distintivo C (Gasolina) / B (Diésel)';
                $etiquetaColor = 'amber';
            } elseif ($ano >= 2001) {
                $etiqueta = 'B';
                $etiquetaLabel = 'Distintivo B (Amarillo - Gasolina)';
                $etiquetaColor = 'yellow';
            } else {
                $etiqueta = 'Sin Etiqueta';
                $etiquetaLabel = 'Sin Distintivo Ambiental (Categoría A)';
                $etiquetaColor = 'slate';
            }

            return [
                'tipo' => 'Ordinaria (Sistema Europeo)',
                'matricula_formateada' => $formatted,
                'fecha_texto' => $fechaTexto,
                'ano' => $ano,
                'mes' => $mes,
                'antiguedad' => $antiguedadTexto,
                'etiqueta' => $etiqueta,
                'etiqueta_label' => $etiquetaLabel,
                'etiqueta_color' => $etiquetaColor,
            ];
        }

        // 2. Formato provincial histórico (ej: CA-1234-AB)
        if (preg_match('/^([A-Z]{1,2})(\d{4,6})([A-Z]{0,2})$/i', $clean, $m)) {
            $prov = strtoupper($m[1]);
            $num = $m[2];
            $let = strtoupper($m[3]);
            $formatted = "{$prov}-{$num}" . ($let ? "-{$let}" : '');

            return [
                'tipo' => "Provincial Histórica ({$prov})",
                'matricula_formateada' => $formatted,
                'fecha_texto' => 'Anterior a Septiembre 2000',
                'ano' => 1998,
                'antiguedad' => 'Más de 25 años (Vehículo Clásico)',
                'etiqueta' => 'Sin Etiqueta',
                'etiqueta_label' => 'Sin Distintivo Ambiental (Categoría A)',
                'etiqueta_color' => 'slate',
            ];
        }

        // 3. Fallback
        return [
            'tipo' => 'Especial / Extranjera',
            'matricula_formateada' => $clean,
            'fecha_texto' => 'No disponible',
            'ano' => 2018,
            'antiguedad' => '-',
            'etiqueta' => 'Consultar DGT',
            'etiqueta_label' => 'Consultar en Auto-Info o DGT',
            'etiqueta_color' => 'slate',
        ];
    }

    public function storeExtorno(Request $request)
    {
        $validated = $request->validate([
            'office' => 'required|string|max:50',
            'cartera' => 'required|string|max:50',
            'numero_poliza' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'email_sent_patria_date' => 'nullable|string|max:50',
            'projected_departure' => 'nullable|string|max:150',
            'patria_amount' => 'nullable|string|max:50',
            'amount_reimbursed' => 'nullable|string|max:50',
            'status' => 'required|string|max:50',
            'stateside_geico_form' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
            'client_id' => 'nullable|integer',
            'policy_id' => 'nullable|integer',
            'cancelar_poliza' => 'nullable',
            'redirect_to' => 'nullable|string',
        ]);

        $validated['stateside_geico_form'] = $request->has('stateside_geico_form');

        // Asociar client_id y policy_id si no vienen pero existen por numero_poliza
        if (empty($validated['policy_id']) && !empty($validated['numero_poliza'])) {
            $foundPolicy = Policy::where('numero_poliza', $validated['numero_poliza'])->first();
            if ($foundPolicy) {
                $validated['policy_id'] = $foundPolicy->id;
                $validated['client_id'] = $foundPolicy->client_id;
            }
        }

        $redirectTo = $validated['redirect_to'] ?? null;
        unset($validated['cancelar_poliza'], $validated['redirect_to']);

        // Normalizar cartera a 81 / 87
        if ($validated['cartera'] === 'Patria Hispana') {
            $validated['cartera'] = '81';
        } elseif ($validated['cartera'] === 'GEICO' || $validated['cartera'] === 'GEICO Overseas') {
            $validated['cartera'] = '87';
        }

        $extorno = Extorno::create($validated);

        // Siempre cancelar / dar de baja la póliza asociada en la ficha del cliente
        if (!empty($validated['policy_id'])) {
            $pol = Policy::find($validated['policy_id']);
            if ($pol) {
                $fecha = date('d/m/Y');
                $obsAct = $pol->observaciones ? trim($pol->observaciones) . "\n" : '';
                $montoReembolso = $validated['amount_reimbursed'] ?: ($validated['patria_amount'] ?? '');
                $motivoSalida = $validated['projected_departure'] ?: 'Órdenes de Traslado PCS';
                $pol->update([
                    'estado' => 'Cancelado',
                    'balance' => 'Cancelled',
                    'observaciones' => $obsAct . "● BAJA POR EXTORNO PCS ({$fecha}): Reembolso {$montoReembolso} € (Destino: {$motivoSalida})",
                ]);
            }
        }

        if ($redirectTo) {
            return redirect($redirectTo)
                ->with('success', "Extorno y devolución para la póliza #{$validated['numero_poliza']} registrado correctamente en Operaciones.");
        }

        return redirect()->route('operations.extornos.index')
            ->with('success', "Extorno para la póliza #{$validated['numero_poliza']} registrado correctamente.");
    }

    public function updateExtorno(Request $request, Extorno $extorno)
    {
        $validated = $request->validate([
            'status' => 'required|string|max:50',
            'amount_reimbursed' => 'nullable|string|max:50',
            'recibo_numero' => 'nullable|string|max:50',
            'stateside_geico_form' => 'nullable|boolean',
            'observaciones' => 'nullable|string',
        ]);

        $validated['stateside_geico_form'] = $request->has('stateside_geico_form');
        if ($validated['status'] === 'Reembolsado' && empty($extorno->recibo_fecha)) {
            $validated['recibo_fecha'] = date('d/m/Y');
        }

        $extorno->update($validated);

        return redirect()->route('operations.extornos.index')
            ->with('success', "Extorno #{$extorno->numero_poliza} actualizado.");
    }

    /**
     * =========================================================================
     * 3. PÓLIZAS SIN MATRÍCULAS / SM (SEGUIMIENTO VEHÍCULOS)
     * =========================================================================
     */
    public function indexSinMatricula(Request $request)
    {
        // 1. Auto-sincronizar contrataciones de vehículos sin matrícula ('SM', 'S/M', vacía o provisional)
        $vehiclePolicies = Policy::where('ramo', 'Vehiculo')
            ->where('estado', '!=', 'Cancelado')
            ->with('client')
            ->get();

        foreach ($vehiclePolicies as $p) {
            $matUpper = strtoupper(trim($p->matricula ?? ''));
            $isSinMat = ($matUpper === '' || in_array($matUpper, ['SM', 'S/M', 'S.M.', 'SIN MATRICULA', 'SIN MATRÍCULA', 'PENDIENTE']));
            $existing = SinMatricula::where('policy_id', $p->id)->first();

            if ($isSinMat) {
                $aseguradoraCode = ($p->codigo_aseguradora == '87' || str_contains($p->aseguradora ?? '', 'GEICO')) ? '87 (GEICO 87)' : '81 (Patria 81)';
                $vehiculoDesc = trim(($p->marca . ' ' . $p->modelo) . ($p->vin ? " (Bastidor: {$p->vin})" : ''));
                $obs = $vehiculoDesc . ($p->observaciones ? " — " . $p->observaciones : '');

                if (!$existing) {
                    SinMatricula::create([
                        'client_id' => $p->client_id,
                        'policy_id' => $p->id,
                        'numero_poliza' => $p->numero_poliza ?: 'Pendiente',
                        'aseguradora' => $aseguradoraCode,
                        'matricula_provisional' => !empty($matUpper) ? $matUpper : 'SM',
                        'client_name' => $p->client ? strtoupper($p->client->full_name) : 'CLIENTE KFM',
                        'date_notification' => $p->fecha_pago ?: ($p->created_at ? $p->created_at->format('d/m/Y') : date('d/m/Y')),
                        'estado' => 'Pendiente',
                        'observaciones' => $obs,
                    ]);
                } else {
                    // Si ya existía pero estaba en regularizado y la póliza volvió a SM
                    if ($existing->estado === 'Regularizado') {
                        $existing->update([
                            'estado' => 'Pendiente',
                            'matricula_provisional' => !empty($matUpper) ? $matUpper : 'SM',
                            'matricula_definitiva' => null,
                        ]);
                    }
                }
            } else {
                // La póliza ya tiene matrícula real en su contratación: regularizar automáticamente para que desaparezca de pendientes
                if ($existing && $existing->estado !== 'Regularizado') {
                    $existing->update([
                        'estado' => 'Regularizado',
                        'matricula_definitiva' => $matUpper,
                    ]);
                }
            }
        }

        $query = SinMatricula::with(['client', 'policy'])->latest();

        // Filtro por estado: por defecto mostrar solo las pólizas de vehículos sin matrícula (pendientes)
        $filtroEstado = $request->input('estado');
        if ($filtroEstado === 'regularizados' || $filtroEstado === 'Regularizado') {
            $query->where('estado', 'Regularizado');
        } elseif ($filtroEstado === 'todos') {
            // Mostrar todas (pendientes + regularizadas)
        } elseif (!empty($filtroEstado)) {
            $query->where('estado', $filtroEstado);
        } else {
            // Comportamiento solicitado: SOLO las pólizas de vehículos sin matrícula activa
            $query->where('estado', '!=', 'Regularizado');
        }

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('matricula_provisional', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('matricula_definitiva', 'like', "%{$search}%")
                  ->orWhere('observaciones', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => SinMatricula::where('estado', '!=', 'Regularizado')->count(),
            'pendientes' => SinMatricula::where('estado', 'Pendiente')->count(),
            'patria' => SinMatricula::where('estado', '!=', 'Regularizado')->where('aseguradora', 'like', '%81%')->count(),
            'geico' => SinMatricula::where('estado', '!=', 'Regularizado')->where('aseguradora', 'like', '%87%')->count(),
            'regularizados' => SinMatricula::where('estado', 'Regularizado')->count(),
        ];

        return view('operations.sin-matricula.index', compact('items', 'stats'));
    }

    public function storeSinMatricula(Request $request)
    {
        $validated = $request->validate([
            'numero_poliza' => 'required|string|max:50',
            'aseguradora' => 'required|string|max:50',
            'matricula_provisional' => 'required|string|max:100',
            'client_name' => 'required|string|max:150',
            'date_notification' => 'nullable|string|max:50',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $pol = Policy::where('numero_poliza', trim($validated['numero_poliza']))->first();
        if ($pol) {
            $validated['policy_id'] = $pol->id;
            $validated['client_id'] = $pol->client_id;
        }

        SinMatricula::create($validated);

        return redirect()->route('operations.sin-matricula.index')
            ->with('success', 'Póliza sin matrícula registrada para seguimiento.');
    }

    public function updateSinMatricula(Request $request, SinMatricula $item)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'matricula_definitiva' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $item->update($validated);

        // Si se asignó matrícula definitiva, actualizar la contratación de la póliza del cliente
        if (!empty($validated['matricula_definitiva'])) {
            $definitiva = strtoupper(trim($validated['matricula_definitiva']));
            if ($item->policy_id) {
                $item->policy?->update([
                    'matricula' => $definitiva,
                ]);
            }
            $item->update([
                'matricula_definitiva' => $definitiva,
                'estado' => 'Regularizado',
            ]);
        }

        return redirect()->route('operations.sin-matricula.index')
            ->with('success', "Matrícula definitiva asignada a {$item->client_name}. La póliza ha quedado regularizada y retirada de la lista de pendientes.");
    }

    /**
     * =========================================================================
     * 4. PÓLIZAS DE EMBARQUE / SHIPPING POV (US ⇄ ROTA)
     * =========================================================================
     */
    public function indexEmbarque(Request $request)
    {
        $query = PolizaEmbarque::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => PolizaEmbarque::count(),
            'en_transito' => PolizaEmbarque::where('estado', 'En Tránsito')->count(),
            'en_vpc' => PolizaEmbarque::where('estado', 'Llegado a VPC')->count(),
            'peritados' => PolizaEmbarque::where('estado', 'Peritado')->count(),
            'liquidados' => PolizaEmbarque::where('estado', 'Liquidado')->count(),
        ];

        return view('operations.embarque.index', compact('items', 'stats'));
    }

    public function storeEmbarque(Request $request)
    {
        $validated = $request->validate([
            'numero_poliza' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'prima' => 'required|string|max:50',
            'fecha_emision' => 'nullable|string|max:50',
            'fecha_desactivacion' => 'nullable|string|max:50',
            'fecha_reactivacion' => 'nullable|string|max:50',
            'fecha_peritacion' => 'nullable|string|max:50',
            'importe_renovacion' => 'nullable|string|max:50',
            'importe_prima_no_consumida' => 'nullable|string|max:50',
            'importe_a_pagar' => 'nullable|string|max:50',
            'prima_aplicada_renovacion' => 'nullable|boolean',
            'estado' => 'required|string|max:50',
            'comments' => 'nullable|string',
        ]);

        $validated['prima_aplicada_renovacion'] = $request->has('prima_aplicada_renovacion');

        $pol = Policy::where('numero_poliza', trim($validated['numero_poliza']))->first();
        if ($pol) {
            $validated['policy_id'] = $pol->id;
            $validated['client_id'] = $pol->client_id;
        }

        PolizaEmbarque::create($validated);

        return redirect()->route('operations.embarque.index')
            ->with('success', "Póliza de embarque #{$validated['numero_poliza']} registrada.");
    }

    public function updateEmbarque(Request $request, PolizaEmbarque $embarque)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'fecha_reactivacion' => 'nullable|string|max:50',
            'fecha_peritacion' => 'nullable|string|max:50',
            'importe_renovacion' => 'nullable|string|max:50',
            'importe_prima_no_consumida' => 'nullable|string|max:50',
            'importe_a_pagar' => 'nullable|string|max:50',
            'prima_aplicada_renovacion' => 'nullable|boolean',
            'comments' => 'nullable|string',
        ]);

        $validated['prima_aplicada_renovacion'] = $request->has('prima_aplicada_renovacion');

        $embarque->update($validated);

        return redirect()->route('operations.embarque.index')
            ->with('success', "Expediente de embarque #{$embarque->numero_poliza} actualizado.");
    }

    /**
     * =========================================================================
     * 5. SINIESTROS & MULTAS ARAG
     * =========================================================================
     */
    public function indexSiniestros(Request $request)
    {
        $tab = $request->input('tab', 'siniestros'); // siniestros or multas
        $query = Siniestro::with(['client', 'policy'])->where('tipo', $tab === 'multas' ? 'Multa' : 'Siniestro')->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('referencia_siniestro', 'like', "%{$search}%");
            });
        }

        $filter = $request->input('filter');
        if ($filter === 'reaviso' && $tab !== 'multas') {
            $query->where('estado_siniestro', 'Abierto')
                  ->whereNotNull('fecha_cliente_avisado')
                  ->where('fecha_cliente_avisado', '!=', '');
        }

        $items = $query->paginate(20)->withQueryString();

        $stats = [
            'siniestros_abiertos' => Siniestro::where('tipo', 'Siniestro')->where('estado_siniestro', '!=', 'Cerrado')->count(),
            'siniestros_total' => Siniestro::where('tipo', 'Siniestro')->count(),
            'siniestros_reaviso' => Siniestro::where('tipo', 'Siniestro')->where('estado_siniestro', 'Abierto')->get()->filter(fn($s) => $s->requiere_reaviso)->count(),
            'multas_activas' => Siniestro::where('tipo', 'Multa')->where('estado_multa', '!=', 'Archivada')->count(),
            'multas_total' => Siniestro::where('tipo', 'Multa')->count(),
        ];

        return view('operations.siniestros.index', compact('items', 'stats', 'tab', 'filter'));
    }

    public function indexMultas(Request $request)
    {
        $request->merge(['tab' => 'multas']);
        return $this->indexSiniestros($request);
    }

    public function storeSiniestro(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'required|in:Siniestro,Multa',
            'office' => 'required|string|max:50',
            'cartera' => 'required|string|max:50',
            'numero_poliza' => 'required|string|max:50',
            'matricula' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'client_id' => 'nullable|integer',
            'policy_id' => 'nullable|integer',
            'fecha_email_diego' => 'nullable|string|max:50',
            'fecha_reclamacion_recibida' => 'nullable|string|max:50',
            'fecha_cliente_avisado' => 'nullable|string|max:50',
            'referencia_patria' => 'nullable|string|max:100',
            'referencia_arag' => 'nullable|string|max:100',
            'referencia_siniestro' => 'nullable|string|max:100',
            'tramitador' => 'nullable|string|max:100',
            'tramitadora' => 'nullable|string|max:100',
            'estado_siniestro' => 'nullable|string|max:50',
            'ticket_date' => 'nullable|string|max:50',
            'date_client_notified' => 'nullable|string|max:50',
            'date_sent_arag' => 'nullable|string|max:50',
            'estado_multa' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $validated['cartera'] = (str_contains($validated['cartera'] ?? '', '87') || str_contains($validated['cartera'] ?? '', 'GEICO')) ? '87 (GEICO 87)' : '81 (Patria 81)';

        if (!empty($validated['tramitador']) && empty($validated['tramitadora'])) {
            $validated['tramitadora'] = $validated['tramitador'];
        }

        if (!empty($validated['referencia_patria']) && empty($validated['referencia_siniestro'])) {
            $validated['referencia_siniestro'] = $validated['referencia_patria'];
        }

        // Asociar automáticamente con póliza y cliente si existen
        $pol = Policy::where('numero_poliza', trim($validated['numero_poliza']))->first();
        if ($pol) {
            $validated['policy_id'] = $pol->id;
            $validated['client_id'] = $pol->client_id;
        }

        $sin = Siniestro::create($validated);

        // Si se envió desde la ficha de cliente, volver a la ficha
        if ($request->filled('redirect_to_client') && $sin->client_id) {
            return redirect()->route('clients.show', $sin->client_id)
                ->with('success', "Parte de {$validated['tipo']} grabado correctamente en la contratación del cliente.");
        }

        return redirect()->route(strtolower($validated['tipo']) === 'multa' ? 'operations.multas.index' : 'operations.siniestros.index')
            ->with('success', "{$validated['tipo']} registrado correctamente.");
    }

    public function updateSiniestro(Request $request, Siniestro $siniestro)
    {
        $validated = $request->validate([
            'referencia_patria' => 'nullable|string|max:100',
            'referencia_arag' => 'nullable|string|max:100',
            'tramitadora' => 'nullable|string|max:100',
            'tramitador' => 'nullable|string|max:100',
            'fecha_reclamacion_recibida' => 'nullable|string|max:50',
            'fecha_cliente_avisado' => 'nullable|string|max:50',
            'estado_siniestro' => 'nullable|string|max:50',
            'estado_multa' => 'nullable|string|max:50',
            'ticket_date' => 'nullable|string|max:50',
            'date_sent_arag' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        if (!empty($validated['tramitador']) && empty($validated['tramitadora'])) {
            $validated['tramitadora'] = $validated['tramitador'];
        }

        if (!empty($validated['referencia_patria'])) {
            $validated['referencia_siniestro'] = $validated['referencia_patria'];
        }

        $siniestro->update($validated);

        return redirect()->back()
            ->with('success', 'Registro actualizado con éxito.');
    }

    /**
     * =========================================================================
     * 6. PAGOS FRACCIONADOS (FINANCIACIÓN EN 2 PLAZOS)
     * =========================================================================
     */
    public function indexPagos(Request $request)
    {
        $query = PagoFraccionado::with(['client', 'policy'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $pagos = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => PagoFraccionado::count(),
            'pendientes' => PagoFraccionado::where('estado', 'Pendiente 2º Pago')->count(),
            'cobrados' => PagoFraccionado::where('estado', 'Cobrado')->count(),
            'vencidos' => PagoFraccionado::where('estado', 'Vencido')->count(),
        ];

        return view('operations.pagos.index', compact('pagos', 'stats'));
    }

    public function storePago(Request $request)
    {
        $validated = $request->validate([
            'numero_poliza' => 'required|string|max:50',
            'client_name' => 'required|string|max:150',
            'total_seguro' => 'required|string|max:50',
            'primer_pago_importe' => 'required|string|max:50',
            'primer_pago_recibo' => 'nullable|string|max:50',
            'primer_pago_fecha' => 'nullable|string|max:50',
            'segundo_pago_vencimiento' => 'nullable|string|max:50',
            'segundo_pago_importe' => 'required|string|max:50',
            'segundo_pago_recibo' => 'nullable|string|max:50',
            'tarjeta_info' => 'nullable|string|max:100',
            'tarjeta_exp' => 'nullable|string|max:20',
            'cvv' => 'nullable|string|max:10',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'client_id' => 'nullable|integer',
            'policy_id' => 'nullable|integer',
        ]);

        $pol = Policy::where('numero_poliza', trim($validated['numero_poliza']))->first();
        if ($pol) {
            $validated['policy_id'] = $pol->id;
            $validated['client_id'] = $pol->client_id;
        }

        PagoFraccionado::create($validated);

        return redirect()->route('operations.pagos.index')
            ->with('success', "Pago fraccionado para la póliza #{$validated['numero_poliza']} registrado correctamente.");
    }

    public function updatePago(Request $request, PagoFraccionado $pago)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'segundo_pago_recibo' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $pago->update($validated);

        return redirect()->route('operations.pagos.index')
            ->with('success', "Estado de pago actualizado para #{$pago->numero_poliza}.");
    }

    /**
     * =========================================================================
     * 7. CESIONES DE PÓLIZA SIN TRANSFERENCIA
     * =========================================================================
     */
    public function indexCesiones(Request $request)
    {
        $query = CesionPoliza::with(['client', 'policy', 'sede'])->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_poliza', 'like', "%{$search}%")
                  ->orWhere('cedente_nombre', 'like', "%{$search}%")
                  ->orWhere('cesionario_nombre', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('cesionario_ssn', 'like', "%{$search}%");
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        if ($sedeId = $request->input('sede_id')) {
            $query->where('sede_id', $sedeId);
        }

        $cesiones = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => CesionPoliza::count(),
            'activas' => CesionPoliza::where('estado', 'Activa')->count(),
            'pendientes' => CesionPoliza::where('estado', 'Pendiente de firma')->count(),
            'finalizadas' => CesionPoliza::where('estado', 'Finalizada')->count(),
        ];

        $sedes = Sede::all();

        return view('operations.cesiones.index', compact('cesiones', 'stats', 'sedes'));
    }

    public function storeCesion(Request $request)
    {
        $validated = $request->validate([
            'numero_poliza' => 'required|string|max:50',
            'cartera' => 'nullable|string|max:20',
            'cedente_nombre' => 'required|string|max:150',
            'cedente_dni' => 'nullable|string|max:50',
            'cedente_telefono' => 'nullable|string|max:50',
            'cesionario_nombre' => 'required|string|max:150',
            'cesionario_ssn' => 'nullable|string|max:50',
            'cesionario_dob' => 'nullable|string|max:50',
            'cesionario_direccion' => 'nullable|string|max:255',
            'cesionario_telefono' => 'nullable|string|max:50',
            'cesionario_email' => 'nullable|string|max:100',
            'matricula' => 'required|string|max:50',
            'marca' => 'nullable|string|max:100',
            'modelo' => 'nullable|string|max:100',
            'vin' => 'nullable|string|max:50',
            'tiene_suplemento' => 'nullable|boolean',
            'suplemento_recibo' => 'nullable|string|max:50',
            'suplemento_cantidad' => 'nullable|string|max:50',
            'fecha_cesion' => 'nullable|string|max:50',
            'estado' => 'required|string|max:50',
            'observaciones' => 'nullable|string',
            'client_id' => 'nullable|integer',
            'policy_id' => 'nullable|integer',
            'sede_id' => 'nullable|integer',
            'agente' => 'nullable|string|max:50',
        ]);

        $validated['tiene_suplemento'] = $request->boolean('tiene_suplemento') || !empty($validated['suplemento_recibo']) || !empty($validated['suplemento_cantidad']);

        $pol = Policy::where('numero_poliza', trim($validated['numero_poliza']))->first();
        if ($pol) {
            $validated['policy_id'] = $pol->id;
            $validated['client_id'] = $pol->client_id;
            if (empty($validated['sede_id'])) {
                $validated['sede_id'] = $pol->sede_id ?: $pol->client?->sede_id;
            }
            if (empty($validated['agente'])) {
                $validated['agente'] = $pol->agente ?: $pol->client?->agente;
            }
        }

        if (empty($validated['fecha_cesion'])) {
            $validated['fecha_cesion'] = date('d/m/Y');
        }

        $cesion = CesionPoliza::create($validated);

        return redirect()->route('operations.cesiones.index')
            ->with('success', "Cesión de póliza #{$cesion->numero_poliza} registrada correctamente para {$cesion->cesionario_nombre}.");
    }

    public function updateCesion(Request $request, CesionPoliza $cesion)
    {
        $validated = $request->validate([
            'estado' => 'required|string|max:50',
            'cesionario_ssn' => 'nullable|string|max:50',
            'cesionario_dob' => 'nullable|string|max:50',
            'cesionario_direccion' => 'nullable|string|max:255',
            'cesionario_telefono' => 'nullable|string|max:50',
            'cesionario_email' => 'nullable|string|max:100',
            'tiene_suplemento' => 'nullable|boolean',
            'suplemento_recibo' => 'nullable|string|max:50',
            'suplemento_cantidad' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $validated['tiene_suplemento'] = $request->boolean('tiene_suplemento') || !empty($validated['suplemento_recibo']) || !empty($validated['suplemento_cantidad']);

        $cesion->update($validated);

        return redirect()->route('operations.cesiones.index')
            ->with('success', "Cesión de póliza #{$cesion->numero_poliza} actualizada.");
    }

    public function documentoCesionPoliza(CesionPoliza $cesion)
    {
        $fecha = $cesion->fecha_cesion ?: ($cesion->created_at ? $cesion->created_at->format('d/m/Y') : date('d/m/Y'));
        $dia = date('d');
        $mesNombre = 'OCTUBRE';
        $ano = date('Y');

        if (preg_match('/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/', trim($fecha), $m)) {
            $dia = (int)$m[1];
            $meses = [
                1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO', 4 => 'ABRIL',
                5 => 'MAYO', 6 => 'JUNIO', 7 => 'JULIO', 8 => 'AGOSTO',
                9 => 'SEPTIEMBRE', 10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
            ];
            $mesNombre = $meses[(int)$m[2]] ?? 'OCTUBRE';
            $ano = $m[3];
        }

        return view('operations.cesiones.documento', compact('cesion', 'dia', 'mesNombre', 'ano'));
    }
}
